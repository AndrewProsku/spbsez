<?php

namespace Kelnik\News\Component;

use Bex\Bbc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\News\News\ImageToNewsTable;
use Kelnik\News\News\NewsTable;
use Kelnik\Helpers\BitrixHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class NewsDetail extends Bbc\Basis
{
    use Bbc\Traits\Elements;

    protected $needModules = ['kelnik.news', 'iblock'];
    protected $checkParams = [
        'ELEMENT_ID' => ['type' => 'int', 'error' => false],
        'ELEMENT_CODE' => ['type' => 'string', 'error' => false]
    ];

    protected function executeProlog()
    {
        if (!$this->arParams['ELEMENT_ID'] && !$this->arParams['ELEMENT_CODE']) {
            $this->return404(true);
        }
    }

    protected function executeMain()
    {
        $element = NewsTable::getRow(
            [
                'select' => [
                    '*',
                    'SECTION_ID' => 'CAT_ID',
                    'SECTION_CODE' => 'CAT.CODE',
                    'ELEMENT_ID' => 'ID',
                    'ELEMENT_CODE' => 'CODE',
                    'SECTION_ACTIVE' => 'CAT.ACTIVE'
                ],
                'filter' => $this->getParamsFilters()
            ]
        );

        if (!$element && $this->arParams['SET_404'] === 'Y') {
            $this->return404();
        }

        $element['IMAGES'] = ImageToNewsTable::getList([
            'select' => [
                'VALUE'
            ],
            'filter' => [
                'ENTITY_ID' => $element['ID']
            ]
        ])->fetchAll();

        $element['IMAGES'] = ArrayHelper::getColumn(
            BitrixHelper::prepareFileFields($element['IMAGES'], ['VALUE']),
            'VALUE'
        );

        $element = BitrixHelper::prepareFileFields($element, ['IMAGE', 'IMAGE_PREVIEW']);

        if ($arElement = $this->processingElementsResult($element)) {
            $this->arResult = array_merge($this->arResult, $arElement);
        }

        $this->arResult['SEO_TAGS']['TITLE'] = htmlentities($arElement['NAME'], ENT_QUOTES, 'UTF-8');

        $this->setSeoTags();

        $this->setResultCacheKeys([
            'ID',
            'CODE',
            'NAME',
            'SECTION_ID',
        ]);
    }
}
