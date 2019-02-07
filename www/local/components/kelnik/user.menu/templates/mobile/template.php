<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="b-account j-account-auth">
    <a href="/cabinet/<?php if($arResult['MESSAGES']): ?>messages/<?php endif; ?>" class="b-account__link<?php if($arResult['IS_AUTHORIZED']): ?>  is-auth j-account-auth<?php endif; ?>">
        <span class="b-account__link-icon">
           <?php if($arResult['MESSAGES']): ?> <span class="b-account__messages"><?= $arResult['MESSAGES']; ?></span><?php endif; ?>
        </span>
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
            "USE_EXT" => "Y"
        )
    );?>
</div>
