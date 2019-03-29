<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();?>

<?php $APPLICATION->IncludeComponent(
    'kelnik:infrastructure.detail',
    '.default',
    array(
        'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
        'ELEMENT_CODE' => $arResult['VARIABLES']['ELEMENT_CODE'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'SET_404' => $arParams['SET_404'],
        'SEF_FOLDER' => $arParams['SEF_FOLDER'],
        'SET_SEO_TAGS' => 'Y'
    ),
    $component
);?>
