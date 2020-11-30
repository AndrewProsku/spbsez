    </main>
    <footer class="l-home-footer b-footer">
        <div class="b-footer__left">
            <a href="<?= LANG_DIR; ?>" class="b-footer__logo"><img src="/images/home/logo-white-<?= LANGUAGE_ID; ?>.svg" alt="<?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_SEZ'); ?>"></a>
        </div>
        <div class="b-footer__center">
            <?$APPLICATION->IncludeComponent(
                "bitrix:menu",
                "bottom",
                Array(
                    "ALLOW_MULTI_SELECT" => "N",
                    "DELAY" => "N",
                    "MAX_LEVEL" => "1",
                    "MENU_CACHE_GET_VARS" => array(""),
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "ROOT_MENU_TYPE" => "bottom",
                    "USE_EXT" => "N"
                )
            );?>
            <div class="b-footer__copyright">
                <span class="b-footer__copyright-text">© <?= date('Y'); ?>, <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FOOTER_SEZ'); ?></span>
                <?php if(LANG_DIR == \SezLang::RUSSIAN_DIR): ?>
                    <a href="<?= LANG_DIR . 'about/info/'; ?>" class="b-footer__copyright-link">Раскрытие информации</a>
                    <a href="<?= LANG_DIR . 'privacy-policy/'; ?>" class="b-footer__copyright-link">Политика конфиденциальности</a>
                <?php endif; ?>
                <button class="b-footer__copyright-link j-message-button" data-href="#message">
                    <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_WRITE_MESSAGES'); ?>
                </button>
            </div>
        </div>
        <div class="b-footer__right">
            <div class="b-footer__right-wrap">
                <? $APPLICATION->IncludeComponent(
                    'kelnik:site.info',
                    'social-footer',
                    array(
                        "COMPONENT_TEMPLATE" => "social-footer",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "360000"
                    ),
                    array()

                ); ?>                
            </div>
        </div>
        <?php include 'inc_message_form.php'; ?>
        <?php include 'inc_calc_form.php'; ?>
    </footer>
    <div class="b-mobile-menu j-mobile-menu">
        <? $APPLICATION->IncludeComponent(
            "kelnik:user.menu",
            "mobile-header",
            array(
                "COMPONENT_TEMPLATE" => "mobile-header",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "360000"
            ),
            array()
        ); ?>

        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "mobile",
            array(
                "ALLOW_MULTI_SELECT" => "N",
                "DELAY" => "N",
                "MAX_LEVEL" => "2",
                "MENU_CACHE_GET_VARS" => array(),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "A",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "top",
                "USE_EXT" => "Y",
                "COMPONENT_TEMPLATE" => "mobile",
                "CHILD_MENU_TYPE" => "left"
            ),
            false
        );?>
        <div class="b-mobile-menu__footer">
            <? $APPLICATION->IncludeComponent(
                "kelnik:user.menu",
                "mobile",
                array(
                    "COMPONENT_TEMPLATE" => "mobile",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "360000"
                ),
                array()
            ); ?>
            <? $APPLICATION->IncludeComponent(
                'kelnik:site.info',
                'social-footer',
                array(
                    "COMPONENT_TEMPLATE" => "social-footer",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "360000"
                ),
                array()

            ); ?>
        </div>
    </div>
    </div>
    <div class="open-feedback-round j-message-button" data-href="#message"><div class="open-feedback"></div><div class="open-feedback__ring"></div></div>

    <? $APPLICATION->ShowHeadStrings(); ?>
    <? $APPLICATION->ShowHeadScripts(); ?>
    <script defer src="/scripts/app.js?v1"></script>
    <script defer src="/scripts/smooth-scrollbar.js"></script>
    <script defer src="/scripts/custom.js"></script>

</body>
</html>
