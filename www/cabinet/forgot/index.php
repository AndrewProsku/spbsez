<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle('Восстановление пароля');
$APPLICATION->SetPageProperty('title', 'Восстановление пароля | АООЭЗ');
if ($USER->IsAuthorized()) {
    LocalRedirect('/cabinet/');
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

<div class="l-password-recovery">

    <div class="b-title b-password-recovery-title">
        <h1><? $APPLICATION->ShowTitle(false); ?></h1>
    </div>

    <? $APPLICATION->IncludeComponent(
        "kelnik:forgot.form",
        ".default",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "360000"
        ),
        array()
    ); ?>

    <div class="password-recovery-block j-password-recovery-button password-recovery-block_is_hidden">
        <a href="/cabinet/auth/" class="button password-recovery__button">
            Войти в личный кабинет
        </a>
    </div>

</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
