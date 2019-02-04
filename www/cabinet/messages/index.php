<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle('Сообщения от ОЭЗ');
$APPLICATION->SetPageProperty('title', 'Сообщения от ОЭЗ | АООЭЗ');
if (!$USER->IsAuthorized()) {
    LocalRedirect('/cabinet/auth/');
}
?>

<div class="l-profile-document__wrap-flex">
    <div class="l-profile-document__wrap-top">
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
                "USE_EXT" => "Y"
            )
        );?>
    </div>

    <div class="l-profile-logout">
        <button class="button-logout j-logout" type="button">Выйти из личного кабинета</button>
    </div>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
