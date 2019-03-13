<?php
if (!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true) {
    die();
}

use Bitrix\Main\Localization\Loc;

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !\Bitrix\Main\Loader::includeModule('kelnik.info')
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
                'NAME' => Loc::getMessage('KELNIK_INFO_SECTION'),
                'TYPE' => 'LIST',
                'VALUES' => \Kelnik\Info\Model\TypesTable::getAdminAssocList()
            ],
            'CACHE_GROUPS' => [
                'PARENT' => 'CACHE_SETTINGS',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_CACHE_GROUPS'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ],
            'CACHE_TIME' => [
                'DEFAULT' => 3600
            ]
        ]
    ];
}
catch (Exception $e)
{
    ShowError($e->getMessage());
}
