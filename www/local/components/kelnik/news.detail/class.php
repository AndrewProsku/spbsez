<?php

namespace Kelnik\News\Component;

use Bex\Bbc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\News\News\ImageToNewsTable;
use Kelnik\News\News\NewsTable;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\News\News\TagsTable;
use Kelnik\Refbook\Model\PresTable;

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

        self::registerCacheTag('kelnik:newsRow_' . $element['ID']);

        $element['IMAGES'] = ImageToNewsTable::getList([
            'select' => [
                'VALUE'
            ],
            'filter' => [
                'ENTITY_ID' => $element['ID']
            ]
        ])->fetchAll();

        if ($element['IMAGES']) {
            $element['IMAGES'] = \Kelnik\Helpers\BitrixHelper::prepareFileFields(
                $element['IMAGES'],
                [
                    'VALUE' => 'full'
                ]
            );

            foreach ($element['IMAGES'] as &$v) {
                $v = [
                    'SRC' => \CFile::GetFileSRC($v['VALUE']),
                    'DESCRIPTION' => $v['VALUE']['DESCRIPTION']
                ];
            }
        }

        $element['TAGS'] = TagsTable::getList([
            'select' => [
                'ID', 'NAME'
            ],
            'filter' => [
                '=ACTIVE' => TagsTable::YES,
                '=NEWS_TAG.ENTITY_ID' => $element['ID']
            ],
            'group' => [
                'ID'
            ],
            'order' => [
                'SORT' => 'ASC',
                'NAME' => 'ASC'
            ]
        ])->fetchAll();

        $element['TAGS'] = TagsTable::prepareTags(
            $element['TAGS'],
            ArrayHelper::getValue($this->arParams, 'SEF_FOLDER', '')
        );

        if ($element['DATE_SHOW'] instanceof \Bitrix\Main\Type\DateTime) {
            $element['DATE_SHOW_FORMAT'] = mb_strtolower(FormatDate('j F', $element['DATE_SHOW']));

            if ($element['DATE_SHOW']->format('Y') != date('Y')) {
                $element['DATE_SHOW_FORMAT'] .= ' ' . $element['DATE_SHOW']->format('Y');
            }
        }

        $this->arResult = $element;
        $this->arResult['SEO_TAGS']['TITLE'] = $element['NAME'] . ' | ОЭЗ СПб';

        $this->setSeoTags();
        $this->setResultCacheKeys([
            'ID',
            'CODE',
            'NAME',
            'SECTION_ID',
            'SEO_TAGS'
        ]);
    }
}
