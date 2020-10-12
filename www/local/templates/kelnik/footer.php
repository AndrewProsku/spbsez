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
                <a href="http://www.kelnik.ru/" rel="nofollow" class="b-footer__kelnik">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="55"
                         height="16" viewBox="0 0 55 16">
                        <defs>
                            <path id="jegya" d="M249.328 7451.358a.146.146 0 0 1-.144.148h-2.04a.144.144 0 0 1-.144-.148v-15.313c0-.083.064-.15.143-.15l2.04-.005c.08 0 .145.067.145.15z"/>
                            <path id="jegyb" d="M273.44 7451.363a.146.146 0 0 1-.145.148h-1.9a.145.145 0 0 1-.145-.148v-15.317c0-.079.063-.146.146-.146h1.899c.08 0 .145.067.145.146z"/>
                            <path id="jegyc" d="M294.138 7451.363a.144.144 0 0 1-.142.148h-1.902a.145.145 0 0 1-.144-.148v-15.308c0-.088.063-.155.144-.155h1.902c.079 0 .142.067.142.155z"/>
                            <path id="jegyd" d="M289.789 7451.363a.145.145 0 0 1-.143.148h-1.901a.146.146 0 0 1-.145-.148v-11.131c0-.084.066-.152.145-.152h1.9c.08 0 .144.068.144.152z"/>
                            <path id="jegye" d="M289.789 7438.08c0 .084-.063.15-.143.15h-1.901a.147.147 0 0 1-.145-.15v-2.026a.15.15 0 0 1 .145-.154h1.9c.08 0 .144.067.144.154z"/>
                            <path id="jegyf" d="M252.587 7443.704c.88-1.038 6.272-7.438 6.412-7.597.057-.073-.011-.207-.09-.207h-2.48c-.07 0-.138.054-.2.133l-5.579 6.665v2.017l5.579 6.665c.062.078.13.133.2.133h2.665c.076 0 .146-.138.086-.207-.138-.156-5.79-6.678-6.593-7.602"/>
                            <path id="jegyg" d="M297.032 7445.645c1.12-1.378 4.16-5.21 4.298-5.367.06-.066-.012-.208-.088-.208h-2.115c-.07 0-.137.056-.201.133l-3.466 4.432v2.02l3.65 4.717c.062.081.13.135.198.135h2.298c.079 0 .148-.139.088-.206-.136-.156-1.848-2.127-4.662-5.656"/>
                            <path id="jegyh" d="M280.58 7439.78c-1.323 0-4.98 0-4.98 5.197v6.388c0 .083.064.149.143.149h1.913c.079 0 .143-.066.143-.149l-.003-6.637c0-2.99 1.97-2.969 2.726-2.969.737 0 2.724.02 2.724 2.968v6.638c0 .083.065.149.143.149h1.908c.079 0 .145-.066.145-.149v-6.388c0-5.197-3.513-5.197-4.862-5.197"/>
                            <path id="jegyi" d="M267.979 7441.312c-.896-1.042-2.075-1.532-3.63-1.532-1.602 0-2.794.499-3.698 1.569-.908 1.074-1.371 2.556-1.371 4.441 0 1.905.463 3.405 1.355 4.494.903 1.09 2.08 1.548 3.655 1.548 1.25 0 2.297-.274 3.189-1.004.874-.71 1.416-1.635 1.642-2.78l.003.003c.001-.006-.002-.01 0-.012.009-.098-.088-.098-.088-.098h-2.022c-.143.586-.429 1.126-.854 1.457-.428.326-1.106.525-1.726.525-.876 0-1.661-.32-2.143-.893-.482-.57-.842-1.471-.82-2.54h7.577c.157 0 .222-.098.232-.231a7.17 7.17 0 0 0 .027-.612c0-1.838-.443-3.284-1.328-4.335zm-6.503 3.426c.074-.953.395-1.754.846-2.265.448-.495 1.178-.788 1.963-.788.823 0 1.63.293 2.083.803.448.522.73 1.316.736 2.25z"/>
                        </defs>
                        <g>
                            <g opacity=".48" transform="translate(-247 -7436)"><use fill="#fff" xlink:href="#jegya"/></g>
                            <g opacity=".48" transform="translate(-247 -7436)"><use fill="#fff" xlink:href="#jegyb"/></g>
                            <g opacity=".48" transform="translate(-247 -7436)"><use fill="#fff" xlink:href="#jegyc"/></g>
                            <g opacity=".48" transform="translate(-247 -7436)"><use fill="#fff" xlink:href="#jegyd"/></g>
                            <g opacity=".48" transform="translate(-247 -7436)"><use fill="#fff" xlink:href="#jegye"/></g>
                            <g opacity=".48" transform="translate(-247 -7436)"><use fill="#fff" xlink:href="#jegyf"/></g>
                            <g opacity=".48" transform="translate(-247 -7436)"><use fill="#fff" xlink:href="#jegyg"/></g>
                            <g opacity=".48" transform="translate(-247 -7436)"><use fill="#fff" xlink:href="#jegyh"/></g>
                            <g opacity=".48" transform="translate(-247 -7436)"><use fill="#fff" xlink:href="#jegyi"/></g>
                        </g>
                    </svg>
                </a>
            </div>
        </div>
        <?php include 'inc_message_form.php'; ?>
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
