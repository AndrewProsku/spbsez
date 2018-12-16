<?php

namespace Kelnik\Refbook\Component;

use Bex\Bbc;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;
use Kelnik\News\Categories\CategoriesTable;
use Kelnik\News\News\NewsTable;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Helpers\PluralHelper;
use Kelnik\Refbook\Model\PartnerTable;
use Kelnik\Refbook\Model\ResidentTable;
use Kelnik\Refbook\Model\ReviewTable;
use Kelnik\Refbook\Types;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class RefbookList extends Bbc\Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.refbook'];
    protected $checkParams = [
        'SECTION' => ['type' => 'int', 'error' => false]
    ];

    protected function executeMain()
    {
        $classes = [
            \Kelnik\Refbook\Types::TYPE_PARTNER => PartnerTable::class,
            \Kelnik\Refbook\Types::TYPE_RESIDENT => ResidentTable::class,
            \Kelnik\Refbook\Types::TYPE_REVIEW => ReviewTable::class
        ];

        if (!$this->arParams['SECTION'] || !isset($classes[$this->arParams['SECTION']])) {
            return;
        }

        $className = $classes[$this->arParams['SECTION']];

        $select = $this->arParams['SECTION'] == Types::TYPE_REVIEW
                    ? ['ID', 'NAME', 'ALIAS', 'IMAGE_ID', 'IMAGE_BG_ID', 'COMMENT', 'PREVIEW']
                    : ['ID', 'NAME', 'IMAGE_ID', 'TEXT'];

        $this->arResult['ELEMENTS'] = $className::getList(
            [
                'select' => $select,
                'filter' => [
                    '=ACTIVE' => $className::YES
                ],
                'order' => [
                    'SORT' => 'ASC'
                ]
            ]
        )->FetchAll();

        $this->arResult['ELEMENTS'] = BitrixHelper::prepareFileFields($this->arResult['ELEMENTS'], ['IMAGE_*']);
    }
}
