<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Закупочная информация");
$APPLICATION->SetPageProperty('title', "Раскрытие информации | Закупочная информация | ОЭЗ СПб");
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
            "docs-proc",
            array(
                "COMPONENT_TEMPLATE" => "docs-proc",
                "SECTION" => "6",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "SHOW_FILTER" => "Y",
                "USE_AJAX" => "N",
                "AJAX_TYPE" => "JSON",
                "AJAX_TEMPLATE_PAGE" => "",
                "AJAX_COMPONENT_ID" => ""
            ),
            false
        ); ?>

        <? $APPLICATION->IncludeComponent(
            "kelnik:infoproc.list",
            "procurements",
            array(
                "COMPONENT_TEMPLATE" => "procurements",
                "YEAR" => "",
                "SHOW_FILTER" => "Y",
                "ELEMENTS_COUNT" => "10",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "USE_AJAX" => "N",
                "AJAX_TYPE" => "DEFAULT",
                "AJAX_TEMPLATE_PAGE" => "",
                "AJAX_COMPONENT_ID" => "info-proc"
            ),
            false
        ); ?>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
