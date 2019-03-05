<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !\Bitrix\Main\Loader::includeModule('kelnik.report')
) {
    return false;
}

Loc::loadMessages(__FILE__);

try {
    $arComponentParameters = [
        'GROUPS' => [
            'OTHERS' => [
                'NAME' => Loc::getMessage('KELNIK_REPORT_DETAIL_GROUP_OTHERS')
            ],
            'SEO' => [
                'NAME' => Loc::getMessage('KELNIK_REPORT_DETAIL_GROUP_SEO')
            ],
        ],
        'PARAMETERS' => [
            'ELEMENT_ID' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_REPORT_DETAIL_ELEMENT_ID'),
                'TYPE' => 'string'
            ],
            'SET_404' => [
                'PARENT' => 'OTHERS',
                'NAME' => Loc::getMessage('KELNIK_REPORT_DETAIL_SET_404'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ],
            'CACHE_TIME' => [
                'DEFAULT' => 3600
            ]
        ]
    ];
} catch (Exception $e) {
    ShowError($e->getMessage());
}
