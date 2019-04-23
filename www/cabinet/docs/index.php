<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Профиль");
$APPLICATION->SetPageProperty('title', 'Личный кабинет');
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
                "MENU_CACHE_USE_USERS" => "Y",
                "ROOT_MENU_TYPE" => "left",
                "USE_EXT" => "Y"
            )
        );?>

        <div class="l-profile-document j-profile-documents">

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
                    "MENU_CACHE_USE_USERS" => "Y",
                    "ROOT_MENU_TYPE" => "sub",
                    "USE_EXT" => "Y"
                )
            );?>

            <? $APPLICATION->IncludeComponent(
                "kelnik:profile",
                "docs",
                array(
                    "COMPONENT_TEMPLATE" => "docs",
                    "SECTION" => "docs",
                    "CACHE_TYPE" => "N",
                    "CACHE_TIME" => "3600"
                ),
                array()
            ); ?>

        </div>
    </div>

    <?php $APPLICATION->IncludeFile('inc_account_logout.php'); ?>
</div>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
