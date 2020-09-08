<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = [
    "NAME" => Loc::getMessage('KELNIK_TEXTBLOCKS_NAME'),
    "DESCRIPTION" => Loc::getMessage('KELNIK_TEXTBLOCKS_COMPLETE_DESC'),
    "PATH" => [
        "ID" => "Kelnik",
        "CHILD" => [
            "ID" => "components",
            'NAME' => Loc::getMessage('KELNIK_TEXTBLOCKS_COMPONENTS'),
        ]
    ],
];