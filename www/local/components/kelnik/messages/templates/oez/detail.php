<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?php $APPLICATION->IncludeComponent(
    'kelnik:messages.detail',
    $arParams['COMPONENT_TEMPLATE'],
    array(
        'DATE_FORMAT' => $arParams['DETAIL_DATE_FORMAT'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'SEF_FOLDER' => $arParams['SEF_FOLDER'],
        'ELEMENT_TYPE' => $arResult['VARIABLES']['ELEMENT_TYPE'],
        'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID']
    ),
    $components
); ?>
