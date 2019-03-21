<?php

namespace Kelnik\Infrastructure\Component;

use Bex\Bbc;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Infrastructure\ElementTrait;
use Kelnik\Infrastructure\Model\PlatformTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !Loader::includeModule('kelnik.infrastructure')
) {
    return false;
}

Loc::loadMessages(__FILE__);

class InfrastructureList extends Bbc\Basis
{
    use Bbc\Traits\Elements;
    use ElementTrait;

    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.infrastructure', 'iblock'];
    protected $checkParams = [];

    protected function executeMain()
    {
        $this->arResult['MAP_DATA'] = [
            'center' => [
                59.942099,
                30.186182
            ],
            'scrollwheel' => false,
            'fullScreenControl' => false,
            'customZoomControl' => true,
            'htmlMarkers' => [],
            'markers' => []
        ];

        self::registerCacheTag('kelnik:infrastructureList');
        $this->setResultCacheKeys(['ELEMENTS', 'MAP_DATA']);

        try {
            $this->arResult['ELEMENTS'] = PlatformTable::getAssoc([
                'select' => [
                    'ID', 'ALIAS',
                    'NAME_RU', 'NAME_EN',
                    'MAP_COORDS_LAT',
                    'MAP_COORDS_LNG',
                    'TEXT_RU', 'TEXT_EN'
                ],
                'filter' => [
                    '=ACTIVE' => PlatformTable::YES
                ],
                'order' => [
                    'SORT' => 'ASC'
                ]
            ]);
        } catch (\Exception $e) {
            $this->abortCache();
            return;
        }

        if (!$this->arResult['ELEMENTS']) {
            return;
        }

        $this->arResult['ELEMENTS'] = BitrixHelper::prepareFileFields($this->arResult['ELEMENTS'], ['IMAGE_ID']);

        foreach ($this->arResult['ELEMENTS'] as &$element) {
            $element = PlatformTable::replaceFieldsByLang($element, LANGUAGE_ID);
            $element['DETAIL_PAGE_URL'] = $this->getElementUrl($element);
            if (!$element['MAP_COORDS_LAT'] || !$element['MAP_COORDS_LNG']) {
                continue;
            }
            $this->arResult['MAP_DATA']['htmlMarkers'][] = [
                'title' => $element['NAME'],
                'layout' => 'secondary',
                'coords' => [
                    $element['MAP_COORDS_LAT'],
                    $element['MAP_COORDS_LNG']
                ]
            ];
        }
    }
}
