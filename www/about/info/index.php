<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Раскрытие информации | ОЭЗ СПб");
LocalRedirect('/about/info/docs/');
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


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
