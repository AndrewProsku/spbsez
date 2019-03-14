<?php

namespace Kelnik\Info\Component;

use Bex\Bbc;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Info\Model\ProcTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class InfoProcList extends Bbc\Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.info'];
    protected $checkParams = [
        'YEAR' => ['type' => 'int', 'error' => false]
    ];

    protected  $offset = 0;

    protected function executeProlog()
    {
        $this->offset = (int) ArrayHelper::getValue($_REQUEST, 'showed', 0);

        $this->addCacheAdditionalId($this->offset);
    }

    protected function executeMain()
    {
        $this->setResultCacheKeys(['YEAR', 'YEARS', 'ELEMENTS']);
        self::registerCacheTag('kelnik:infoProcList');

        $this->arResult['YEARS'] = $this->arResult['ELEMENTS'] = [];
        $this->arResult['MORE']  = false;
        $this->arResult['YEAR']  = $this->arParams['YEAR'];

        try {
            $filter = [
                '=ACTIVE' => ProcTable::YES
            ];

            $this->arResult['YEARS'] = ProcTable::getAssoc([
                'select' => [
                    new ExpressionField(
                        'NAME',
                        'DISTINCT YEAR(%s)',
                        'DATE_SHOW'
                    )
                ],
                'filter' => $filter,
                'order' => [
                    'DATE_SHOW' => 'DESC'
                ]
            ], 'NAME');

            if (!$this->arResult['YEARS']) {
                return;
            }

            if (!$this->arParams['YEAR'] || !isset($this->arResult['YEARS'][$this->arParams['YEAR']])) {
                $years = $this->arResult['YEARS'];
                $this->arResult['YEAR'] = (int) ArrayHelper::getValue(array_shift($years), 'NAME');
                unset($years);
            }

            $this->arResult['YEARS'][$this->arResult['YEAR']]['SELECTED'] = 1;

            $filter['ELEMENT_YEAR'] = $this->arResult['YEAR'];

            $limit  = ArrayHelper::getValue($this->arParams, 'ELEMENTS_COUNT', ProcTable::ITEMS_PER_PAGE);

            $this->arResult['ELEMENTS'] = ProcTable::getList([
                'runtime' =>  [
                    new ExpressionField(
                        'ELEMENT_YEAR',
                        'YEAR(%s)',
                        'DATE_SHOW'
                    )
                ],
                'filter' => $filter,
                'order' => [
                    'SORT' => 'ASC',
                    'DATE_SHOW' => 'DESC'
                ],
                'limit' => $limit + 1,
                'offset' => $this->offset
            ])->fetchAll();
        } catch (\Exception $e) {
        }

        if (!$this->arResult['ELEMENTS']) {
            return;
        }

        if (count($this->arResult['ELEMENTS']) > $limit) {
            $this->arResult['ELEMENTS'] = array_slice($this->arResult['ELEMENTS'], 0, $limit);
            $this->arResult['MORE'] = true;
        }

        foreach ($this->arResult['ELEMENTS'] as $k => &$v) {
            $v['DATE_SHOW_FORMAT'] = $v['DATE_SHOW']->format('Y-m-d');
            $v['DATE_SHOW_FORMAT_HUMAN'] = $v['DATE_SHOW']->format('d.m.Y');
        }
        unset($v);

        $this->arResult['ELEMENTS'] = array_values($this->arResult['ELEMENTS']);
    }
}
