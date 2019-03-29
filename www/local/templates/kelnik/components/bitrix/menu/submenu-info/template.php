<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php if (empty($arResult)): return; endif; ?>
<?php
$arCopy = $arResult;
$arItem = [];

foreach ($arCopy as $k => $v) {
    if ($v['SELECTED']) {
        $arItem = $v;
        unset($arCopy[$k]);
    }
}
?>
<div class="b-links j-accordion-links b-profile__links">
    <div class="b-links-mobile j-accordion-links__mobile">
        <div class="b-links-mobile__header j-accordion-links__header">
            <span class="b-links-mobile__title"><?= $arItem['TEXT']; ?></span>
        </div>
        <?php if ($arCopy): ?>
            <ul class="b-links-mobile__body">
                <li class="b-links-mobile__item">
                    <?php foreach($arCopy as $arItem): ?>
                        <a href="<?= $arItem['LINK'] ?>" class="b-links-mobile__link<?php if(!empty($arItem['SELECTED'])): ?> is-active<?php endif; ?>"><?= $arItem['TEXT'] ?></a>
                    <?php endforeach; ?>
                </li>
            </ul>
        <?php endif; ?>
    </div>
    <div class="b-links-desktop">
        <?php foreach($arResult as $arItem): ?>
            <a href="<?= $arItem['LINK'] ?>" class="b-links__link<?php if(!empty($arItem['SELECTED'])): ?> is-active<?php endif; ?>"><?= $arItem['TEXT'] ?></a>
        <?php endforeach; ?>
    </div>
</div>
