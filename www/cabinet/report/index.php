<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle('Подача отчета');
$APPLICATION->SetPageProperty('title', 'Подача отчета');
if (!$USER->IsAuthorized()) {
    LocalRedirect(LANG_DIR . 'cabinet/auth/');
}
?>

    <?$APPLICATION->IncludeComponent(
        "bitrix:menu",
        "submenu-cabinet",
        Array(
            "ALLOW_MULTI_SELECT" => "N",
            "DELAY" => "N",
            "MAX_LEVEL" => "1",
            "MENU_CACHE_GET_VARS" => array(""),
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_TYPE" => "A",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_USE_USERS" => "Y",
            "ROOT_MENU_TYPE" => "left",
            "USE_EXT" => "Y"
        )
    );?>

    <? $APPLICATION->IncludeComponent(
        "kelnik:report",
        ".default",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "SEF_MODE" => "Y",
            "SEF_FOLDER" => LANG_DIR . "cabinet/report/",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "SET_404" => "Y",
            "SEF_URL_TEMPLATES" => array(
                "index" => "",
                "detail" => "#ELEMENT_ID#/",
            )
        ),
        false
    ); ?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
