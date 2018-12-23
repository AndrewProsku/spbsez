<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php if (empty($arResult)): return; endif; ?>
<?php
$arCopy = $arResult;
$arItem = array_shift($arCopy);
?>
<div class="b-links b-profile__links">
    <div class="b-links-mobile">
        <div class="b-links-mobile-header">
            <a href="<?= $arItem['LINK']; ?>" class="b-links__link-mobile"><?= $arItem['TEXT']; ?></a>
            <span class="b-links__link-icon"></span>
        </div>
        <?php if ($arCopy): ?>
            <ul class="b-links-mobile-body">
                <li class="b-links-mobile-item">
                    <?php foreach($arResult as $arItem): ?>
                        <a href="<?= $arItem['LINK'] ?>" class="b-links-mobile-link<?php if(!empty($arItem['SELECTED'])): ?> is-active<?php endif; ?>"><?= $arItem['TEXT'] ?></a>
                    <?php endforeach; ?>
                </li>
            </ul>
        <?php endif; ?>
    </div>
    <div class="b-links-desctop">
        <?php foreach($arResult as $arItem): ?>
            <a href="<?= $arItem['LINK'] ?>" class="b-links__link<?php if(!empty($arItem['SELECTED'])): ?> is-active<?php endif; ?>"><?= $arItem['TEXT'] ?></a>
        <?php endforeach; ?>
    </div>
</div>