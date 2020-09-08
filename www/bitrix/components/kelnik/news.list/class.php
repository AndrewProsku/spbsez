<?php

namespace Kelnik\News\Component;

use Bex\Bbc;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use Kelnik\News\Categories\CategoriesTable;
use Kelnik\News\News\NewsTable;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Helpers\PluralHelper;

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
    protected $needModules = ['kelnik.news', 'iblock'];
    protected $checkParams = [
        'SECTION_ID' => ['type' => 'int', 'error' => false],
        'OBJECT_ID' => ['type' => 'int', 'error' => false],
        'ACTION' => ['type' => 'string', 'error' => false]
    ];

    protected function executeMain()
    {
        $filter = $this->getParamsFilters();

        if ($this->arParams['SECTION_ID']) {
            $filter['=CAT_ID'] = $this->arParams['SECTION_ID'];
            $filter['=SECTION_ACTIVE'] = CategoriesTable::YES;
        }

        if ($this->arParams['ACTION'] === 'Y') {
            $filter['=ACTION'] = NewsTable::YES;
        }

        if ($this->arParams['OBJECT_ID']) {
            $filter['=OBJECT_ID'] = $this->arParams['OBJECT_ID'];
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

        if (empty($this->arParams['USE_AJAX'])) {
            // TODO: Сделать обычную пагинацию
        }

        if ($this->isAjax() && $this->arResult['PAGE'] && $this->arParams['AJAX_TYPE'] == 'JSON') {
            $this->setResultCacheKeys(1);
        }

        $rsElements = NewsTable::getList(
            [
                'select' => [
                    '*',
                    'SECTION_ID' => 'CAT_ID',
                    'SECTION_CODE' => 'CAT.CODE',
                    'ELEMENT_ID' => 'ID',
                    'ELEMENT_CODE' => 'CODE',
                    'SECTION_ACTIVE' => 'CAT.ACTIVE'
                ],
                'filter' => $filter,
                'order' => $this->getParamsSort(),
                'limit' => $limit,
                'offset' => $offset
            ]
        )->FetchAll();

        $this->arResult['ELEMENTS'] = [];

        foreach ($rsElements as $element) {
            $element = BitrixHelper::prepareFileFields($element, ['IMAGE', 'IMAGE_PREVIEW']);
            $element['DETAIL_PAGE_URL'] = $this->getELementUrl($element);
            $this->arResult['ELEMENTS'][] = $element;
        }

        if ($this->arParams['SET_404'] === 'Y' && empty($this->arResult['ELEMENTS'])) {
            $this->return404();
        }

        $this->arResult['MORE_TEXT'] = $this->arResult['MORE'] = false;
        $newsCnt = count($this->arResult['ELEMENTS']);

        if ($newsCnt > $onPage) {
            $this->arResult['MORE'] = $this->isAjax()
                ? $newsCnt - $onPage
                : $newsCnt - ($onPage * $this->arResult['PAGE']);

            if ($this->arResult['MORE']) {
                $this->arResult['MORE_TEXT'] = PluralHelper::pluralForm(
                    $this->arResult['MORE'],
                    [
                        Loc::getMessage('KELNIK_NEWS_LIST_p1'),
                        Loc::getMessage('KELNIK_NEWS_LIST_p2'),
                        Loc::getMessage('KELNIK_NEWS_LIST_p3'),
                    ]
                );

                $this->arResult['ELEMENTS'] = $this->isAjax()
                    ? array_slice($this->arResult['ELEMENTS'], 0, $onPage)
                    : array_slice($this->arResult['ELEMENTS'], 0, $onPage * $this->arResult['PAGE']);
            }
        }

        //$this->setResultCacheKeys(['NAV_CACHED_DATA']);
    }

    protected function executeEpilog()
    {
        global $APPLICATION;

        if (!$this->isAjax()) {
            return;
        }

        $APPLICATION->RestartBuffer();

        $this->arResult['CNT'] = $this->arResult['CNT']['CNT'];

        unset($this->arResult['SECTION'], $this->arResult['IPROPERTY_VALUES'], $this->arResult['AJAX_REQUEST_PARAMS']);

        BitrixHelper::jsonResponse($this->arResult);
    }

    public function getELementUrl($el)
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
