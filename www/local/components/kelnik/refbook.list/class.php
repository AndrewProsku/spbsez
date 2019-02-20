<?php

namespace Kelnik\Refbook\Component;

use Bex\Bbc;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Refbook\Model\DocsTable;
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
            Types::TYPE_TEAM => TeamTable::class,
            Types::TYPE_DOCS => DocsTable::class
        ];

        if (!$this->arParams['SECTION'] || !isset($classes[$this->arParams['SECTION']])) {
            return false;
        }

        $className = $classes[$this->arParams['SECTION']];
        $selectFields = [
            Types::TYPE_REVIEW => ['ID', 'NAME', 'ALIAS', 'IMAGE_ID', 'IMAGE_BG_ID', 'COMMENT', 'PREVIEW'],
            Types::TYPE_DOCS => ['ID', 'NAME', 'FILE_ID']
        ];

        $select = ArrayHelper::getValue(
            $selectFields,
            $this->arParams['SECTION'] ,
            ['ID', 'NAME', 'IMAGE_ID', 'TEXT']
        );

        $filter = [
            '=ACTIVE' => $className::YES
        ];

        if ($this->arParams['SECTION'] !== Types::TYPE_RESIDENT) {
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

            $this->arResult['ELEMENTS'] = BitrixHelper::prepareFileFields($this->arResult['ELEMENTS'], ['IMAGE_*', 'FILE_*' => 'full']);

            if ($this->arResult['ELEMENTS'] && $this->arParams['SECTION'] === Types::TYPE_DOCS) {
                foreach ($this->arResult['ELEMENTS'] as $k => &$v) {
                    if (empty($v['FILE_ID']['ID'])) {
                        unset($this->arResult['ELEMENTS'][$k]);
                        continue;
                    }

                    $v['FILE_ID']['EXT'] = strtolower(pathinfo($v['FILE_ID']['ORIGINAL_NAME'], PATHINFO_EXTENSION));
                    $v['FILE_ID']['DATE_FORMAT'] = $v['FILE_ID']['TIMESTAMP_X']->format('d.m.Y');
                    $v['FILE_ID']['FILE_SIZE_FORMAT'] = \CFile::FormatSize($v['FILE_ID']['FILE_SIZE']);
                }
                unset($v);
            }

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
