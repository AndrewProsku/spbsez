<?php

namespace Kelnik\Infrastructure\Component;

use Bex\Bbc;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Infrastructure\ElementTrait;
use Kelnik\Infrastructure\Model\MapTable;
use Kelnik\Infrastructure\Model\PlanTable;
use Kelnik\Infrastructure\Model\PlatformTable;
use Kelnik\Refbook\Model\ResidentTable;

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
        $this->setResultCacheKeys(['ELEMENT', 'ELEMENTS', 'SEO_TAGS']);

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
                    'AREA_BG_ID' => 'full'
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

            $this->arResult['ELEMENT']['PLAN'] = self::getPlan((int) $this->arResult['ELEMENT']['ID']);
        } catch (\Exception $e) {
            $this->abortCache();

            return false;
        }

        self::registerCacheTag('kelnik:infrastructureRow_' . $this->arResult['ELEMENT']['ID']);

        $this->arResult['SEO_TAGS']['TITLE'] = implode(
            ' | ',
            [
                Loc::getMessage('KELNIK_INFRA_COMP_FOLDER'),
                Loc::getMessage('KELNIK_INFRA_COMP_PLATFORM') . ' ' . $this->arResult['ELEMENT']['NAME']
            ]
        );

        $this->setSeoTags();
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

    public static function getPlan(int $platformId)
    {
        $res = [];

        if (!$platformId) {
            return $res;
        }

        $stubs = [
            'ru' => '/images/residents/stub_ru.png',
            'en' => '/images/residents/stub_en.png'
        ];

        try {
            $res = PlanTable::getAssoc([
                'select' => [
                    'ID', 'RESIDENT_ID',
                    'HEAT', 'ELECTRICITY', 'WATER', 'STORM_SEWER', 'IS_BUSY', 'AREA',
                    'PRICE' => 'PRICE_RU', 'PRICE_EN',
                    'RENT' => 'RENT_RU', 'RENT_EN', 'COORDS'
                ],
                'filter' => [
                    '=ACTIVE' => PlanTable::YES,
                    '=PLATFORM_ID' => $platformId,
                    '!COORDS' => false
                ]
            ], 'ID');

            $residents = ResidentTable::getAssoc([
                'select' => [
                    'ID', 'NAME' => 'NAME', 'NAME_EN',
                    'SITE', 'RTYPE' => 'TYPE.NAME',
                    'RTYPE_EN' => 'TYPE.NAME_EN',
                    'IMAGE_RU' => 'IMAGE_ID', 'IMAGE_EN' => 'IMAGE_ID_EN'
                ],
                'filter' => [
                    '=ACTIVE' => ResidentTable::YES,
                    '=ID' => array_column($res, 'RESIDENT_ID')
                ]
            ], 'ID');
        } catch (\Exception $e) {
            $res = [];
        }

        $residents = BitrixHelper::prepareFileFields($residents, ['IMAGE_*' => 'path']);
        $residents = array_map(function ($el) {
            return PlanTable::replaceFieldsByLang($el, LANGUAGE_ID);
        }, $residents);

        foreach ($res as &$v) {
            $v = PlanTable::replaceFieldsByLang($v, LANGUAGE_ID);
            $json = [
                'PRICE' => $v['PRICE'],
                'RENT' => $v['RENT'],
                'AREA' => $v['AREA'] ? $v['AREA'] . ' ' . Loc::getMessage('KELNIK_INFRA_COMP_AREA_SUFFIX') : '',
                'AREA_TITLE' => Loc::getMessage('KELNIK_INFRA_COMP_AREA_TITLE'),
                'TITLE' => Loc::getMessage('KELNIK_INFRA_COMP_TITLE'),
                'PRICE_TITLE' => Loc::getMessage('KELNIK_INFRA_COMP_PRICE_TITLE'),
                'RENT_TITLE' => Loc::getMessage('KELNIK_INFRA_COMP_RENT_TITLE'),
                'FEATURES' => []
            ];

            foreach (PlanTable::getFeatures() as $featureKey => $feature) {
                if (!isset($v[$featureKey]) || $v[$featureKey] != PlanTable::YES) {
                    continue;
                }

                $json['FEATURES'][] = $feature;
            }

            if ($v['RESIDENT'] = ArrayHelper::getValue($residents, $v['RESIDENT_ID'], [])) {
                $json = [
                    'NAME' => strip_tags(html_entity_decode($v['RESIDENT']['NAME'], ENT_QUOTES, SITE_CHARSET)),
                    'AREA' => $v['AREA'] ? $v['AREA'] . ' ' . Loc::getMessage('KELNIK_INFRA_COMP_AREA_SUFFIX') : '',
                    'AREA_TITLE' => Loc::getMessage('KELNIK_INFRA_COMP_AREA_TITLE'),
                    'TYPE' => $v['RESIDENT']['RTYPE'],
                    'IMAGE' => ArrayHelper::getValue(
                                    $v,
                                    'RESIDENT.IMAGE_' . strtoupper(LANGUAGE_ID) . '_PATH',
                                    ArrayHelper::getValue($v, 'RESIDENT.IMAGE_RU_PATH')
                                ),
                    'SITE' => $v['RESIDENT']['SITE']
                                ? ['URL' => 'http://' . $v['RESIDENT']['SITE'], 'TITLE' => $v['RESIDENT']['SITE']]
                                : false
                ];

                if (!$json['IMAGE']) {
                    $json['IMAGE'] = $stubs[LANGUAGE_ID];
                }
            }

            $v['JSON'] = base64_encode(json_encode($json));
        }

        usort($res, function ($a, $b) {
            $aIsBusy = !empty($a['RESIDENT_ID']) || $a['IS_BUSY'] == 'Y';
            $bIsBusy = !empty($b['RESIDENT_ID']) || $b['IS_BUSY'] == 'Y';

            if ($aIsBusy && $bIsBusy) {
                return 0;
            }

            return $aIsBusy ? 1 : -1;
        });

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
