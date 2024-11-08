<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "Медиа");
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

<div class="l-news">
    <div class="l-news__title b-title"><h1><? $APPLICATION->ShowTitle(false); ?></h1></div>
    <div class="l-news__main">
        <? $APPLICATION->IncludeComponent(
            "kelnik:news.list",
            "media-news",
            array(
                "COMPONENT_TEMPLATE" => "media-news",
                "SECTION_ID" => "1",
                "SECTION_CODE" => "",
                "SORT_BY_1" => "DATE_SHOW",
                "SORT_ORDER_1" => "DESC",
                "SORT_BY_2" => "ID",
                "SORT_ORDER_2" => "ASC",
                "ELEMENTS_COUNT" => "6",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "USE_AJAX" => "N",
                "AJAX_TYPE" => "DEFAULT",
                "AJAX_TEMPLATE_PAGE" => "",
                "AJAX_COMPONENT_ID" => "",
                "SET_SEO_TAGS" => "N",
                "SET_404" => "N",
                "SEF_URL_TEMPLATES" => array(
                    "detail" => "#ELEMENT_CODE#/"
                ),
                "SEF_FOLDER" => "/media/news/",
                "USE_ADVANCE_FILTER" => "N"
            ),
            array()
        ); ?>
    </div>

    <div class="l-news__useful">
        <? $APPLICATION->IncludeComponent(
            "kelnik:news.list",
            "media-articles",
            array(
                "COMPONENT_TEMPLATE" => "media-articles",
                "SECTION_ID" => "2",
                "SECTION_CODE" => "",
                "SORT_BY_1" => "DATE_SHOW",
                "SORT_ORDER_1" => "DESC",
                "SORT_BY_2" => "ID",
                "SORT_ORDER_2" => "ASC",
                "ELEMENTS_COUNT" => "3",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "USE_AJAX" => "N",
                "AJAX_TYPE" => "DEFAULT",
                "AJAX_TEMPLATE_PAGE" => "",
                "AJAX_COMPONENT_ID" => "",
                "SET_SEO_TAGS" => "N",
                "SET_404" => "N",
                "SEF_URL_TEMPLATES" => array(
                    "detail" => "#ELEMENT_CODE#/"
                ),
                "SEF_FOLDER" => "/media/articles/",
                "USE_ADVANCE_FILTER" => "N"
            ),
            array()
        ); ?>
    </div>

    <div class="l-news__main">
        <h2 class="b-useful__title b-title">Мероприятия</h2>
        <? $APPLICATION->IncludeComponent(
            "kelnik:news.list",
            "media-news",
            array(
                "COMPONENT_TEMPLATE" => "media-news",
                "SECTION_ID" => "5",
                "SECTION_CODE" => "",
                "SORT_BY_1" => "DATE_SHOW",
                "SORT_ORDER_1" => "DESC",
                "SORT_BY_2" => "ID",
                "SORT_ORDER_2" => "ASC",
                "ELEMENTS_COUNT" => "6",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "USE_AJAX" => "N",
                "AJAX_TYPE" => "DEFAULT",
                "AJAX_TEMPLATE_PAGE" => "",
                "AJAX_COMPONENT_ID" => "",
                "SET_SEO_TAGS" => "N",
                "SET_404" => "N",
                "SEF_URL_TEMPLATES" => array(
                    "detail" => "#ELEMENT_CODE#/"
                ),
                "SEF_FOLDER" => "/media/events/",
                "USE_ADVANCE_FILTER" => "N",
                "SHOW_MORE_TEXT" => "Посмотреть все мероприятия"
            ),
            array()
        ); ?>
    </div>

    <div class="l-news-presentations">
        <div class="b-presentations">
            <? $APPLICATION->IncludeComponent(
                "kelnik:refbook.list",
                "presentation",
                array(
                    "COMPONENT_TEMPLATE" => "presentation",
                    "SECTION" => "6",
                    "CACHE_GROUPS" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600"
                ),
                array()
            ); ?>
            <? $APPLICATION->IncludeComponent(
                'kelnik:site.info',
                'press-contact',
                array(
                    "COMPONENT_TEMPLATE" => "press-contact",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "360000"
                ),
                array()
            ); ?>
        </div>
    </div>

</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
