<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php if (empty($arResult)): return; endif; ?>
<div class="b-page-submenu">
    <div class="b-page-submenu__block">
        <ul class="b-page-submenu__list">
            <?php foreach($arResult as $arItem): ?>
            <li class="b-page-submenu__item<?php if(!empty($arItem['SELECTED'])): ?> is-active<?php endif; ?>">
                <a href="<?= $arItem['LINK'] ?>" class="b-page-submenu__link b-link-line"><?= $arItem['TEXT'] ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>