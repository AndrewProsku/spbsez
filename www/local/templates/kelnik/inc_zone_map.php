<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php
    $noidorfLink = LANG_DIR != SezLang::CHINESE_DIR
                    ? LANG_DIR . 'infrastructure/noidorf/'
                    : 'javascript:;';
    $novLink = LANG_DIR != SezLang::CHINESE_DIR
                    ? LANG_DIR . 'infrastructure/novoorlovskaya/'
                    : 'javascript:;';
    $parnasLink = LANG_DIR != SezLang::CHINESE_DIR
        ? LANG_DIR . 'infrastructure/parnas/'
        : 'javascript:;';
    $shusharyLink = LANG_DIR != SezLang::CHINESE_DIR
        ? LANG_DIR . 'infrastructure/shushary/'
        : 'javascript:;';
?>
<div id="area"<?php if(LANG_DIR == SezLang::CHINESE_DIR): ?> class="l-home-plots" <?php else: ?> class="l-home-plots j-home-plots"<?php endif; ?>>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" style="border: 0 !important; clip: rect(0 0 0 0) !important; height: 1px !important; margin: -1px !important; overflow: hidden !important; padding: 0 !important; position: absolute !important; width: 1px !important;">
        <defs>
            <linearGradient id="plots-gradient" x1="30.6" y1="50.66" x2="49.4" y2="31.86" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#373e99"/>
                <stop offset="1" stop-color="#662e91"/>
            </linearGradient>
        </defs>
    </svg>
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
                        <a href="<?= $parnasLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-three">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_PARNAS'); ?></span>
                        </a>
                        <a href="<?= $shusharyLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-four">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SHUSHARY'); ?></span>
                        </a>
                        <div class="l-home-plots__all-map-icon icon-port icon-port-mobile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M53.1 46.11l-2-7a.14.14 0 0 0-.09-.08.15.15 0 0 0-.13 0l-5 5.2a.16.16 0 0 0-.05.15.14.14 0 0 0 .1.11l1.93.48a8.3 8.3 0 0 1-6.23 7.21V32.8a4.41 4.41 0 1 0-3.2 0v19.46a8.3 8.3 0 0 1-6.23-7.21l1.92-.48a.14.14 0 0 0 .11-.11.16.16 0 0 0-.05-.15l-5-5.2a.15.15 0 0 0-.13 0 .14.14 0 0 0-.09.08l-2 7a.12.12 0 0 0 0 .15.12.12 0 0 0 .15.05l2-.48a11.47 11.47 0 0 0 10.64 9.86h.64A11.47 11.47 0 0 0 51 45.83l2 .48a.12.12 0 0 0 .15-.05.12.12 0 0 0 0-.15M40 27.08a1.62 1.62 0 1 1-1.61 1.62A1.62 1.62 0 0 1 40 27.08"
                                        fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SEA_PORT'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40.07,24.19a25.33,25.33,0,0,0-8.42,1.5l-.07,0-.07,0h0a5.32,5.32,0,0,0-1.64.84,2.4,2.4,0,0,0-.95,1.86V47.92a2.86,2.86,0,0,0,2.91,2.77h1.11l-3.85,3.9c-.54.54-.08,1.22.61,1.22H50.33a.73.73,0,0,0,.56-1.29L47,50.69h1.23a2.86,2.86,0,0,0,2.91-2.77V28.44a2.38,2.38,0,0,0-1-1.85,6.26,6.26,0,0,0-1.7-.88,23.52,23.52,0,0,0-8.37-1.52Zm-9.72,14.6h8.82v-7.2H30.35Zm10.26,0h9v-7.2h-9Zm-6.38,4.09A2.16,2.16,0,1,1,32.07,45,2.17,2.17,0,0,1,34.23,42.88Zm11.51,0a2.16,2.16,0,1,1-2.15,2.16A2.17,2.17,0,0,1,45.74,42.89ZM35,50.69h9.92l1.07,1h-12Zm-2.44,2.47H47.43l1.25,1.22H31.32Z"
                                        fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_BALT'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-center-city">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40 20c-8.73 0-15.81 6.67-15.81 14.91C24.19 47.75 40 62.24 40 62.24s15.81-15.32 15.81-27.33C55.81 26.67 48.73 20 40 20m-.1 23.4a7.5 7.5 0 1 1 7.5-7.5 7.5 7.5 0 0 1-7.5 7.5"
                                        fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_CENTER'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40.07,24.19a25.33,25.33,0,0,0-8.42,1.5l-.07,0-.07,0h0a5.32,5.32,0,0,0-1.64.84,2.4,2.4,0,0,0-.95,1.86V47.92a2.86,2.86,0,0,0,2.91,2.77h1.11l-3.85,3.9c-.54.54-.08,1.22.61,1.22H50.33a.73.73,0,0,0,.56-1.29L47,50.69h1.23a2.86,2.86,0,0,0,2.91-2.77V28.44a2.38,2.38,0,0,0-1-1.85,6.26,6.26,0,0,0-1.7-.88,23.52,23.52,0,0,0-8.37-1.52Zm-9.72,14.6h8.82v-7.2H30.35Zm10.26,0h9v-7.2h-9Zm-6.38,4.09A2.16,2.16,0,1,1,32.07,45,2.17,2.17,0,0,1,34.23,42.88Zm11.51,0a2.16,2.16,0,1,1-2.15,2.16A2.17,2.17,0,0,1,45.74,42.89ZM35,50.69h9.92l1.07,1h-12Zm-2.44,2.47H47.43l1.25,1.22H31.32Z"
                                        fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip  b-tooltip_position_left"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_LAD'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-airport">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M31.94 44.77l6.8-7.41a.86.86 0 0 0-.22-1.36L26.8 29.38a1.1 1.1 0 0 1-.53-.69 1 1 0 0 1 1.14-1.09l16.69 3.27a.9.9 0 0 0 .74-.18l7.06-5.84h.05a2.37 2.37 0 0 1 1.83-.7 2.45 2.45 0 0 1 1.74.92 2.37 2.37 0 0 1-.3 3.18l-5.75 7a.92.92 0 0 0-.18.75l3.27 16.7a1 1 0 0 1-.22.79l-.18.13a.85.85 0 0 1-.74.26.93.93 0 0 1-.65-.48L44 41.54a.93.93 0 0 0-.62-.43.9.9 0 0 0-.74.22l-7.4 6.79a.9.9 0 0 0-.27.79l1 5.88a1 1 0 0 1-.26.83.94.94 0 0 1-.83.26.86.86 0 0 1-.7-.52l-3.05-6.1a.82.82 0 0 0-.39-.39l-6.32-3a.84.84 0 0 1-.48-.65.89.89 0 0 1 .26-.79l.22-.21a.84.84 0 0 1 .68-.22h.13l5.93 1a.66.66 0 0 0 .78-.22"
                                        fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
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
                        <a href="<?= $parnasLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-three">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_PARNAS'); ?></span>
                        </a>
                        <a href="<?= $shusharyLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-four">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SHUSHARY'); ?></span>
                        </a>
                        <div class="l-home-plots__all-map-icon icon-port icon-port-mobile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M53.1 46.11l-2-7a.14.14 0 0 0-.09-.08.15.15 0 0 0-.13 0l-5 5.2a.16.16 0 0 0-.05.15.14.14 0 0 0 .1.11l1.93.48a8.3 8.3 0 0 1-6.23 7.21V32.8a4.41 4.41 0 1 0-3.2 0v19.46a8.3 8.3 0 0 1-6.23-7.21l1.92-.48a.14.14 0 0 0 .11-.11.16.16 0 0 0-.05-.15l-5-5.2a.15.15 0 0 0-.13 0 .14.14 0 0 0-.09.08l-2 7a.12.12 0 0 0 0 .15.12.12 0 0 0 .15.05l2-.48a11.47 11.47 0 0 0 10.64 9.86h.64A11.47 11.47 0 0 0 51 45.83l2 .48a.12.12 0 0 0 .15-.05.12.12 0 0 0 0-.15M40 27.08a1.62 1.62 0 1 1-1.61 1.62A1.62 1.62 0 0 1 40 27.08"
                                        fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SEA_PORT'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40.07,24.19a25.33,25.33,0,0,0-8.42,1.5l-.07,0-.07,0h0a5.32,5.32,0,0,0-1.64.84,2.4,2.4,0,0,0-.95,1.86V47.92a2.86,2.86,0,0,0,2.91,2.77h1.11l-3.85,3.9c-.54.54-.08,1.22.61,1.22H50.33a.73.73,0,0,0,.56-1.29L47,50.69h1.23a2.86,2.86,0,0,0,2.91-2.77V28.44a2.38,2.38,0,0,0-1-1.85,6.26,6.26,0,0,0-1.7-.88,23.52,23.52,0,0,0-8.37-1.52Zm-9.72,14.6h8.82v-7.2H30.35Zm10.26,0h9v-7.2h-9Zm-6.38,4.09A2.16,2.16,0,1,1,32.07,45,2.17,2.17,0,0,1,34.23,42.88Zm11.51,0a2.16,2.16,0,1,1-2.15,2.16A2.17,2.17,0,0,1,45.74,42.89ZM35,50.69h9.92l1.07,1h-12Zm-2.44,2.47H47.43l1.25,1.22H31.32Z"
                                        fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_BALT'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-center-city">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40 20c-8.73 0-15.81 6.67-15.81 14.91C24.19 47.75 40 62.24 40 62.24s15.81-15.32 15.81-27.33C55.81 26.67 48.73 20 40 20m-.1 23.4a7.5 7.5 0 1 1 7.5-7.5 7.5 7.5 0 0 1-7.5 7.5"
                                        fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_CENTER'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40.07,24.19a25.33,25.33,0,0,0-8.42,1.5l-.07,0-.07,0h0a5.32,5.32,0,0,0-1.64.84,2.4,2.4,0,0,0-.95,1.86V47.92a2.86,2.86,0,0,0,2.91,2.77h1.11l-3.85,3.9c-.54.54-.08,1.22.61,1.22H50.33a.73.73,0,0,0,.56-1.29L47,50.69h1.23a2.86,2.86,0,0,0,2.91-2.77V28.44a2.38,2.38,0,0,0-1-1.85,6.26,6.26,0,0,0-1.7-.88,23.52,23.52,0,0,0-8.37-1.52Zm-9.72,14.6h8.82v-7.2H30.35Zm10.26,0h9v-7.2h-9Zm-6.38,4.09A2.16,2.16,0,1,1,32.07,45,2.17,2.17,0,0,1,34.23,42.88Zm11.51,0a2.16,2.16,0,1,1-2.15,2.16A2.17,2.17,0,0,1,45.74,42.89ZM35,50.69h9.92l1.07,1h-12Zm-2.44,2.47H47.43l1.25,1.22H31.32Z"
                                        fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip  b-tooltip_position_left"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_LAD'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-airport">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M31.94 44.77l6.8-7.41a.86.86 0 0 0-.22-1.36L26.8 29.38a1.1 1.1 0 0 1-.53-.69 1 1 0 0 1 1.14-1.09l16.69 3.27a.9.9 0 0 0 .74-.18l7.06-5.84h.05a2.37 2.37 0 0 1 1.83-.7 2.45 2.45 0 0 1 1.74.92 2.37 2.37 0 0 1-.3 3.18l-5.75 7a.92.92 0 0 0-.18.75l3.27 16.7a1 1 0 0 1-.22.79l-.18.13a.85.85 0 0 1-.74.26.93.93 0 0 1-.65-.48L44 41.54a.93.93 0 0 0-.62-.43.9.9 0 0 0-.74.22l-7.4 6.79a.9.9 0 0 0-.27.79l1 5.88a1 1 0 0 1-.26.83.94.94 0 0 1-.83.26.86.86 0 0 1-.7-.52l-3.05-6.1a.82.82 0 0 0-.39-.39l-6.32-3a.84.84 0 0 1-.48-.65.89.89 0 0 1 .26-.79l.22-.21a.84.84 0 0 1 .68-.22h.13l5.93 1a.66.66 0 0 0 .78-.22"
                                        fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
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

        <div class="l-home-plots__item">
            <div class="l-home-plots__map l-home-plots__map-parnas">
                <div class="l-home-plots__all-map-images lang-<?= LANGUAGE_ID; ?>">
                    <div class="l-home-plots__all-map-icon-wrap">
                        <a href="<?= $novLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-one">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_NOVOORLOVSKAYA'); ?></span>
                        </a>
                        <a href="<?= $noidorfLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-two">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_NOIDORF'); ?></span>
                        </a>
                        <a href="<?= $parnasLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-three">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_PARNAS'); ?></span>
                        </a>
                        <a href="<?= $shusharyLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-four">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SHUSHARY'); ?></span>
                        </a>
                        <div class="l-home-plots__all-map-icon icon-port icon-port-mobile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                 viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M53.1 46.11l-2-7a.14.14 0 0 0-.09-.08.15.15 0 0 0-.13 0l-5 5.2a.16.16 0 0 0-.05.15.14.14 0 0 0 .1.11l1.93.48a8.3 8.3 0 0 1-6.23 7.21V32.8a4.41 4.41 0 1 0-3.2 0v19.46a8.3 8.3 0 0 1-6.23-7.21l1.92-.48a.14.14 0 0 0 .11-.11.16.16 0 0 0-.05-.15l-5-5.2a.15.15 0 0 0-.13 0 .14.14 0 0 0-.09.08l-2 7a.12.12 0 0 0 0 .15.12.12 0 0 0 .15.05l2-.48a11.47 11.47 0 0 0 10.64 9.86h.64A11.47 11.47 0 0 0 51 45.83l2 .48a.12.12 0 0 0 .15-.05.12.12 0 0 0 0-.15M40 27.08a1.62 1.62 0 1 1-1.61 1.62A1.62 1.62 0 0 1 40 27.08"
                                      fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SEA_PORT'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                 viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40.07,24.19a25.33,25.33,0,0,0-8.42,1.5l-.07,0-.07,0h0a5.32,5.32,0,0,0-1.64.84,2.4,2.4,0,0,0-.95,1.86V47.92a2.86,2.86,0,0,0,2.91,2.77h1.11l-3.85,3.9c-.54.54-.08,1.22.61,1.22H50.33a.73.73,0,0,0,.56-1.29L47,50.69h1.23a2.86,2.86,0,0,0,2.91-2.77V28.44a2.38,2.38,0,0,0-1-1.85,6.26,6.26,0,0,0-1.7-.88,23.52,23.52,0,0,0-8.37-1.52Zm-9.72,14.6h8.82v-7.2H30.35Zm10.26,0h9v-7.2h-9Zm-6.38,4.09A2.16,2.16,0,1,1,32.07,45,2.17,2.17,0,0,1,34.23,42.88Zm11.51,0a2.16,2.16,0,1,1-2.15,2.16A2.17,2.17,0,0,1,45.74,42.89ZM35,50.69h9.92l1.07,1h-12Zm-2.44,2.47H47.43l1.25,1.22H31.32Z"
                                      fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_BALT'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-center-city">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                 viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40 20c-8.73 0-15.81 6.67-15.81 14.91C24.19 47.75 40 62.24 40 62.24s15.81-15.32 15.81-27.33C55.81 26.67 48.73 20 40 20m-.1 23.4a7.5 7.5 0 1 1 7.5-7.5 7.5 7.5 0 0 1-7.5 7.5"
                                      fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_CENTER'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                 viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40.07,24.19a25.33,25.33,0,0,0-8.42,1.5l-.07,0-.07,0h0a5.32,5.32,0,0,0-1.64.84,2.4,2.4,0,0,0-.95,1.86V47.92a2.86,2.86,0,0,0,2.91,2.77h1.11l-3.85,3.9c-.54.54-.08,1.22.61,1.22H50.33a.73.73,0,0,0,.56-1.29L47,50.69h1.23a2.86,2.86,0,0,0,2.91-2.77V28.44a2.38,2.38,0,0,0-1-1.85,6.26,6.26,0,0,0-1.7-.88,23.52,23.52,0,0,0-8.37-1.52Zm-9.72,14.6h8.82v-7.2H30.35Zm10.26,0h9v-7.2h-9Zm-6.38,4.09A2.16,2.16,0,1,1,32.07,45,2.17,2.17,0,0,1,34.23,42.88Zm11.51,0a2.16,2.16,0,1,1-2.15,2.16A2.17,2.17,0,0,1,45.74,42.89ZM35,50.69h9.92l1.07,1h-12Zm-2.44,2.47H47.43l1.25,1.22H31.32Z"
                                      fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip  b-tooltip_position_left"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_LAD'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-airport">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                 viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M31.94 44.77l6.8-7.41a.86.86 0 0 0-.22-1.36L26.8 29.38a1.1 1.1 0 0 1-.53-.69 1 1 0 0 1 1.14-1.09l16.69 3.27a.9.9 0 0 0 .74-.18l7.06-5.84h.05a2.37 2.37 0 0 1 1.83-.7 2.45 2.45 0 0 1 1.74.92 2.37 2.37 0 0 1-.3 3.18l-5.75 7a.92.92 0 0 0-.18.75l3.27 16.7a1 1 0 0 1-.22.79l-.18.13a.85.85 0 0 1-.74.26.93.93 0 0 1-.65-.48L44 41.54a.93.93 0 0 0-.62-.43.9.9 0 0 0-.74.22l-7.4 6.79a.9.9 0 0 0-.27.79l1 5.88a1 1 0 0 1-.26.83.94.94 0 0 1-.83.26.86.86 0 0 1-.7-.52l-3.05-6.1a.82.82 0 0 0-.39-.39l-6.32-3a.84.84 0 0 1-.48-.65.89.89 0 0 1 .26-.79l.22-.21a.84.84 0 0 1 .68-.22h.13l5.93 1a.66.66 0 0 0 .78-.22"
                                      fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_AIRPORT'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="l-home-plots__content">
                <?php if(LANG_DIR == \SezLang::CHINESE_DIR): ?>
                    <div class="l-home-plots__title lang-<?= LANGUAGE_ID; ?>">
                        <span class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_PARNAS'); ?></span>
                    </div>
                <?php else: ?>
                    <div class="l-home-plots__title">
                        <a href="<?= $parnasLink; ?>" class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_PARNAS'); ?></a>
                    </div>
                <?php endif; ?>
                <div class="l-home-plots__desc">
                    <div class="l-home-plots__desc-item">
                        <p>
                            <strong>27,58 <?=\Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_AREA_HA'); ?></strong> <span><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_AREA'); ?></span>
                        </p>
                    </div>
                    <div class="l-home-plots__desc-item">
                        <p>
                            <strong>17,5 <?=\Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_KM'); ?></strong> <span><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_TO_CENTER'); ?></span>
                        </p>
                    </div>
                    <div class="l-home-plots__desc-item">
                        <p>
                            <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_VYB'); ?>
                        </p>
                    </div>
                </div>
                <div class="l-home-plots__text">
                    <p>
                        <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_DESCR_PARNAS'); ?>
                    </p>
                </div>
                <?php if(LANG_DIR !== \SezLang::CHINESE_DIR): ?>
                    <div class="l-home-plots__link">
                        <a href="<?= $parnasLink; ?>" class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_MORE'); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="l-home-plots__item">
            <div class="l-home-plots__map l-home-plots__map-shushary">
                <div class="l-home-plots__all-map-images lang-<?= LANGUAGE_ID; ?>">
                    <div class="l-home-plots__all-map-icon-wrap">
                        <a href="<?= $novLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-one">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_NOVOORLOVSKAYA'); ?></span>
                        </a>
                        <a href="<?= $noidorfLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-two">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_NOIDORF'); ?></span>
                        </a>
                        <a href="<?= $parnasLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-three">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_PARNAS'); ?></span>
                        </a>
                        <a href="<?= $shusharyLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-four">
                            <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SHUSHARY'); ?></span>
                        </a>
                        <div class="l-home-plots__all-map-icon icon-port icon-port-mobile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                 viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M53.1 46.11l-2-7a.14.14 0 0 0-.09-.08.15.15 0 0 0-.13 0l-5 5.2a.16.16 0 0 0-.05.15.14.14 0 0 0 .1.11l1.93.48a8.3 8.3 0 0 1-6.23 7.21V32.8a4.41 4.41 0 1 0-3.2 0v19.46a8.3 8.3 0 0 1-6.23-7.21l1.92-.48a.14.14 0 0 0 .11-.11.16.16 0 0 0-.05-.15l-5-5.2a.15.15 0 0 0-.13 0 .14.14 0 0 0-.09.08l-2 7a.12.12 0 0 0 0 .15.12.12 0 0 0 .15.05l2-.48a11.47 11.47 0 0 0 10.64 9.86h.64A11.47 11.47 0 0 0 51 45.83l2 .48a.12.12 0 0 0 .15-.05.12.12 0 0 0 0-.15M40 27.08a1.62 1.62 0 1 1-1.61 1.62A1.62 1.62 0 0 1 40 27.08"
                                      fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SEA_PORT'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                 viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40.07,24.19a25.33,25.33,0,0,0-8.42,1.5l-.07,0-.07,0h0a5.32,5.32,0,0,0-1.64.84,2.4,2.4,0,0,0-.95,1.86V47.92a2.86,2.86,0,0,0,2.91,2.77h1.11l-3.85,3.9c-.54.54-.08,1.22.61,1.22H50.33a.73.73,0,0,0,.56-1.29L47,50.69h1.23a2.86,2.86,0,0,0,2.91-2.77V28.44a2.38,2.38,0,0,0-1-1.85,6.26,6.26,0,0,0-1.7-.88,23.52,23.52,0,0,0-8.37-1.52Zm-9.72,14.6h8.82v-7.2H30.35Zm10.26,0h9v-7.2h-9Zm-6.38,4.09A2.16,2.16,0,1,1,32.07,45,2.17,2.17,0,0,1,34.23,42.88Zm11.51,0a2.16,2.16,0,1,1-2.15,2.16A2.17,2.17,0,0,1,45.74,42.89ZM35,50.69h9.92l1.07,1h-12Zm-2.44,2.47H47.43l1.25,1.22H31.32Z"
                                      fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_BALT'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-center-city">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                 viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40 20c-8.73 0-15.81 6.67-15.81 14.91C24.19 47.75 40 62.24 40 62.24s15.81-15.32 15.81-27.33C55.81 26.67 48.73 20 40 20m-.1 23.4a7.5 7.5 0 1 1 7.5-7.5 7.5 7.5 0 0 1-7.5 7.5"
                                      fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_CENTER'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                 viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M40.07,24.19a25.33,25.33,0,0,0-8.42,1.5l-.07,0-.07,0h0a5.32,5.32,0,0,0-1.64.84,2.4,2.4,0,0,0-.95,1.86V47.92a2.86,2.86,0,0,0,2.91,2.77h1.11l-3.85,3.9c-.54.54-.08,1.22.61,1.22H50.33a.73.73,0,0,0,.56-1.29L47,50.69h1.23a2.86,2.86,0,0,0,2.91-2.77V28.44a2.38,2.38,0,0,0-1-1.85,6.26,6.26,0,0,0-1.7-.88,23.52,23.52,0,0,0-8.37-1.52Zm-9.72,14.6h8.82v-7.2H30.35Zm10.26,0h9v-7.2h-9Zm-6.38,4.09A2.16,2.16,0,1,1,32.07,45,2.17,2.17,0,0,1,34.23,42.88Zm11.51,0a2.16,2.16,0,1,1-2.15,2.16A2.17,2.17,0,0,1,45.74,42.89ZM35,50.69h9.92l1.07,1h-12Zm-2.44,2.47H47.43l1.25,1.22H31.32Z"
                                      fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip  b-tooltip_position_left"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_LAD'); ?></div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-airport">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                 viewBox="0 0 80 80" class="plots-icon">
                                <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                                <path d="M31.94 44.77l6.8-7.41a.86.86 0 0 0-.22-1.36L26.8 29.38a1.1 1.1 0 0 1-.53-.69 1 1 0 0 1 1.14-1.09l16.69 3.27a.9.9 0 0 0 .74-.18l7.06-5.84h.05a2.37 2.37 0 0 1 1.83-.7 2.45 2.45 0 0 1 1.74.92 2.37 2.37 0 0 1-.3 3.18l-5.75 7a.92.92 0 0 0-.18.75l3.27 16.7a1 1 0 0 1-.22.79l-.18.13a.85.85 0 0 1-.74.26.93.93 0 0 1-.65-.48L44 41.54a.93.93 0 0 0-.62-.43.9.9 0 0 0-.74.22l-7.4 6.79a.9.9 0 0 0-.27.79l1 5.88a1 1 0 0 1-.26.83.94.94 0 0 1-.83.26.86.86 0 0 1-.7-.52l-3.05-6.1a.82.82 0 0 0-.39-.39l-6.32-3a.84.84 0 0 1-.48-.65.89.89 0 0 1 .26-.79l.22-.21a.84.84 0 0 1 .68-.22h.13l5.93 1a.66.66 0 0 0 .78-.22"
                                      fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                            </svg>
                            <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_AIRPORT'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="l-home-plots__content">
                <?php if(LANG_DIR == \SezLang::CHINESE_DIR): ?>
                    <div class="l-home-plots__title lang-<?= LANGUAGE_ID; ?>">
                        <span class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_SHUSHARY'); ?></span>
                    </div>
                <?php else: ?>
                    <div class="l-home-plots__title">
                        <a href="<?= $shusharyLink; ?>" class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_SHUSHARY'); ?></a>
                    </div>
                <?php endif; ?>
                <div class="l-home-plots__desc">
                    <div class="l-home-plots__desc-item">
                        <p>
                            <strong>127,23 <?=\Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_AREA_HA'); ?></strong> <span><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_AREA'); ?></span>
                        </p>
                    </div>
                    <div class="l-home-plots__desc-item">
                        <p>
                            <strong>17 <?=\Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SITE_KM'); ?></strong> <span><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_TO_CENTER'); ?></span>
                        </p>
                    </div>
                    <div class="l-home-plots__desc-item">
                        <p>
                            <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_PUSHKIN'); ?>
                        </p>
                    </div>
                </div>
                <div class="l-home-plots__text">
                    <p>
                        <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_DESCR_SHUSHARY'); ?>
                    </p>
                </div>
                <?php if(LANG_DIR !== \SezLang::CHINESE_DIR): ?>
                    <div class="l-home-plots__link">
                        <a href="<?= $shusharyLink; ?>" class="b-link-line"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_MORE'); ?></a>
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
                <a href="<?= $parnasLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-parnas">
                    <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_PARNAS'); ?></span>
                </a>
                <a href="<?= $shusharyLink; ?>" class="l-home-plots__all-map-build l-home-plots__all-map-build-shushary">
                    <span class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SHUSHARY'); ?></span>
                </a>
                <div class="l-home-plots__all-map-icon icon-port">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                            viewBox="0 0 80 80" class="plots-icon">
                        <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                        <path d="M53.1 46.11l-2-7a.14.14 0 0 0-.09-.08.15.15 0 0 0-.13 0l-5 5.2a.16.16 0 0 0-.05.15.14.14 0 0 0 .1.11l1.93.48a8.3 8.3 0 0 1-6.23 7.21V32.8a4.41 4.41 0 1 0-3.2 0v19.46a8.3 8.3 0 0 1-6.23-7.21l1.92-.48a.14.14 0 0 0 .11-.11.16.16 0 0 0-.05-.15l-5-5.2a.15.15 0 0 0-.13 0 .14.14 0 0 0-.09.08l-2 7a.12.12 0 0 0 0 .15.12.12 0 0 0 .15.05l2-.48a11.47 11.47 0 0 0 10.64 9.86h.64A11.47 11.47 0 0 0 51 45.83l2 .48a.12.12 0 0 0 .15-.05.12.12 0 0 0 0-.15M40 27.08a1.62 1.62 0 1 1-1.61 1.62A1.62 1.62 0 0 1 40 27.08"
                                fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                    </svg>
                    <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_SEA_PORT'); ?></div>
                </div>

                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                            viewBox="0 0 80 80" class="plots-icon">
                        <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                        <path d="M40.07,24.19a25.33,25.33,0,0,0-8.42,1.5l-.07,0-.07,0h0a5.32,5.32,0,0,0-1.64.84,2.4,2.4,0,0,0-.95,1.86V47.92a2.86,2.86,0,0,0,2.91,2.77h1.11l-3.85,3.9c-.54.54-.08,1.22.61,1.22H50.33a.73.73,0,0,0,.56-1.29L47,50.69h1.23a2.86,2.86,0,0,0,2.91-2.77V28.44a2.38,2.38,0,0,0-1-1.85,6.26,6.26,0,0,0-1.7-.88,23.52,23.52,0,0,0-8.37-1.52Zm-9.72,14.6h8.82v-7.2H30.35Zm10.26,0h9v-7.2h-9Zm-6.38,4.09A2.16,2.16,0,1,1,32.07,45,2.17,2.17,0,0,1,34.23,42.88Zm11.51,0a2.16,2.16,0,1,1-2.15,2.16A2.17,2.17,0,0,1,45.74,42.89ZM35,50.69h9.92l1.07,1h-12Zm-2.44,2.47H47.43l1.25,1.22H31.32Z"
                                fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                    </svg>
                    <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_BALT'); ?></div>
                </div>

                <div class="l-home-plots__all-map-icon icon-center-city">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                            viewBox="0 0 80 80" class="plots-icon">
                        <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                        <path d="M40 20c-8.73 0-15.81 6.67-15.81 14.91C24.19 47.75 40 62.24 40 62.24s15.81-15.32 15.81-27.33C55.81 26.67 48.73 20 40 20m-.1 23.4a7.5 7.5 0 1 1 7.5-7.5 7.5 7.5 0 0 1-7.5 7.5"
                                fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                    </svg>
                    <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_CENTER'); ?></div>
                </div>

                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                            viewBox="0 0 80 80" class="plots-icon">
                        <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                        <path d="M40.07,24.19a25.33,25.33,0,0,0-8.42,1.5l-.07,0-.07,0h0a5.32,5.32,0,0,0-1.64.84,2.4,2.4,0,0,0-.95,1.86V47.92a2.86,2.86,0,0,0,2.91,2.77h1.11l-3.85,3.9c-.54.54-.08,1.22.61,1.22H50.33a.73.73,0,0,0,.56-1.29L47,50.69h1.23a2.86,2.86,0,0,0,2.91-2.77V28.44a2.38,2.38,0,0,0-1-1.85,6.26,6.26,0,0,0-1.7-.88,23.52,23.52,0,0,0-8.37-1.52Zm-9.72,14.6h8.82v-7.2H30.35Zm10.26,0h9v-7.2h-9Zm-6.38,4.09A2.16,2.16,0,1,1,32.07,45,2.17,2.17,0,0,1,34.23,42.88Zm11.51,0a2.16,2.16,0,1,1-2.15,2.16A2.17,2.17,0,0,1,45.74,42.89ZM35,50.69h9.92l1.07,1h-12Zm-2.44,2.47H47.43l1.25,1.22H31.32Z"
                                fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                    </svg>
                    <div class="b-tooltip  b-tooltip_position_left"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_RAILWAY_LAD'); ?></div>
                </div>

                <div class="l-home-plots__all-map-icon icon-airport">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                            viewBox="0 0 80 80" class="plots-icon">
                        <circle cx="40" cy="40" r="39.5" fill="url(#plots-gradient)"  class="plots-icon-circle"/>
                        <path d="M31.94 44.77l6.8-7.41a.86.86 0 0 0-.22-1.36L26.8 29.38a1.1 1.1 0 0 1-.53-.69 1 1 0 0 1 1.14-1.09l16.69 3.27a.9.9 0 0 0 .74-.18l7.06-5.84h.05a2.37 2.37 0 0 1 1.83-.7 2.45 2.45 0 0 1 1.74.92 2.37 2.37 0 0 1-.3 3.18l-5.75 7a.92.92 0 0 0-.18.75l3.27 16.7a1 1 0 0 1-.22.79l-.18.13a.85.85 0 0 1-.74.26.93.93 0 0 1-.65-.48L44 41.54a.93.93 0 0 0-.62-.43.9.9 0 0 0-.74.22l-7.4 6.79a.9.9 0 0 0-.27.79l1 5.88a1 1 0 0 1-.26.83.94.94 0 0 1-.83.26.86.86 0 0 1-.7-.52l-3.05-6.1a.82.82 0 0 0-.39-.39l-6.32-3a.84.84 0 0 1-.48-.65.89.89 0 0 1 .26-.79l.22-.21a.84.84 0 0 1 .68-.22h.13l5.93 1a.66.66 0 0 0 .78-.22"
                                fill-rule="evenodd" fill="url(#plots-gradient)" class="plots-icon-path"/>
                    </svg>
                    <div class="b-tooltip"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SITES_AIRPORT'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
