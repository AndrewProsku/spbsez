<?php

namespace Kelnik\Refbook\Component;

use Bex\Bbc;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Refbook\Model\PartnerTable;
use Kelnik\Refbook\Model\ResidentTable;
use Kelnik\Refbook\Model\ResidentTypesTable;
use Kelnik\Refbook\Model\ReviewTable;
use Kelnik\Refbook\Model\TeamTable;
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
            Types::TYPE_PARTNER => PartnerTable::class,
            Types::TYPE_RESIDENT => ResidentTable::class,
            Types::TYPE_REVIEW => ReviewTable::class,
            Types::TYPE_TEAM => TeamTable::class
        ];

        if (!$this->arParams['SECTION'] || !isset($classes[$this->arParams['SECTION']])) {
            return false;
        }

        $className = $classes[$this->arParams['SECTION']];

        $select = $this->arParams['SECTION'] == Types::TYPE_REVIEW
                    ? ['ID', 'NAME', 'ALIAS', 'IMAGE_ID', 'IMAGE_BG_ID', 'COMMENT', 'PREVIEW']
                    : ['ID', 'NAME', 'IMAGE_ID', 'TEXT'];

        $filter = [
            '=ACTIVE' => $className::YES
        ];

        if ((int)$this->arParams['SECTION'] !== Types::TYPE_RESIDENT) {
            try {
                $this->arResult['ELEMENTS'] = $className::getList(
                    [
                        'select' => $select,
                        'filter' => $filter,
                        'order' => [
                            'SORT' => 'ASC'
                        ]
                    ]
                )->FetchAll();
            } catch (\Exception $e) {
                return [];
            }

            $this->arResult['ELEMENTS'] = BitrixHelper::prepareFileFields($this->arResult['ELEMENTS'], ['IMAGE_*']);

            return true;
        }

        try {
            $this->arResult['ELEMENTS'] = ResidentTable::getAssoc([
                'select' => [
                    '*',
                    'TYPE_NAME' => 'TYPE.NAME',
                    'TYPE_SORT' => 'TYPE.SORT'
                ],
                'filter' => [
                    '=TYPE.ACTIVE' => ResidentTypesTable::YES,
                    '=ACTIVE' => ResidentTable::YES
                ],
                'order' => [
                    'SORT' => 'ASC',
                    'NAME' => 'ASC'
                ]
            ], 'ID');
        } catch (\Exception $e) {
            return [];
        }

        if (!$this->arResult['ELEMENTS']) {
            return false;
        }

        $this->arResult['TYPES'] = [];

        foreach ($this->arResult['ELEMENTS'] as &$v) {
            if (!isset($this->arResult['TYPES'][$v['TYPE_ID']])) {
                $this->arResult['TYPES'][$v['TYPE_ID']] = [
                    'ID' => $v['TYPE_ID'],
                    'NAME' => $v['TYPE_NAME'],
                    'SORT' => $v['TYPE_SORT']
                ];
            }
            $this->arResult['TYPES'][$v['TYPE_ID']]['CNT']++;

            if (!$v['PLACE']) {
                continue;
            }
            $v['PLACE_NAME'] = ResidentTable::getPlaceName($v['PLACE']);
            $v['PLACE_LINK'] = ResidentTable::getPlaceLink($v['PLACE']);
        }
        unset($v);

        usort($this->arResult['TYPES'], function ($a, $b) {
            if ($a == $b) {
                return 0;
            }

            return $a > $b ? 1 : -1;
        });

        $this->arResult['ELEMENTS'] = BitrixHelper::prepareFileFields($this->arResult['ELEMENTS'], ['IMAGE_*']);
    }
}
