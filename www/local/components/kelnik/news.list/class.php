<?php

namespace Kelnik\News\Component;

use Bex\Bbc;
use Bitrix\Main\Context;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\ImageResizer\Resizer;
use Kelnik\News\Categories\CategoriesTable;
use Kelnik\News\News\NewsTable;
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
        'SECTION_ID' => ['type' => 'int', 'error' => false]
    ];

    public function onPrepareComponentParams($arParams)
    {
        $arParams['A_FILTER']['YEAR'] = false;
        $arParams['A_FILTER']['TAG'] = false;

        if ($arParams['USE_ADVANCE_FILTER'] == 'Y') {
            $fields = [
                'tag' => 'TAG',
                'year' => 'YEAR'
            ];
            $request = Context::getCurrent()->getRequest();

            $isAjax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            foreach ($fields as $k => $v) {
                $arParams['A_FILTER'][$v] = $isAjax && $request->isPost()
                                            ? $request->getPost($k)
                                            : $request->getQuery($k);

                if (is_array($arParams['A_FILTER'][$v])) {
                    $arParams['A_FILTER'][$v] = array_map('intval', $arParams['A_FILTER'][$v]);
                    continue;
                }

                if ($k !== 'year') {
                    continue;
                }

                $arParams['A_FILTER'][$v] = (int) $arParams['A_FILTER'][$v];
            }
        }

        return parent::onPrepareComponentParams($arParams);
    }

    protected function executeMain()
    {
        $this->setResultCacheKeys(['ELEMENTS', 'TAGS', 'YEARS', 'CNT']);
        $filter = [
            '=ACTIVE' => NewsTable::YES
        ];

        if ($this->arParams['SECTION_ID']) {
            $filter['=CAT_ID'] = $this->arParams['SECTION_ID'];
            $filter['=CAT.ACTIVE'] = CategoriesTable::YES;
        }

        $this->arResult['YEARS'] = $this->getYears($filter);
        $activeElements = self::getActiveElements($this->arResult['YEARS']);

        $this->arResult['CNT'] = count($activeElements);

        if (!$activeElements) {
            return true;
        }

        $this->arResult['TAGS'] = self::getTags($activeElements);
        $this->arResult['TAGS'] = TagsTable::prepareTags(
            $this->arResult['TAGS'],
            ArrayHelper::getValue($this->arParams, 'SEF_FOLDER', '')
        );

        $onPage = !empty($this->arParams['ELEMENTS_COUNT'])
                    ? (int)$this->arParams['ELEMENTS_COUNT']
                    : NewsTable::ITEMS_ON_PAGE;

        $limit = $onPage + 1;

        $offset = $this->isAjax()
                    ? ArrayHelper::getValue($_REQUEST, 'showed', 0)
                    : 0;

        try {
            $select = [
                '*',
                'SECTION_ID'     => 'CAT_ID',
                'SECTION_CODE'   => 'CAT.CODE',
                'ELEMENT_ID'     => 'ID',
                'ELEMENT_CODE'   => 'CODE'
            ];
            $filter = [
                '=ID' => $activeElements
            ];

            $reCount = false;

            if ($this->arParams['A_FILTER']['YEAR']) {
                $reCount = true;
                $filter['=YEAR'] = $this->arParams['A_FILTER']['YEAR'];
                $select[] = new ExpressionField(
                    'YEAR',
                    'YEAR(%s)',
                    'DATE_SHOW'
                );

                foreach ($this->arResult['YEARS'] as  &$v) {
                    $v['SELECTED'] = is_array($this->arParams['A_FILTER']['YEAR']) && in_array((int)$v['NAME'], $this->arParams['A_FILTER']['YEAR'])
                                     || (int)$v['NAME'] === $this->arParams['A_FILTER']['YEAR'];
                }
                unset($v);
            }

            if ($this->arParams['A_FILTER']['TAG']) {
                $reCount = true;
                $filter['=TAGS.VALUE'] = $this->arParams['A_FILTER']['TAG'];
                foreach ($this->arResult['TAGS'] as  &$v) {
                    $v['SELECTED'] = is_array($this->arParams['A_FILTER']['TAG']) && in_array((int)$v['ID'], $this->arParams['A_FILTER']['TAG'])
                        || $v['ID'] == $this->arParams['A_FILTER']['TAG'];
                }
                unset($v);
            }

            if ($reCount) {
                $this->arResult['CNT'] = NewsTable::getRow([
                    'select' => [
                        new ExpressionField(
                            'CNT',
                            'COUNT(DISTINCT %s)',
                            'ID'
                        ),
                        new ExpressionField(
                            'YEAR',
                            'YEAR(%s)',
                            'DATE_SHOW'
                        )
                    ],
                    'filter' => $filter
                ]);
                $this->arResult['CNT'] = ArrayHelper::getValue($this->arResult['CNT'], 'CNT', 0);
            }

            if ($this->arResult['CNT']) {
                $rsElements = NewsTable::getList($r =
                    [
                        'select' => $select,
                        'filter' => $filter,
                        'order' => $this->getParamsSort(),
                        'limit' => $limit,
                        'offset' => $offset
                    ]
                )->FetchAll();
            }
        } catch (\Exception $e) {
            $rsElements = [];
        }

        $this->arResult['ELEMENTS'] = [];
        if (!$this->isAjax() && $this->arParams['SET_404'] === 'Y' && !$rsElements) {
            $this->return404();
        }

        if ($rsElements) {
            $ids = $tags = [];

            $rsElements = \Kelnik\Helpers\BitrixHelper::prepareFileFields(
                $rsElements,
                ['IMAGE' => 'full', 'IMAGE_PREVIEW' => 'full']
            );

            foreach ($rsElements as $element) {
                if (empty($element['IMAGE_PREVIEW_PATH'])) {
                    $element['IMAGE_PREVIEW_PATH'] = '/images/news/no-img.svg';
                }
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
                $element['TAGS'] = [];

                foreach ($this->arResult['TAGS'] as $tag) {
                    if(!in_array($element['ID'], $tag['NEWS_IDS'])) {
                        continue;
                    }

                    $element['TAGS'][] = [
                        'LINK' => $tag['LINK'],
                        'NAME' => $tag['NAME']
                    ];
                }

                $this->arResult['ELEMENTS'][] = $element;
                $ids[$element['ID']] = $element['ID'];
            }
            unset($rsElements);
        }

        $this->arResult['MORE_TEXT'] = $this->arResult['MORE'] = false;
        $newsCnt = count($this->arResult['ELEMENTS']);

        if ($newsCnt > $onPage) {
            $this->arResult['MORE'] = $newsCnt - $onPage;
        }

        if ($this->arResult['MORE']) {
            $this->arResult['ELEMENTS'] = array_slice($this->arResult['ELEMENTS'], 0, $onPage);
        }
    }

    protected function executeEpilog()
    {
        if ($this->isAjax() || !Context::getCurrent()->getRequest()->getQueryList()->toArray()) {
            return;
        }

        Asset::getInstance()->addString(
            '<link rel="canonical" href="' .
            (\CMain::IsHTTPS() ? 'https' : 'http') .
            '://' .  $_SERVER['HTTP_HOST'] .
            $this->arParams['SEF_FOLDER'] . '">'
        );
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

    public static function getTags(array $activeElements): array
    {
        if (!$activeElements) {
            return [];
        }

        try {
            return TagsTable::getList([
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
                    '=NEWS_TAG.ENTITY_ID' => $activeElements
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
        }

        return [];
    }

    public function getYears(array $filter): array
    {
        try {
            return NewsTable::getList([
                'select' => [
                    new ExpressionField(
                        'NAME',
                        'YEAR(%s)',
                        'DATE_SHOW'
                    ),
                    new ExpressionField(
                        'ELEMENTS',
                        'GROUP_CONCAT(%s)',
                        'ID'
                    )
                ],
                'filter' => $filter,
                'group' => [
                    'NAME'
                ],
                'order' => [
                    'NAME' => 'DESC'
                ]
            ])->fetchAll();
        } catch (\Exception $e) {
        }

        return [];
    }

    public static function getActiveElements(array $years): array
    {
        if (!$years) {
            return [];
        }

        return explode(
            ',',
            implode(
                ',',
                array_column($years, 'ELEMENTS')
            )
        );
    }
}
