<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if(empty($arResult['ELEMENTS'])): return; endif; ?>

<div class="b-page-submenu b-page-submenu_with_switch">
    <div class="b-page-submenu__block">
        <ul class="b-page-submenu__list">
            <?php foreach ($arResult['ELEMENTS'] as $arItem): ?>
                <li class="b-page-submenu__item">
                    <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="b-page-submenu__link b-link-line">
                        <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_PLATFORM'); ?> «<?= $arItem['NAME']; ?>»
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="b-page-switch">
        <a href="<?= $arParams['SEF_FOLDER']; ?>"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_LIST'); ?></a>
        <div class="b-page-switch__switch b-page-switch__switch_is_switched"></div>
        <div><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_ON_MAP'); ?></div>
    </div>
</div>

<div class="l-infrastructure-map">
    <div class="b-infrastructure-map">
        <div class="b-infrastructure-map__title">
            <div class="b-page-switch b-infrastructure-switch">
                <a href="<?= $arParams['SEF_FOLDER']; ?>"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_LIST'); ?></a>
                <div class="b-page-switch__switch b-page-switch__switch_is_switched"></div>
                <div><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_ON_MAP'); ?></div>
            </div>
            <h1><?= $APPLICATION->ShowTitle(false); ?></h1>
        </div>
        <div class="b-infrastructure-map__content">
            <div class="b-infrastructure-map__yandex-map j-yandex-map" data-lang="<?= LANGUAGE_ID; ?>" data-json="<?= base64_encode(json_encode($arResult['MAP_DATA'])); ?>">
                <div id="first" class="b-yandex-map__base"></div>
            </div>
        </div>
    </div>
</div>
