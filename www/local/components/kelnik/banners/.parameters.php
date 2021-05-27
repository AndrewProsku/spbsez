<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */
use \Bitrix\Main as Main;
use \Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);

if(!Main\Loader::includeModule("iblock")) return;

$arComponentParameters = array(
    "GROUPS" => array(),

    "PARAMETERS" => array(
        "CACHE_TYPE" => "N",
        "CACHE_TIME"  =>  array("DEFAULT"=>3600),
    ),
);

$arComponentParameters["PARAMETERS"]["IBLOCK_TYPE"] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('IBLOCK_TYPE'),
    'TYPE' => 'STRING'
];

$arComponentParameters["PARAMETERS"]["IBLOCK_ID"] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('IBLOCK_ID'),
    'TYPE' => 'STRING'
];

$arComponentParameters["PARAMETERS"]["IBLOCK_BANNER_POSITIONS"] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('IBLOCK_BANNER_POSITIONS'),
    'TYPE' => 'STRING'
];

$arComponentParameters["PARAMETERS"]["IBLOCK_BRANDING_POSITIONS"] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('IBLOCK_BRANDING_POSITIONS'),
    'TYPE' => 'STRING'
];

$arComponentParameters["PARAMETERS"]["POSITION"] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('POSITION'),
    'TYPE' => 'STRING'
];

$arComponentParameters["PARAMETERS"]["DEVICE"] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('DEVICE'),
    'TYPE' => 'STRING'
];

$arComponentParameters["PARAMETERS"]["TYPE"] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('TYPE'),
    'TYPE' => 'STRING'
];