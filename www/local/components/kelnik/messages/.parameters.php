<?php
use Bex\Bbc\Helpers\ComponentParameters;
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    exit;
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

$currentZone = basename(dirname(__DIR__));

try {
    $arComponentParameters = [
        'GROUPS' => [
            'LIST' => [
                'NAME' => Loc::getMessage('KELNIK_MESSAGES_GROUP_LIST'),
                'SORT' => '200'
            ],
            'DETAIL' => [
                'NAME' => Loc::getMessage('KELNIK_MESSAGES_GROUP_DETAIL'),
                'SORT' => '300'
            ],
            'OTHERS' => [
                'NAME' => Loc::getMessage('KELNIK_MESSAGES_GROUP_OTHER'),
                'SORT' => '400'
            ]
        ],
        'PARAMETERS' => [
            'SEF_MODE' => [
                'list' => [
                    'NAME' => Loc::getMessage('KELNIK_MESSAGES_SEF_LIST'),
                    'DEFAULT' => '',
                    'VARIABLES' => []
                ],
                'year' => [
                    'NAME' => Loc::getMessage('KELNIK_MESSAGES_SEF_YEAR'),
                    'DEFAULT' => '#YEAR#/',
                    'VARIABLES' => ['YEAR']
                ],
                'search' => [
                    'NAME' => Loc::getMessage('KELNIK_MESSAGES_SEF_SEARCH'),
                    'DEFAULT' => 'search/',
                    'VARIABLES' => []
                ],
                'detail' => [
                    'NAME' => Loc::getMessage('KELNIK_MESSAGES_SEF_DETAIL'),
                    'DEFAULT' => '#ELEMENT_TYPE#-#ELEMENT_ID#/',
                    'VARIABLES' => ['ELEMENT_TYPE', 'ELEMENT_ID']
                ]
            ]
        ]
    ];

    $paramsElementsList = ComponentParameters::getParameters(
        $currentZone . ':messages.list',
        [
            'DATE_FORMAT' => [
                'RENAME' => 'LIST_DATE_FORMAT',
                'MOVE' => 'LIST'
            ]
        ],
        $arCurrentValues
    );

    $paramsElement = ComponentParameters::getParameters(
        $currentZone . ':messages.detail',
        [
            'DATE_FORMAT' => [
                'RENAME' => 'DETAIL_DATE_FORMAT',
                'MOVE' => 'DETAIL'
            ]
        ],
        $arCurrentValues
    );

    $arComponentParameters = array_replace_recursive($arComponentParameters, $paramsElementsList, $paramsElement);

} catch (Exception $e) {
    ShowError($e->getMessage());
}
