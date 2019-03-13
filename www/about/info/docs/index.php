<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Внутренние документы");
$APPLICATION->SetPageProperty('title', "Раскрытие информации | Внутренние документы | АООЭЗ");
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

        <div class="b-disclosure-title b-title">
            <h2><? $APPLICATION->ShowTitle(false); ?></h2>
        </div>

        <? $APPLICATION->IncludeComponent(
            "kelnik:infodocs.list",
            "docs",
            array(
                "COMPONENT_TEMPLATE" => "docs",
                "SECTION" => "1",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            ),
            false
        ); ?>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
