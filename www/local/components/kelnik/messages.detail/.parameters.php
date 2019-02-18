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
                Loc::getMessage('KELNIK_MESSAGES_DETAIL_DATE_FORMAT'),
                'OTHERS'
            ),
            'SET_404' => [
                'PARENT' => 'DETAIL',
                'NAME' => Loc::getMessage('KELNIK_MESSAGES_DETAIL_SET_404'),
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
