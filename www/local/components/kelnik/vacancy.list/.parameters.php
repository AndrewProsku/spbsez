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
            'SECTION' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_REFBOOK_SECTION'),
                'TYPE' => 'LIST',
                'VALUES' => [
                    \Kelnik\Refbook\Types::TYPE_PARTNER => Loc::getMessage('KELNIK_REFBOOK_TYPE_PARTNER'),
                    \Kelnik\Refbook\Types::TYPE_RESIDENT => Loc::getMessage('KELNIK_REFBOOK_TYPE_RESIDENT'),
                    \Kelnik\Refbook\Types::TYPE_REVIEW => Loc::getMessage('KELNIK_REFBOOK_TYPE_REVIEW')
                ]
            ],
            'CACHE_GROUPS' => [
                'PARENT' => 'CACHE_SETTINGS',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_CACHE_GROUPS'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ],
            'CACHE_TIME' => [
                'DEFAULT' => 360000
            ]
        ]
    ];
}
catch (Exception $e)
{
    ShowError($e->getMessage());
}
