<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "News | SEZ");
$APPLICATION->SetTitle("News");
?>

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
        "SECTION_ID" => "3",
        "SEF_FOLDER" => "/en/media/news/",
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
