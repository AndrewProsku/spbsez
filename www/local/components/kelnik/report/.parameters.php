<?php

use Bitrix\Main\Localization\Loc;
use Bex\Bbc\Helpers\ComponentParameters;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !\Bitrix\Main\Loader::includeModule('kelnik.report')
) {
    return false;
}

Loc::loadMessages(__FILE__);

$currentZone = basename(dirname(__DIR__));

try {
    $currentParameters = [
        'GROUPS' => [
            'DETAIL' => [
                'NAME' => Loc::getMessage('KELNIK_REPORT_GROUP_DETAIL'),
                'SORT' => '300'
            ]
        ],
        'PARAMETERS' => [
            'SEF_MODE' => [
                'index' => [
                    'NAME' => Loc::getMessage('KELNIK_REPORT_SEF_INDEX'),
                    'DEFAULT' => '',
                    'VARIABLES' => []
                ],
                'detail' => [
                    'NAME' => Loc::getMessage('KELNIK_REPORT_SEF_DETAIL'),
                    'DEFAULT' => '#ELEMENT_ID#/',
                    'VARIABLES' => ['ELEMENT_ID']
                ]
            ]
        ]
    ];

    $paramsElementsDetail = ComponentParameters::getParameters(
        $currentZone . ':report.detail',
        [
            'ELEMENT_ID' => [
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
    $arComponentParameters = array_replace_recursive($currentParameters, $paramsElementsDetail);
} catch (\Exception $e) {
    ShowError($e->getMessage());
}
?>
