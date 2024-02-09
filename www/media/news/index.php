<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "Новости");
$APPLICATION->SetTitle("Новости");
?>

<?/*$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "submenu-about",
    Array(
        "ALLOW_MULTI_SELECT" => "N",
        "DELAY" => "N",
        "MAX_LEVEL" => "1",
        "MENU_CACHE_GET_VARS" => array(""),
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_TYPE" => "A",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "ROOT_MENU_TYPE" => "left",
        "USE_EXT" => "N"
    )
);*/?>

<?$APPLICATION->IncludeComponent(
    "kelnik:news",
    "media",
    Array(
        "AJAX_COMPONENT_ID" => "news-list",
        "AJAX_TEMPLATE_PAGE" => "",
        "AJAX_TYPE" => "DEFAULT",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "ELEMENTS_COUNT" => "6",
        "SECTION_ID" => "1",
        "SEF_FOLDER" => "/media/news/",
        "SEF_MODE" => "Y",
        "SEF_URL_TEMPLATES" => Array("detail"=>"#ELEMENT_CODE#/","index"=>"","section"=>""),
        "SET_404" => "Y",
        "SET_SEO_TAGS" => "Y",
        "SORT_BY_1" => "DATE_SHOW",
        "SORT_ORDER_1" => "DESC",
        "SORT_BY_2" => "ID",
        "SORT_ORDER_2" => "ASC",
        "USE_AJAX" => "N",
        "USE_ADVANCE_FILTER" => "Y"
    )
);?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
