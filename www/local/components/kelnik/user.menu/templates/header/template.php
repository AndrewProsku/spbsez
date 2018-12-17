<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="l-home__header-right">
    <div class="b-account">
        <a href="/cabinet/" class="b-account__link<?php if($arResult['IS_AUTHORIZED']): ?> is-auth<?php endif; ?>">
            <span class="b-account__link-icon">
               <?php if($arResult['IS_AUTHORIZED']): ?> <span class="b-account__messages">2</span><?php endif; ?>
            </span>
            <span class="b-account__link-text">Личный кабинет</span>
        </a>
    </div>
    <div class="b-language">
        <a href="#" class="b-language__link">
            Ru
        </a>
    </div>
    <div class="b-burger-wrap j-burger-click">
        <div class="b-burger">
            <div class="b-burger__line"></div>
            <div class="b-burger__line"></div>
            <div class="b-burger__line"></div>
        </div>
    </div>
</div>