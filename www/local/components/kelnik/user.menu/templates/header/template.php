<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="l-home__header-right">
    <div class="b-account">
        <a href="<?= LANG_DIR; ?>cabinet/<?php if($arResult['MESSAGES']): ?>messages/<?php endif; ?>" class="b-account__link<?php if($arResult['IS_AUTHORIZED']): ?> is-auth<?php endif; ?>">
            <span class="b-account__link-icon">
               <?php if($arResult['MESSAGES']): ?> <span class="b-account__messages"><?= $arResult['MESSAGES']; ?></span><?php endif; ?>
            </span>
            <span class="b-account__link-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_CABINET'); ?></span>
        </a>
    </div>
    <? $APPLICATION->IncludeComponent(
        "kelnik:lang.menu",
        "oez",
        array(
            "COMPONENT_TEMPLATE" => "oez",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600"
        ),
        array()
    ); ?>
    <div class="b-burger-wrap j-burger-click">
        <div class="b-burger">
            <div class="b-burger__line"></div>
            <div class="b-burger__line"></div>
            <div class="b-burger__line"></div>
        </div>
    </div>
</div>
