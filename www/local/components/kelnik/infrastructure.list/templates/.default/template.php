<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if(empty($arResult['ELEMENTS'])): return; endif; ?>

<div class="b-page-submenu b-page-submenu_with_switch">
    <div class="b-page-submenu__block">
        <ul class="b-page-submenu__list">
            <li class="b-page-submenu__item is-active">
                <a href="<?= $arParams['SEF_FOLDER']; ?>" class="b-page-submenu__link b-link-line">
                    <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_GENERAL_INFO'); ?>
                </a>
            </li>
            <?php foreach ($arResult['ELEMENTS'] as $arItem): ?>
            <li class="b-page-submenu__item">
                <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="b-page-submenu__link b-link-line">
                    <?if($arItem['NAME'] == 'Инновационный центр'){?>
                        <? $name = $arItem['NAME']; ?>
                    <?}else{?>
                        <? $name = \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_PLATFORM')." «".$arItem['NAME']."»"?>
                    <?}?>
                    <?=$name;?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="b-page-switch">
        <div><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_LIST'); ?></div>
        <a href="<?= $arParams['SEF_FOLDER']; ?>map/" class="b-page-switch__switch"></a>
        <a href="<?= $arParams['SEF_FOLDER']; ?>map/"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_ON_MAP'); ?></a>
    </div>
</div>

<div class="b-infrastructure-map__title b-infrastructure-map__title_only_mobile">
    <div class="b-page-switch b-infrastructure-switch">
        <div><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_LIST'); ?></div>
        <a href="<?= $arParams['SEF_FOLDER']; ?>map/" class="b-page-switch__switch"></a>
        <a href="<?= $arParams['SEF_FOLDER']; ?>map/"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_ON_MAP'); ?></a>
    </div>
    <h1><?= $APPLICATION->ShowTitle(false); ?></h1>
</div>

<div class="l-infrastructure">
    <div class="b-infrastructure-places">
        <?php foreach ($arResult['ELEMENTS'] as $arItem): ?>
            <div class="b-infrastructure-place">
                <?php if(!empty($arItem['IMAGE_ID_PATH'])): ?>
                <div class="b-infrastructure-place__img">
                    <img src="<?= $arItem['IMAGE_ID_PATH']; ?>" alt="<?= htmlentities($arItem['NAME'], ENT_QUOTES, SITE_CHARSET); ?>">
                </div>
                <?php endif; ?>
                <div class="b-infrastructure-place__title">              

                    <?if($arItem['NAME'] !== 'Инновационный центр'){?>
                        <h2><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_PLATFORM'); ?></h2>
                        
                        <? $name = "«".$arItem['NAME']."»"?>
                    <?}else{?>
                        <? $name = $arItem['NAME']; ?>
                    <?}?>
                    
                </div>
                <h3 class="b-infrastructure-place__name"><a class="b-link-line" href="<?= $arItem['DETAIL_PAGE_URL']; ?>"><span><?= $name; ?></span></a></h3>
                <div class="b-icons-list">
                    <?= $arItem['TEXT']; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
