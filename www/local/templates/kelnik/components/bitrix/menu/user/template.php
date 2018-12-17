<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php if (empty($arResult)): return; endif; ?>
<div class="b-account__tooltip">
    <div class="b-account__tooltip-block">
        <ul class="b-account__tooltip-list">
            <?php foreach($arResult as $arItem): ?>
            <li class="b-account__tooltip-item">
                <a href="<?= $arItem['LINK'] ?>" class="b-account__tooltip-link"><?= $arItem['TEXT'] ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>