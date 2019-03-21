<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();?>

<?php $APPLICATION->IncludeComponent(
    'kelnik:infrastructure.list',
    '.default',
    array(
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'ACTION' => $arParams['ACTION'],
        'SET_404' => $arParams['SET_404'],
        'SEF_FOLDER' => $arParams['SEF_FOLDER']
    ),
    $component
);?>
