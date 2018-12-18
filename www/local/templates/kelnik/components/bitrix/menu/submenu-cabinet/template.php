<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php if (empty($arResult)): return; endif; ?>
<div class="b-lk-login-nav">
    <ul class="b-lk-login-nav__list">
        <?php foreach($arResult as $arItem): ?>
        <li class="b-lk-login-nav__item<?php if(!empty($arItem['SELECTED'])): ?> is-active<?php endif; ?>">
            <a href="<?= $arItem['LINK'] ?>" class="b-lk-nav__link b-link-line"><?= $arItem['TEXT'] ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>