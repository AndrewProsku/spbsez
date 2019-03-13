<?php

namespace Kelnik\Info\Component;

use Bex\Bbc;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Info\Model\DocsTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class InfoDocsList extends Bbc\Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.info'];
    protected $checkParams = [
        'SECTION' => ['type' => 'int', 'error' => false]
    ];

    protected function executeMain()
    {
        if (!$this->arParams['SECTION']) {
            $this->abortCache();

            return;
        }

        $this->setResultCacheKeys(['YEAR', 'YEARS', 'ELEMENTS', 'TYPE_NAME']);
        self::registerCacheTag('kelnik:infoDocsList_' . $this->arParams['SECTION']);

        $this->arResult['YEARS'] = $this->arResult['ELEMENTS'] = [];
        $this->arResult['TYPE_NAME'] = '';

        try {
            $filter = [
                '=ACTIVE' => DocsTable::YES,
                '=TYPE_ID' => $this->arParams['SECTION']
            ];

            $this->arResult['YEARS'] = DocsTable::getList([
                'select' => [
                    new ExpressionField(
                        'NAME',
                        'DISTINCT YEAR(%s)',
                        'DATE_SHOW'
                    )
                ],
                'order' => [
                    'DATE_SHOW' => 'DESC'
                ]
            ])->fetchAll();

            if (!$this->arResult['YEARS']) {
                return;
            }

            $this->arResult['YEAR'] = ArrayHelper::getValue($this->arResult['YEARS'], '0.NAME');
            $this->arResult['YEARS'][0]['SELECTED'] = 1;

            $filter['ELEMENT_YEAR'] = $this->arResult['YEAR'];

            $this->arResult['ELEMENTS'] = DocsTable::getList([
                'select' => [
                    '*',
                    'TYPE_NAME' => 'TYPE.NAME'
                ],
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
                ]
            ])->fetchAll();
        } catch (\Exception $e) {
        }

        if (!$this->arResult['ELEMENTS']) {
            return;
        }

        $this->arResult['ELEMENTS'] = BitrixHelper::prepareFileFields($this->arResult['ELEMENTS'], ['FILE_ID' => 'full']);

        foreach ($this->arResult['ELEMENTS'] as $k => &$v) {
            if (empty($v['FILE_ID']['ID'])) {
                unset($this->arResult['ELEMENTS'][$k]);
                continue;
            }

            $v['DATE_SHOW_FORMAT'] = $v['DATE_SHOW']->format('Y-m-d');
            $v['DATE_SHOW_FORMAT_HUMAN'] = $v['DATE_SHOW']->format('d.m.Y');

            $v['FILE_ID']['EXT'] = strtolower(pathinfo($v['FILE_ID']['ORIGINAL_NAME'], PATHINFO_EXTENSION));
        }
        unset($v);

        $this->arResult['ELEMENTS'] = array_values($this->arResult['ELEMENTS']);

        if (!$this->arResult['ELEMENTS']) {
            return;
        }

        $this->arResult['TYPE_NAME'] = ArrayHelper::getValue($this->arResult['ELEMENTS'], '0.TYPE_NAME');
    }
}
