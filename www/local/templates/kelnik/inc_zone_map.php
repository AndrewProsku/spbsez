<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php
    $noidorfLink = LANG_DIR != SezLang::CHINESE_DIR
                    ? LANG_DIR . 'infrastructure/noidorf/'
                    : 'javascript:;';
    $novLink = LANG_DIR != SezLang::CHINESE_DIR
                    ? LANG_DIR . 'infrastructure/novoorlovskaya/'
                    : 'javascript:;';
?>
<div<?php if(LANG_DIR == SezLang::CHINESE_DIR): ?> id="area" class="l-home-plots" <?php else: ?> class="l-home-plots j-home-plots"<?php endif; ?>>
    <div class="l-home-plots__title-mobile"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_LOCATION'); ?></div>
    <div class="l-home-plots__list">
        <div class="l-home-plots__item">
            <div class="l-home-plots__map l-home-plots__map-novoorlovskaya">
                <div class="l-home-plots__all-map-images lang-<?= LANGUAGE_ID; ?>">
                    <div class="l-home-plots__all-map-icon-wrap">
                        <a href="<?= $novLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-one">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_NOVOORLOVSKAYA'); ?></span>
                        </a>
                        <a href="<?= $noidorfLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-two">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_NOIDORF'); ?></span>
                        </a>
                        <div class="l-home-plots__all-map-icon icon-port">
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SEA_PORT'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-center-city">
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_CENTER'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                            <div class="b-tooltip  b-tooltip_position_left"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_LAD'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-airport">
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_AIRPORT'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="l-home-plots__content">
                <?php if(LANG_DIR == \SezLang::CHINESE_DIR): ?>
                    <div class="l-home-plots__title lang-<?= LANGUAGE_ID; ?>">
                        <span class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_NOVOORLOVSKAYA'); ?></span>
                    </div>
                <?php else: ?>
                    <div class="l-home-plots__title">
                        <a href="<?= $novLink; ?>" class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_NOVOORLOVSKAYA'); ?></a>
                    </div>
                <?php endif; ?>
                <div class="l-home-plots__desc">
                    <div class="l-home-plots__desc-item">
                        <p>
                            <strong>163,33 <?=\Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_AREA_HA'); ?></strong> <span><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_AREA'); ?></span>
                        </p>
                    </div>
                    <div class="l-home-plots__desc-item">
                        <p>
                            <strong>20 <?=\Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_KM'); ?></strong> <span><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_TO_CENTER'); ?></span>
                        </p>
                    </div>
                    <div class="l-home-plots__desc-item">
                        <p>
                            <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_PRIM'); ?>
                        </p>
                    </div>
                </div>
                <div class="l-home-plots__text">
                    <p><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_DESCR_NOV'); ?></p>
                </div>
                <?php if(LANG_DIR !== \SezLang::CHINESE_DIR): ?>
                <div class="l-home-plots__link">
                    <a href="<?= $novLink; ?>" class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_MORE'); ?></a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="l-home-plots__item">
            <div class="l-home-plots__map l-home-plots__map-neudorf">
                <div class="l-home-plots__all-map-images lang-<?= LANGUAGE_ID; ?>">
                    <div class="l-home-plots__all-map-icon-wrap">
                        <a href="<?= $novLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-one">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_NOVOORLOVSKAYA'); ?></span>
                        </a>
                        <a href="<?= $noidorfLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-two">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_NOIDORF'); ?></span>
                        </a>
                        <div class="l-home-plots__all-map-icon icon-port">
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SEA_PORT'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-center-city">
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_CENTER'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                            <div class="b-tooltip  b-tooltip_position_left"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_LAD'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-airport">
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_AIRPORT'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="l-home-plots__content">
                <?php if(LANG_DIR == \SezLang::CHINESE_DIR): ?>
                    <div class="l-home-plots__title lang-<?= LANGUAGE_ID; ?>">
                        <span class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_NOIDORF'); ?></span>
                    </div>
                <?php else: ?>
                    <div class="l-home-plots__title">
                        <a href="<?= $noidorfLink; ?>" class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_NOIDORF'); ?></a>
                    </div>
                <?php endif; ?>
                <div class="l-home-plots__desc">
                    <div class="l-home-plots__desc-item">
                        <p>
                            <strong>18,99 <?=\Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_AREA_HA'); ?></strong> <span><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_AREA'); ?></span>
                        </p>
                    </div>
                    <div class="l-home-plots__desc-item">
                        <p>
                            <strong>30 <?=\Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_KM'); ?></strong> <span><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_TO_CENTER'); ?></span>
                        </p>
                    </div>
                    <div class="l-home-plots__desc-item">
                        <p>
                            <strong><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_STRELNA'); ?></strong> <span><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SW'); ?></span>
                        </p>
                    </div>
                </div>
                <div class="l-home-plots__text">
                    <p>
                        <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_DESCR_NOIDORF'); ?>
                    </p>
                </div>
                <?php if(LANG_DIR !== \SezLang::CHINESE_DIR): ?>
                <div class="l-home-plots__link">
                    <a href="<?= $noidorfLink; ?>" class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_MORE'); ?></a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="l-home-plots__all-map">
        <div class="l-home-plots__all-map-images lang-<?= LANGUAGE_ID; ?>">
            <div class="l-home-plots__all-map-icon-wrap">
                <a href="<?= $novLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-novoorlovskaya">
                    <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_NOVOORLOVSKAYA'); ?></span>
                </a>
                <a href="<?= $noidorfLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-neudorf">
                    <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_NOIDORF'); ?></span>
                </a>
                <div class="l-home-plots__all-map-icon icon-port">
                    <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SEA_PORT'); ?></div>
                </div>

                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                    <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY'); ?></div>
                </div>

                <div class="l-home-plots__all-map-icon icon-center-city">
                    <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_CENTER'); ?></div>
                </div>

                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                    <div class="b-tooltip  b-tooltip_position_left"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_LAD'); ?></div>
                </div>

                <div class="l-home-plots__all-map-icon icon-airport">
                    <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_AIRPORT'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
