<?php

namespace Kelnik\News\Component;

use Bex\Bbc;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\ImageResizer\Resizer;
use Kelnik\News\Categories\CategoriesTable;
use Kelnik\News\News\NewsTable;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Helpers\PluralHelper;
use Kelnik\News\News\TagsTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class NewsList extends Bbc\Basis
{
    use Bbc\Traits\Elements;

    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.news', 'iblock', 'kelnik.imageresizer'];
    protected $checkParams = [
        'SECTION_ID' => ['type' => 'int', 'error' => false],
        'ACTION' => ['type' => 'string', 'error' => false]
    ];

    protected function executeMain()
    {
        $this->setResultCacheKeys(['ELEMENTS', 'TAGS']);
        $filter = $this->getParamsFilters();

        if ($this->arParams['SECTION_ID']) {
            $filter['=CAT_ID'] = $this->arParams['SECTION_ID'];
            $filter['=SECTION_ACTIVE'] = CategoriesTable::YES;
        }

        if ($this->arParams['ACTION'] === 'Y') {
            $filter['=ACTION'] = NewsTable::YES;
        }

        $this->arResult['CNT'] = NewsTable::getRow(
            [
                'select' => [
                    'SECTION_ID' => 'CAT_ID',
                    'SECTION_CODE' => 'CAT.CODE',
                    'SECTION_ACTIVE' => 'CAT.ACTIVE',
                    new ExpressionField('CNT', 'COUNT(%s)', 'ID')
                ],
                'filter' => $filter
            ]
        );

        $this->arResult['PAGE'] = !empty($_REQUEST['page'])
                                    ? (int)$_REQUEST['page']
                                    : 1;

        $onPage = !empty($this->arParams['ELEMENTS_COUNT'])
                    ? (int)$this->arParams['ELEMENTS_COUNT']
                    : NewsTable::ITEMS_ON_PAGE;

        $limit = $this->isAjax()
                    ? $onPage * 2
                    : ($onPage * $this->arResult['PAGE']) + $onPage;

        $offset = $this->isAjax()
                    ? $onPage * ($this->arResult['PAGE'] - 1)
                    : 0;

        try {
            $rsElements = NewsTable::getList(
                [
                    'select' => [
                        '*',
                        'SECTION_ID'     => 'CAT_ID',
                        'SECTION_CODE'   => 'CAT.CODE',
                        'ELEMENT_ID'     => 'ID',
                        'ELEMENT_CODE'   => 'CODE',
                        'SECTION_ACTIVE' => 'CAT.ACTIVE'
                    ],
                    'filter' => $filter,
                    'order'  => $this->getParamsSort(),
                    'limit'  => $limit,
                    'offset' => $offset
                ]
            )->FetchAll();
        } catch (\Exception $e) {
            $rsElements = [];
        }

        $this->arResult['ELEMENTS'] = [];

        if ($rsElements) {
            $ids = $tags = [];

            $rsElements = \Kelnik\Helpers\BitrixHelper::prepareFileFields(
                $rsElements,
                ['IMAGE' => 'full', 'IMAGE_PREVIEW' => 'full']
            );

            foreach ($rsElements as $element) {
                $element['DETAIL_PAGE_URL'] = $this->getElementUrl($element);
                if (is_array($element['IMAGE_PREVIEW']) && !empty($element['IMAGE_PREVIEW'])) {
                    $element['IMAGE_PREVIEW_PATH'] = Resizer::getResizedPath(
                        $element['IMAGE_PREVIEW'],
                        [
                            'width' => 640,
                            'height' => 360,
                            'crop' => true
                        ]
                    );
                }
                if ($element['DATE_SHOW'] instanceof \Bitrix\Main\Type\DateTime) {
                    $element['DATE_SHOW_FORMAT'] = mb_strtolower(FormatDate('j F', $element['DATE_SHOW']));

                    if ($element['DATE_SHOW']->format('Y') != date('Y')) {
                        $element['DATE_SHOW_FORMAT'] .= ' ' . $element['DATE_SHOW']->format('Y');
                    }
                }
                $this->arResult['ELEMENTS'][] = $element;
                $ids[$element['ID']] = $element['ID'];
            }
            unset($rsElements);

            try {
                $this->arResult['TAGS'] = TagsTable::getList([
                    'select' => [
                        'ID', 'NAME',
                        new ExpressionField(
                            'NEWS_IDS',
                            'GROUP_CONCAT(%s SEPARATOR \',\')',
                            'NEWS_TAG.ENTITY_ID'
                        )
                    ],
                    'filter' => [
                        '=ACTIVE' => TagsTable::YES,
                        '=NEWS_TAG.ENTITY_ID' => array_values($ids)
                    ],
                    'group' => [
                        'ID'
                    ],
                    'order' => [
                        'SORT' => 'ASC',
                        'NAME' => 'ASC'
                    ]
                ])->fetchAll();
            } catch (\Exception $e) {
                $this->arResult['TAGS'] = [];
            }

            $this->arResult['TAGS'] = TagsTable::prepareTags(
                $this->arResult['TAGS'],
                ArrayHelper::getValue($this->arParams, 'SEF_FOLDER', '')
            );
        }

        if ($this->arParams['SET_404'] === 'Y' && !$this->arResult['ELEMENTS']) {
            $this->return404();
        }

        $this->arResult['MORE_TEXT'] = $this->arResult['MORE'] = false;
        $newsCnt = count($this->arResult['ELEMENTS']);

        if ($newsCnt > $onPage) {
            $this->arResult['MORE'] = $newsCnt - ($onPage * $this->arResult['PAGE']);

            if ($this->arResult['MORE']) {
                $this->arResult['MORE_TEXT'] = PluralHelper::pluralForm(
                    $this->arResult['MORE'],
                    [
                        Loc::getMessage('KELNIK_NEWS_LIST_p1'),
                        Loc::getMessage('KELNIK_NEWS_LIST_p2'),
                        Loc::getMessage('KELNIK_NEWS_LIST_p3'),
                    ]
                );

                $this->arResult['ELEMENTS'] = array_slice($this->arResult['ELEMENTS'], 0, $onPage * $this->arResult['PAGE']);
            }
        }
    }

    protected function executeEpilog()
    {
        //TODO: set canonical
    }

    public function getElementUrl($el)
    {
        if (!$el) {
            return null;
        }

        $arParams = $this->getParent()->arParams;
        if (!$arParams) {
            $arParams = $this->arParams;
        }

        $fields = [
            'SECTION_ID',
            'SECTION_CODE',
            'SECTION_PATH',
            'ELEMENT_ID',
            'ELEMENT_CODE'
        ];

        if (!empty($arParams['SEF_URL_TEMPLATES']['detail'])) {
            $str = $arParams['SEF_URL_TEMPLATES']['detail'];
            foreach ($fields as $field) {
                if (!isset($el[$field])) {
                    continue;
                }

                $str = str_replace('#' . $field . '#', $el[$field], $str);
            }

            return $arParams['SEF_FOLDER'] . $str;
        }

        return '?ID=' . $el['ID'];
    }
}
