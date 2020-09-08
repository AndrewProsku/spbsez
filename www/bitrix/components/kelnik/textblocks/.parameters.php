<?php
if (!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Kelnik\Text\Blocks\BlocksTable;

foreach (array('bex.bbc', 'kelnik.text') as $moduleName) {
    if (!\Bitrix\Main\Loader::includeModule($moduleName)) {
        return false;
    }
}

Loc::loadMessages(__FILE__);

try
{
    $arComponentParameters = [
        'PARAMETERS' => [
            'CODE' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_TEXTBLOCKS_OBJECT'),
                'TYPE' => 'STRING',
                'DEFAULT' => BlocksTable::getNewCode()
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
