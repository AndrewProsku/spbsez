<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = [
    'NAME' => '',
    'DESCRIPTION' => '',
    'PATH' => [
        'ID' => 'Kelnik',
        'CHILD' => [
            'ID' => 'components',
            'NAME' => Loc::getMessage('KELNIK_COMPONENTS'),
        ]
    ],
];