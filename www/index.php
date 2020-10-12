<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Главная");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Главная");
?>
    <div class="l-home__main-screen j-home__main-screen">
        <? $APPLICATION->IncludeComponent(
            'kelnik:site.info',
            'main-video',
            array(
                "COMPONENT_TEMPLATE" => "main-video",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "360000"
            ),
            array()

        ); ?>
        <? $APPLICATION->IncludeComponent(
            'kelnik:site.info',
            'social',
            array(
                "COMPONENT_TEMPLATE" => "social",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "360000"
            ),
            array()

        ); ?>
        <div class="b-main-screen-content">
            <div class="b-main-screen-text">
                <?$APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "main_screen_1"
                    )
                );?>
            </div>
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "main_screen_2"
                )
            );?>
        </div>
    </div>

    

    <div class="l-home__block_bg_fixed">
        <div class="l-home__advantages l-home__block">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "main_screen_5"
                )
            );?>
        </div>
    
        <div class="l-home__privileges l-home__block">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "main_screen_3"
                )
            );?>
            <?/*$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "banner_privileges"
                )
            );*/?>
        </div>

        <? $APPLICATION->IncludeComponent(
            "kelnik:news.list",
            "main",
            array(
                "COMPONENT_TEMPLATE" => "main",
                "SECTION_ID" => "1",
                "SECTION_CODE" => "",
                "SORT_BY_1" => "DATE_SHOW",
                "SORT_ORDER_1" => "DESC",
                "SORT_BY_2" => "ID",
                "SORT_ORDER_2" => "ASC",
                "ELEMENTS_COUNT" => "4",
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
        
        <div class="l-home-resident l-home__block">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "main_screen_4"
                )
            );?>
        </div>
        <? $APPLICATION->IncludeComponent(
            "kelnik:refbook.list",
            "residents",
            array(
                "COMPONENT_TEMPLATE" => "residents",
                "SECTION" => "2",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            ),
            array()
        ); ?>
        <? $APPLICATION->IncludeComponent(
            "kelnik:refbook.list",
            "reviews",
            array(
                "COMPONENT_TEMPLATE" => "reviews",
                "SECTION" => "3",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            ),
            array()
        ); ?>

        <? $APPLICATION->IncludeFile('inc_zone_map.php'); ?>

        <? $APPLICATION->IncludeComponent(
            "kelnik:refbook.list",
            "partners",
            array(
                "COMPONENT_TEMPLATE" => "partners",
                "SECTION" => "1",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            ),
            array()
        ); ?>
    </div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
