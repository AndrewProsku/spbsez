<?php

namespace Kelnik\Infrastructure\Component;

use Bex\Bbc;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Infrastructure\ElementTrait;
use Kelnik\Infrastructure\Model\PlatformTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!Loader::includeModule('bex.bbc')
    || !Loader::includeModule('kelnik.infrastructure')
) {
    return false;
}

Loc::loadMessages(__FILE__);

class InfrastructureDetail extends Bbc\Basis
{
    use Bbc\Traits\Elements;
    use ElementTrait;

    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.infrastructure', 'iblock'];
    protected $checkParams = [
        'ELEMENT_ID' => ['type' => 'int', 'error' => false],
        'ELEMENT_CODE' => ['type' => 'string', 'error' => false]
    ];

    protected function executeMain()
    {
        $this->setResultCacheKeys(['ELEMENT', 'ELEMENTS']);

        if (!$this->arParams['ELEMENT_ID']
            && !$this->arParams['ELEMENT_CODE']
            && $this->arParams['SET_404'] == 'Y'
        ) {
            $this->show404();

            return false;
        }

        $filter = [
            '=ACTIVE' => PlatformTable::YES
        ];

        if ($this->arParams['ELEMENT_ID']) {
            $filter['=ID'] = $this->arParams['ELEMENT_ID'];
        } elseif ($this->arParams['ELEMENT_CODE']) {
            $filter['=ALIAS'] = $this->arParams['ELEMENT_CODE'];
        }

        try {
            $this->arResult['ELEMENT'] = PlatformTable::getRow([
                'select' => ['*'],
                'filter' => $filter,
                'limit' => 1
            ]);

            if (!$this->arResult['ELEMENT'] && $this->arParams['SET_404'] == 'Y') {
                $this->show404();

                return false;
            }

            $this->arResult['ELEMENT'] = PlatformTable::replaceFieldsByLang($this->arResult['ELEMENT'], LANGUAGE_ID);
            $this->arResult['ELEMENT'] = current(BitrixHelper::prepareFileFields(
                [
                    $this->arResult['ELEMENT']
                ],
                [
                    'VIDEO_ID' => 'full',
                    'IMAGE_BG_ID' => 'path',
                    'AREA_BG_ID' => 'path'
                ]
            ));

            $this->arResult['ELEMENTS'] = PlatformTable::getAssoc([
                'select' => [
                    'ID', 'ALIAS',
                    'NAME_RU', 'NAME_EN'
                ],
                'filter' => [
                    '=ACTIVE' => PlatformTable::YES
                ],
                'order' => [
                    'SORT' => 'ASC'
                ]
            ]);

            foreach ($this->arResult['ELEMENTS'] as &$element) {
                $element = PlatformTable::replaceFieldsByLang($element, LANGUAGE_ID);
                $element['DETAIL_PAGE_URL'] = $this->getElementUrl($element);
            }

            $this->arResult['ELEMENT']['IMAGES'] = array_column(self::getImages((int) $this->arResult['ELEMENT']['ID']), 'VALUE');

        } catch (\Exception $e) {
            $this->abortCache();

            return false;
        }

        self::registerCacheTag('kelnik:infrastructureRow_' . $this->arResult['ELEMENT']['ID']);
    }

    protected static function getImages(int $elementId)
    {
        $imageNameSpace = '\Kelnik\Infrastructure\Model\Images' . ucfirst(mb_strtolower(LANGUAGE_ID)) . 'Table';

        if (!class_exists($imageNameSpace) || !$elementId) {
            return [];
        }

        $res = $imageNameSpace::getAssoc([
            'select' => ['VALUE'],
            'filter' => [
                '=ENTITY_ID' => $elementId
            ]
        ]);

        if (!$res) {
            return $res;
        }

        $res = BitrixHelper::prepareFileFields($res, ['VALUE' => 'full']);

        return $res;
    }

    protected function show404()
    {
        Tools::process404(
            'Not Found',
            true,
            true,
            true
        );
    }
}
