<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?php $APPLICATION->IncludeComponent(
    'kelnik:messages.list',
    $arParams['COMPONENT_TEMPLATE'],
    array(
        'DATE_FORMAT' => $arParams['LIST_DATE_FORMAT'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'SEF_FOLDER' => $arParams['SEF_FOLDER'],
        'IS_SEARCH' => $arResult['VARIABLES']['IS_SEARCH']
        ),
    $components
); ?>
