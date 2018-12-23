<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    exit;
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

try {

    $arComponentParameters = [
        'PARAMETERS' => [
            'CACHE_TIME' => [
                'DEFAULT' => 360000
            ]
        ]
    ];

} catch (Exception $e) {
    ShowError($e->getMessage());
}
