<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php if (empty($arResult)): return; endif; ?>
<div class="b-lk-nav">
    <ul class="b-lk-nav__list">
        <?php foreach($arResult as $arItem): ?>
            <?php
                $cssClasses = ['b-lk-nav__item'];

                if (!empty($arItem['PARAMS']['isMessages'])) {
                    $cssClasses[] = 'b-lk-nav__item-message';
                }

                if (!empty($arItem['SELECTED'])) {
                    $cssClasses[] = 'is-active';
                }

                if (!empty($arItem['PARAMS']['cnt'])) {
                    $cssClasses[] = 'is-new-message';
                }
            ?>
        <li class="<?= implode(' ', $cssClasses); ?>">
            <a href="<?= $arItem['LINK'] ?>" class="b-lk-nav__link b-link-line">
                <?= $arItem['TEXT'] ?>
                <?php if (!empty($arItem['PARAMS']['cnt'])): ?>
                    <span class="b-lk-nav__item-new-message"><?= $arItem['PARAMS']['cnt']; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
