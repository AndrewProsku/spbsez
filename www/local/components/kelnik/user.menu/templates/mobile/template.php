<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="b-account b-account_is_hover">
    <a href="/cabinet/" class="b-account__link<?php if($arResult['IS_AUTHORIZED']): ?>  is-auth j-account-auth<?php endif; ?>">
        <?php if($arResult['IS_AUTHORIZED']): ?>
            <span class="b-account__link-icon">
                <span class="b-account__messages">2</span>
            </span>
        <?php endif; ?>
        <span class="b-account__link-text">Личный кабинет</span>
    </a>
    <?$APPLICATION->IncludeComponent(
        "bitrix:menu",
        "user",
        Array(
            "ALLOW_MULTI_SELECT" => "N",
            "DELAY" => "N",
            "MAX_LEVEL" => "1",
            "MENU_CACHE_GET_VARS" => array(""),
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_TYPE" => "A",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "ROOT_MENU_TYPE" => "user",
            "USE_EXT" => "N"
        )
    );?>
</div>
