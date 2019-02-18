<?php
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    exit;
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !\Bitrix\Main\Loader::includeModule('iblock')
) {
    return false;
}

Loc::loadMessages(__FILE__);

try {
    $arComponentParameters = [
        'PARAMETERS' => [
            'DATE_FORMAT' => CIBlockParameters::GetDateFormat(
                Loc::getMessage('KELNIK_MESSAGES_LIST_DATE_FORMAT'),
                'OTHERS'
            ),
            'USE_SEARCH' => [
                'PARENT' => 'LIST',
                'NAME' => Loc::getMessage('KELNIK_MESSAGES_USE_SEARCH'),
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
