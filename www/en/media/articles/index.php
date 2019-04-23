<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "Articles");
$APPLICATION->SetTitle("Articles");
?>

<?$APPLICATION->IncludeComponent(
    "kelnik:news",
    "media-articles",
    Array(
        "AJAX_COMPONENT_ID" => "articles-list",
        "AJAX_TEMPLATE_PAGE" => "",
        "AJAX_TYPE" => "DEFAULT",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "ELEMENTS_COUNT" => "6",
        "SECTION_ID" => "4",
        "SEF_FOLDER" => "/en/media/articles/",
        "SEF_MODE" => "Y",
        "SEF_URL_TEMPLATES" => Array("detail"=>"#ELEMENT_CODE#/","index"=>"","section"=>""),
        "SET_404" => "Y",
        "SET_SEO_TAGS" => "Y",
        "SORT_BY_1" => "DATE_SHOW",
        "SORT_ORDER_1" => "DESC",
        "SORT_BY_2" => "ID",
        "SORT_ORDER_2" => "ASC",
        "USE_AJAX" => "N",
        "USE_ADVANCE_FILTER" => "N"
    )
);?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
