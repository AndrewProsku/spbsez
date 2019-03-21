<?php

namespace Kelnik\Infrastructure\Component;

use Bex\Bbc;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Infrastructure\ElementTrait;
use Kelnik\Infrastructure\Model\MapTable;
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
            $this->arResult['ELEMENT']['MAP_DATA'] = array_merge_recursive(
                [
                    'center' => [
                        $this->arResult['ELEMENT']['MAP_COORDS_CENTER_LAT'],
                        $this->arResult['ELEMENT']['MAP_COORDS_CENTER_LNG']
                    ],
                    'scrollwheel' => false,
                    'fullScreenControl' => false,
                    'customZoomControl' => true,
                    'htmlMarkers' => [
                        [
                            'title' => $this->arResult['ELEMENT']['NAME'],
                            'layout' => 'secondary',
                            'coords' => [
                                $this->arResult['ELEMENT']['MAP_COORDS_LAT'],
                                $this->arResult['ELEMENT']['MAP_COORDS_LNG']
                            ]
                        ]
                    ],
                    'markers' => [],
                    'routes' => []
                ],
                self::getMapElements(
                    (int) $this->arResult['ELEMENT']['ID'],
                    $this->arResult['ELEMENT']['MAP_COORDS_LAT'],
                    $this->arResult['ELEMENT']['MAP_COORDS_LNG']
                )
            );
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

    public static function getMapElements(int $elementId, $elementLat, $elementLng)
    {
        $tmp = MapTable::getList([
            'filter' => [
                '=PLATFORM_ID' => $elementId,
                '=ACTIVE' => MapTable::YES
            ]
        ])->fetchAll();

        if (!$tmp) {
            return [];
        }

        $res = [];

        foreach ($tmp as $row) {
            if (!$row['MAP_COORDS_LAT'] || !$row['MAP_COORDS_LNG']) {
                continue;
            }

            $row = PlatformTable::replaceFieldsByLang($row, LANGUAGE_ID);

            if ($row['MAKE_ROUTE'] == MapTable::YES) {
                $res['routes'][] = [
                    'points' => [
                        [$elementLat, $elementLng],
                        [$row['MAP_COORDS_LAT'], $row['MAP_COORDS_LNG']]
                    ],
                    'finishMarker' => [
                        'title' => $row['NAME'],
                        'theme' => 'violet'
                    ],
                    'activeStrokeWidth' => 6,
                    'activeStrokeColor' => 'rgba(48,64,154,0.48)'
                ];
                continue;
            }

            $res['htmlMarkers'][] = [
                'title' => $row['NAME'],
                'layout' => 'text',
                'coords' => [
                    $row['MAP_COORDS_LAT'],
                    $row['MAP_COORDS_LNG']
                ]
            ];
        }

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
