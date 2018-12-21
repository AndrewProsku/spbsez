<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle('Подать заявку');
$APPLICATION->SetPageProperty('title', 'Подать заявку | РОСОЭЗ');
if (!$USER->IsAuthorized()) {
    LocalRedirect('/cabinet/auth/');
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
        "ROOT_MENU_TYPE" => "left",
        "USE_EXT" => "N"
    )
);?>

<div class="l-add-request">
    <div class="b-title b-add-request-title">
        <h1><? $APPLICATION->ShowTitle(false); ?></h1>
    </div>

    <?$APPLICATION->IncludeComponent(
        "bitrix:menu",
        "submenu-cabinet-request",
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
        "kelnik:request.form",
        ".default",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "360000"
        ),
        array()
    ); ?>

</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
