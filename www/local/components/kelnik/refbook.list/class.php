<?php

namespace Kelnik\Refbook\Component;

use Bex\Bbc;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Refbook\Model\ResidentTable;
use Kelnik\Refbook\Model\ResidentTypesTable;
use Kelnik\Refbook\Model\ImageToResidentTable;
use Kelnik\Refbook\Types;
use Kelnik\UserData\Model\ContactTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class RefBookList extends Bbc\Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.refbook'];
    protected $checkParams = [
        'SECTION' => ['type' => 'int', 'error' => false]
    ];

    protected function executeProlog()
    {
        global $APPLICATION;

        if ($this->arParams['SECTION'] === Types::TYPE_RESIDENT) {
            $this->arParams['RESIDENT_TYPE'] = (int) ArrayHelper::getValue($_GET, 'type');
            $APPLICATION->SetPageProperty(
                'residentCategory',
                $this->arParams['RESIDENT_TYPE']
                    ? 'category-' . $this->arParams['RESIDENT_TYPE']
                    : 'all-categories'
            );
        }
    }

    protected function executeMain()
    {
        $classes = Types::getClassesByTypeList();

        if (!$this->arParams['SECTION'] || !isset($classes[$this->arParams['SECTION']])) {
            return false;
        }

        self::registerCacheTag('kelnik:refBookList_' . $this->arParams['SECTION']);

        $className = $classes[$this->arParams['SECTION']];
        $selectFields = [
            Types::TYPE_REVIEW => ['ID', 'NAME', 'NAME_EN', 'ALIAS', 'IMAGE_ID', 'IMAGE_BG_ID', 'COMMENT', 'COMMENT_EN', 'PREVIEW', 'PREVIEW_EN', 'BODY'],
            Types::TYPE_DOCS => ['ID', 'NAME', 'FILE_ID'],
            Types::TYPE_PRESENTATION => ['ID', 'NAME', 'FILE_ID'],
            Types::TYPE_TEAM => ['ID', 'NAME', 'IMAGE_ID', 'TEXT', 'NAME_EN', 'TEXT_EN'],
            Types::TYPE_PARTNER => ['ID', 'NAME', 'IMAGE_ID', 'IMAGE_ID_EN', 'NAME_EN'],
            Types::TYPE_STRATEGY_DOCS => ['ID', 'NAME', 'FILE_ID'],
        ];

        $select = ArrayHelper::getValue(
            $selectFields,
            $this->arParams['SECTION'] ,
            ['ID', 'NAME', 'IMAGE_ID', 'TEXT']
        );

        $filter = [
            '=ACTIVE' => $className::YES
        ];
        
        if (in_array($this->arParams['SECTION'], [Types::TYPE_DOCS, Types::TYPE_PRESENTATION, Types::TYPE_STRATEGY_DOCS])) {
            $filter['=SITE_ID'] = SITE_ID;
        }

        $this->arResult['HEADER'] = Loc::getMessage('KELNIK_REFBOOK_HEADER_' . $this->arParams['SECTION']);
        $this->arResult['MORE'] = Loc::getMessage('KELNIK_REFBOOK_MORE_' . $this->arParams['SECTION']);

        if ($this->arParams['SECTION'] !== Types::TYPE_RESIDENT) {
            if ($this->arParams['SECTION'] === Types::TYPE_REVIEW && LANGUAGE_ID == 'en') {
                $filter['!NAME_EN'] = false;
            }
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

            $this->arResult['ELEMENTS'] = $this->replaceFields(
                $this->arResult['ELEMENTS'],
                strtoupper(Context::getCurrent()->getLanguage())
            );

            $this->arResult['ELEMENTS'] = BitrixHelper::prepareFileFields($this->arResult['ELEMENTS'], ['IMAGE_*', 'FILE_*' => 'full']);

            if (!$this->arResult['ELEMENTS']) {
                return true;
            }

            foreach ($this->arResult['ELEMENTS'] as $k => &$v) {

                $v['JSON'] = base64_encode(json_encode($v));

                if (!in_array($this->arParams['SECTION'], [Types::TYPE_DOCS, Types::TYPE_PRESENTATION, Types::TYPE_STRATEGY_DOCS])) {
                    continue;
                }

                if (empty($v['FILE_ID']['ID'])) {
                    unset($this->arResult['ELEMENTS'][$k]);
                    continue;
                }

                $v['FILE_ID']['EXT'] = strtolower(pathinfo($v['FILE_ID']['ORIGINAL_NAME'], PATHINFO_EXTENSION));
                $v['FILE_ID']['DATE_FORMAT'] = $v['FILE_ID']['TIMESTAMP_X']->format('d.m.Y');
                $v['FILE_ID']['FILE_SIZE_FORMAT'] = \CFile::FormatSize($v['FILE_ID']['FILE_SIZE']);
            }
            unset($v);

            return true;
        }

        try {
            $this->arResult['ELEMENTS'] = ResidentTable::getAssoc([
                'select' => [
                    '*',
                    'TYPE_NAME' => 'TYPE.NAME',
                    'TYPE_NAME_EN' => 'TYPE.NAME_EN',
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

        $itemsID = [];
        foreach ($this->arResult['ELEMENTS'] as &$v) {
            if (!isset($this->arResult['TYPES'][$v['TYPE_ID']])) {
                $this->arResult['TYPES'][$v['TYPE_ID']] = [
                    'ID' => $v['TYPE_ID'],
                    'NAME' => $v['TYPE_NAME'],
                    'NAME_EN' => $v['TYPE_NAME_EN'],
                    'SORT' => $v['TYPE_SORT']
                ];
            }
            $this->arResult['TYPES'][$v['TYPE_ID']]['CNT']++;

            if (!$v['PLACE']) {
                continue;
            }
            $v['PLACE_NAME'] = ResidentTable::getPlaceName($v['PLACE']);
            $v['PLACE_LINK'] = ResidentTable::getPlaceLink($v['PLACE']);

            $itemsID[] = $v["ID"];

            $this->arResult['ELEMENTS'][$v['ID']]['IMAGES'] = [];
        }

        //select images
        if(count($itemsID) > 0){            
            $imagesResident = ImageToResidentTable::getAssoc([
                'select' => [
                    'ID',
                    'VALUE',
                    'ENTITY_ID'
                ],
                'filter' => [
                    '=ENTITY_ID' => $itemsID,
                ],
                'order' => [
                    'ID' => 'ASC'
                ]
            ], 'ID');
            $arAssocImageToEntity = [];
            foreach($imagesResident as $arImage){
                $this->arResult['ELEMENTS'][$arImage['ENTITY_ID']]['IMAGES'][] = \CFile::GetPath($arImage['VALUE']);
            }
         
        }

        //TODO select contact user
       
        unset($itemsID);
        unset($v);

        $langId = strtoupper(Context::getCurrent()->getLanguage());

        foreach (['ELEMENTS', 'TYPES'] as $type) {
            $this->arResult[$type] = $this->replaceFields(
                $this->arResult[$type],
                $langId
            );
        }

        usort($this->arResult['TYPES'], function ($a, $b) {
            if ($a == $b) {
                return 0;
            }

            return $a > $b ? 1 : -1;
        });

        $this->arResult['ELEMENTS'] = BitrixHelper::prepareFileFields($this->arResult['ELEMENTS'], ['IMAGE_*']);
    }

    protected function executeEpilog()
    {
        global $APPLICATION;

        if (!$this->isAjax()
            && !empty($this->arParams['RESIDENT_TYPE'])
            && $this->arParams['COMPONENT_TEMPLATE'] == 'residents-full'
        ) {
            $APPLICATION->SetPageProperty(
                'canonical',
                (\CMain::IsHTTPS() ? 'https' : 'http') . '://' .  $_SERVER['HTTP_HOST'] . $APPLICATION->GetCurDir()
            );
        }
    }

    protected function replaceFields(array $data, $langId)
    {
        if (!$data || !$langId) {
            return $data;
        }

        foreach ($data as &$v) {
            foreach ($v as $key => $val) {
                if (empty($v[$key . '_' . $langId])) {
                    continue;
                }
                $v[$key] = $v[$key . '_' . $langId];
                unset($v[$key . '_' . $langId]);
            }
        }
        unset($v);

        return $data;
    }
}
