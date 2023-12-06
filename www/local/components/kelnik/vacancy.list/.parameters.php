<?php
if (!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true) {
    die();
}

use Bitrix\Main\Localization\Loc;

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !\Bitrix\Main\Loader::includeModule('kelnik.refbook')
) {
    return false;
}

Loc::loadMessages(__FILE__);

try
{
    $arComponentParameters = [
        'PARAMETERS' => [
            'CACHE_TIME' => [
                'DEFAULT' => 3600
            ],
            'RESIDENTS' => [
                'DEFAULT' => 'N'
            ]
        ]
    ];
}
catch (Exception $e)
{
    ShowError($e->getMessage());
}
