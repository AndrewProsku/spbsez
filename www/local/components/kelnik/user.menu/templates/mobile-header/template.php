<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="b-mobile-menu__header">
    <div class="b-mobile-menu__header-left">
        <a href="/" class="b-mobile-menu__header-logo"></a>
    </div>
    <div class="b-mobile-menu__header-right">
        <div class="b-account">
            <a href="/cabinet/<?php if($arResult['MESSAGES']): ?>messages/<?php endif; ?>" class="b-account__link<?php if($arResult['IS_AUTHORIZED']): ?> is-auth<?php endif; ?>">
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
        <div class="b-language">
            <span class="b-language__link">
                Ru
            </span>
            <ul class="b-language__list">
                <li class="b-language__item is-active">
                    <a href="#" class="b-language__item-link b-link-line">
                        Рус
                    </a>
                </li>
                <li class="b-language__item">
                    <a href="#" class="b-language__item-link b-link-line">
                        Eng
                    </a>
                </li>
                <li class="b-language__item">
                    <a href="#" class="b-language__item-link b-link-line">
                        中國
                    </a>
                </li>
            </ul>
        </div>
        <div class="b-close-menu-warp j-close-menu">
            <div class="b-close-menu">
                <div class="b-close-menu__line"></div>
                <div class="b-close-menu__line"></div>
            </div>
        </div>
    </div>
</div>
