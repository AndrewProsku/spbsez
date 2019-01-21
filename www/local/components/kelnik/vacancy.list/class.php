<?php

namespace Kelnik\Refbook\Component;

use Bex\Bbc;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\FormatHelper;
use Kelnik\Vacancy\Model\VacancyTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class VacancyList extends Bbc\Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.vacancy'];
    protected $checkParams = [];

    protected function executeMain()
    {
        $this->arResult['ELEMENTS'] = [];

        try {
            $this->arResult['ELEMENTS'] = VacancyTable::getAssoc([
                'filter' => [
                    '=ACTIVE' => VacancyTable::YES
                ],
                'order' => [
                    'SORT' => 'ASC'
                ]
            ], 'ID');
        } catch (\Exception $e) {
            $this->arResult['ELEMENTS'] = [];
        }

        if (!$this->arResult['ELEMENTS']) {
            return;
        }

        foreach ($this->arResult['ELEMENTS'] as &$v) {
            $v['PRICE_MIN'] = FormatHelper::priceFormat($v['PRICE_MIN']);
            $v['PRICE_MAX'] = FormatHelper::priceFormat($v['PRICE_MAX']);
        }
        unset($v);
    }
}
