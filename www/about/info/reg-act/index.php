<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Раскрытие информации в сфере регулируемых видов деятельности");
$APPLICATION->SetPageProperty('title', "Раскрытие информации | Раскрытие информации в сфере регулируемых видов деятельности");
?>
    <?$APPLICATION->IncludeComponent(
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
    );?>

    <div class="l-disclosure">
        <div class="b-title">
            <h1><?= $APPLICATION->GetDirProperty('title'); ?></h1>
        </div>

        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "submenu-info",
            Array(
                "ALLOW_MULTI_SELECT" => "N",
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => array(""),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "A",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "sub",
                "USE_EXT" => "N"
            )
        );?>

        <? $APPLICATION->IncludeComponent(
            "kelnik:infodocs.list",
            "docs-reg",
            array(
                "COMPONENT_TEMPLATE" => "docs-reg",
                "SECTION" => "2",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "SHOW_FILTER" => "Y",
                "USE_AJAX" => "Y",
                "AJAX_TYPE" => "JSON",
                "AJAX_TEMPLATE_PAGE" => "",
                "AJAX_COMPONENT_ID" => ""
            ),
            false
        ); ?>
        <? $APPLICATION->IncludeComponent(
            "kelnik:infodocs.list",
            "docs-reg",
            array(
                "COMPONENT_TEMPLATE" => "docs-reg",
                "SECTION" => "3",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "SHOW_FILTER" => "Y",
                "USE_AJAX" => "Y",
                "AJAX_TYPE" => "JSON",
                "AJAX_TEMPLATE_PAGE" => "",
                "AJAX_COMPONENT_ID" => ""
            ),
            false
        ); ?>
        <? $APPLICATION->IncludeComponent(
            "kelnik:infodocs.list",
            "docs-reg",
            array(
                "COMPONENT_TEMPLATE" => "docs-reg",
                "SECTION" => "4",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "SHOW_FILTER" => "Y",
                "USE_AJAX" => "Y",
                "AJAX_TYPE" => "JSON",
                "AJAX_TEMPLATE_PAGE" => "",
                "AJAX_COMPONENT_ID" => ""
            ),
            false
        ); ?>
        <? $APPLICATION->IncludeComponent(
            "kelnik:infodocs.list",
            "docs-reg",
            array(
                "COMPONENT_TEMPLATE" => "docs-reg",
                "SECTION" => "5",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "SHOW_FILTER" => "Y",
                "USE_AJAX" => "Y",
                "AJAX_TYPE" => "JSON",
                "AJAX_TEMPLATE_PAGE" => "",
                "AJAX_COMPONENT_ID" => ""
            ),
            false
        ); ?>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
