<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle('Подать заявку');
$APPLICATION->SetPageProperty('title', 'Подать заявку | ОЭЗ СПб');
if (!$USER->IsAuthorized()) {
    LocalRedirect(LANG_DIR . 'cabinet/auth/');
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
                    "USE_EXT" => "Y"
                )
            );?>

            <? $APPLICATION->IncludeComponent(
                "kelnik:request.form",
                "permit",
                array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "CACHE_TYPE" => "N",
                    "CACHE_TIME" => "3600",
                    "SUB_TYPE" => "permit"
                ),
                array()
            ); ?>

        </div>
    </div>
    <?php $APPLICATION->IncludeFile('inc_account_logout.php'); ?>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
