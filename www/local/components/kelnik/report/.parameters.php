<?php

use Bitrix\Main\Localization\Loc;
use Bex\Bbc\Helpers\ComponentParameters;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !\Bitrix\Main\Loader::includeModule('kelnik.news')
) {
    return false;
}

Loc::loadMessages(__FILE__);

$currentZone = basename(dirname(__DIR__));

try {
    $currentParameters = [
        'GROUPS' => [
            'LIST' => [
                'NAME' => Loc::getMessage('KELNIK_NEWS_GROUP_LIST'),
                'SORT' => '200'
            ],
            'DETAIL' => [
                'NAME' => Loc::getMessage('KELNIK_NEWS_GROUP_DETAIL'),
                'SORT' => '300'
            ]
        ],
        'PARAMETERS' => [
            'SEF_MODE' => [
                'index' => [
                    'NAME' => Loc::getMessage('KELNIK_NEWS_SEF_INDEX'),
                    'DEFAULT' => '',
                    'VARIABLES' => []
                ],
                'section' => [
                    'NAME' => Loc::getMessage('KELNIK_NEWS_SEF_SECTION'),
                    'DEFAULT' => '#SECTION_CODE#/',
                    'VARIABLES' => ['SECTION_ID', 'SECTION_CODE', 'SECTION_CODE_PATH']
                ],
                'detail' => [
                    'NAME' => Loc::getMessage('KELNIK_NEWS_SEF_DETAIL'),
                    'DEFAULT' => '#SECTION_CODE#/#ELEMENT_CODE#/',
                    'VARIABLES' => ['ELEMENT_ID', 'ELEMENT_CODE', 'SECTION_ID', 'SECTION_CODE', 'SECTION_CODE_PATH']
                ]
            ]
        ]
    ];

    $paramsElementsList = ComponentParameters::getParameters(
        $currentZone . ':news.list',
        [
            'SECTION_ID' => [
            ],
            'SECTION_CODE' => [
                'DELETE' => true
            ],
            'SORT_BY_1' => [
                'MOVE' => 'LIST'
            ],
            'SORT_ORDER_1' => [
                'MOVE' => 'LIST'
            ],
            'SORT_BY_2' => [
                'MOVE' => 'LIST'
            ],
            'SORT_ORDER_2' => [
                'MOVE' => 'LIST'
            ],
            'DATE_FORMAT' => [
                'RENAME' => 'LIST_DATE_FORMAT',
                'MOVE' => 'LIST'
            ]
        ],
        $arCurrentValues
    );
    $paramsElementsDetail = ComponentParameters::getParameters(
        $currentZone . ':news.detail',
        [
            'ELEMENT_ID' => [
                'DELETE' => true
            ],
            'ELEMENT_CODE' => [
                'DELETE' => true
            ],
            'OG_TAGS_TITLE' => [
                'RENAME' => 'DETAIL_OG_TAGS_TITLE'
            ],
            'OG_TAGS_DESCRIPTION' => [
                'RENAME' => 'DETAIL_OG_TAGS_DESCRIPTION'
            ],
            'OG_TAGS_IMAGE' => [
                'RENAME' => 'DETAIL_OG_TAGS_IMAGE'
            ],
            'OG_TAGS_URL' => [
                'RENAME' => 'DETAIL_OG_TAGS_URL'
            ]
        ],
        $arCurrentValues
    );
    $arComponentParameters = array_replace_recursive($currentParameters, $paramsElementsList, $paramsElementsDetail);
} catch (\Exception $e) {
    ShowError($e->getMessage());
}
?>
