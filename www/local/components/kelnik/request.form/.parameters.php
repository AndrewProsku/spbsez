<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    exit;
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !\Bitrix\Main\Loader::includeModule('kelnik.requests')
) {
    return false;
}

Loc::loadMessages(__FILE__);

try {

    $arComponentParameters = [
        'PARAMETERS' => [
            'SUB_TYPE' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_REQ_SUB_TYPE'),
                'TYPE' => 'LIST',
                'VALUES' => \Kelnik\Requests\Model\TypeTable::getFormTypes(),
                'DEFAULT' => \Kelnik\Requests\Model\TypeTable::SUB_TYPE_STANDARD
            ],
            'CACHE_TIME' => [
                'DEFAULT' => 3600
            ]
        ]
    ];

} catch (Exception $e) {
    ShowError($e->getMessage());
}
