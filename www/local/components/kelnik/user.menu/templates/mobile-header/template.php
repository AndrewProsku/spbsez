<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="b-mobile-menu__header">
    <div class="b-mobile-menu__header-left">
        <a href="/" class="b-mobile-menu__header-logo">
            <img src="/images/home/logo-menu.svg" alt="Особая экономическая зона" title="На главную страницу">
        </a>
    </div>
    <div class="b-mobile-menu__header-right">
        <div class="b-account">
            <a href="/cabinet/" class="b-account__link<?php if($arResult['IS_AUTHORIZED']): ?> is-auth<?php endif; ?>">
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
        <div class="b-language">
            <a href="#" class="b-language__link">Ru</a>
        </div>
        <div class="b-close-menu-warp j-close-menu">
            <div class="b-close-menu">
                <div class="b-close-menu__line"></div>
                <div class="b-close-menu__line"></div>
            </div>
        </div>
    </div>
</div>
