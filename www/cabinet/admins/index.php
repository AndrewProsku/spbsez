<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Профиль");
$APPLICATION->SetPageProperty('title', 'Личный кабинет | АООЭЗ');
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

<div class="l-profile-administrators">

    <div class="b-title b-add-request-title">
        <h1><?= $APPLICATION->ShowTitle(false); ?></h1>
    </div>

    <?$APPLICATION->IncludeComponent(
        "bitrix:menu",
        "submenu-cabinet-profile",
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
        "kelnik:profile",
        "admins",
        array(
            "COMPONENT_TEMPLATE" => "admins",
            "SECTION" => "admins",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "360000"
        ),
        array()
    ); ?>

</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
