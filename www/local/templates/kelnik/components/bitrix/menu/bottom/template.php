<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php if (empty($arResult)): return; endif; ?>
<nav class="b-footer__nav">
    <ul class="b-footer__nav-list">
        <?php foreach($arResult as $arItem): ?>
            <?php if($arParams['MAX_LEVEL'] == 1 && $arItem['DEPTH_LEVEL'] > 1): continue; endif; ?>
            <li class="b-footer__nav-item">
                <a href="<?= $arItem['LINK'] ?>" class="b-footer__nav-link"><?= $arItem['TEXT'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>