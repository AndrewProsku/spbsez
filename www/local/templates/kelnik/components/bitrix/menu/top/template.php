<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php if (empty($arResult)): return; endif; ?>
<nav class="b-nav">
    <ul class="b-nav__list">
        <?php foreach($arResult as $arItem): ?>
            <?php if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1): continue; endif; ?>
            <li class="b-nav__item<?php if(!empty($arItem['SELECTED'])): ?> is-active<?php endif; ?>">
                <a href="<?= $arItem["LINK"] ?>" class="b-nav__link"><?= $arItem["TEXT"] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>