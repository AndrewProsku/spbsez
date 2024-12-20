<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="l-home__header-right lang-<?= LANGUAGE_ID; ?>">
    <?php if(LANG_DIR !== SezLang::CHINESE_DIR): ?>
         <div class="b-account">
            <div class="b-globalSearch__icon j-globalSearch-icon">
                <svg width="27" height="27" viewBox="0 0 27 27" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M22.0046 11.4577C22.0046 13.7798 21.2445 15.9251 19.9586 17.6605L25.5098 23.1882C26.1559 23.8315 26.1559 24.8744 25.5098 25.5177C24.8638 26.161 23.8163 26.161 23.1703 25.5177L17.6012 19.9723C15.8819 21.1956 13.7765 21.9153 11.5023 21.9153C5.70203 21.9153 1 17.2333 1 11.4577C1 5.68206 5.70203 1 11.5023 1C17.3025 1 22.0046 5.68206 22.0046 11.4577ZM11.477 19.2444C15.8097 19.2444 19.3221 15.747 19.3221 11.4327C19.3221 7.11835 15.8097 3.6209 11.477 3.6209C7.14427 3.6209 3.63191 7.11835 3.63191 11.4327C3.63191 15.747 7.14427 19.2444 11.477 19.2444Z"/>
                    <path d="M19.9586 17.6605L19.6774 17.4521L19.4973 17.6951L19.7116 17.9085L19.9586 17.6605ZM25.5098 23.1882L25.7568 22.9401L25.7568 22.9401L25.5098 23.1882ZM25.5098 25.5177L25.7568 25.7658L25.7568 25.7658L25.5098 25.5177ZM23.1703 25.5177L23.4173 25.2697L23.4173 25.2697L23.1703 25.5177ZM17.6012 19.9723L17.8481 19.7243L17.6389 19.5159L17.3983 19.6871L17.6012 19.9723ZM20.2398 17.8688C21.5688 16.0754 22.3546 13.8575 22.3546 11.4577H21.6546C21.6546 13.7021 20.9202 15.7749 19.6774 17.4521L20.2398 17.8688ZM25.7568 22.9401L20.2055 17.4125L19.7116 17.9085L25.2629 23.4362L25.7568 22.9401ZM25.7568 25.7658C26.5402 24.9857 26.5402 23.7202 25.7568 22.9401L25.2629 23.4362C25.7715 23.9427 25.7715 24.7632 25.2629 25.2697L25.7568 25.7658ZM22.9233 25.7658C23.7059 26.545 24.9742 26.545 25.7568 25.7658L25.2629 25.2697C24.7534 25.7771 23.9267 25.7771 23.4173 25.2697L22.9233 25.7658ZM17.3542 20.2203L22.9233 25.7658L23.4173 25.2697L17.8481 19.7243L17.3542 20.2203ZM11.5023 22.2653C13.8515 22.2653 16.0274 21.5216 17.8041 20.2575L17.3983 19.6871C15.7364 20.8695 13.7015 21.5653 11.5023 21.5653V22.2653ZM0.65 11.4577C0.65 17.428 5.51014 22.2653 11.5023 22.2653V21.5653C5.89393 21.5653 1.35 17.0386 1.35 11.4577H0.65ZM11.5023 0.65C5.51014 0.65 0.65 5.48735 0.65 11.4577H1.35C1.35 5.87676 5.89393 1.35 11.5023 1.35V0.65ZM22.3546 11.4577C22.3546 5.48735 17.4944 0.65 11.5023 0.65V1.35C17.1106 1.35 21.6546 5.87676 21.6546 11.4577H22.3546ZM18.9721 11.4327C18.9721 15.5523 15.6178 18.8944 11.477 18.8944V19.5944C16.0016 19.5944 19.6721 15.9417 19.6721 11.4327H18.9721ZM11.477 3.9709C15.6178 3.9709 18.9721 7.31305 18.9721 11.4327H19.6721C19.6721 6.92364 16.0016 3.2709 11.477 3.2709V3.9709ZM3.98191 11.4327C3.98191 7.31305 7.33616 3.9709 11.477 3.9709V3.2709C6.95238 3.2709 3.28191 6.92364 3.28191 11.4327H3.98191ZM11.477 18.8944C7.33616 18.8944 3.98191 15.5523 3.98191 11.4327H3.28191C3.28191 15.9417 6.95238 19.5944 11.477 19.5944V18.8944Z" />
                </svg>
            </div>
            <a href="<?= LANG_DIR; ?>cabinet/<?php if($arResult['MESSAGES']): ?>messages/<?php endif; ?>" class="b-account__link<?php if($arResult['IS_AUTHORIZED']): ?> is-auth<?php endif; ?>">
                <span class="b-account__link-icon">
                   <?php if($arResult['MESSAGES']): ?> <span class="b-account__messages"><?= $arResult['MESSAGES']; ?></span><?php endif; ?>
                </span>
                <span class="b-account__link-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_CABINET'); ?></span>
            </a>
        </div>
    <?php endif; ?>
    <? $APPLICATION->IncludeComponent(
        "kelnik:lang.menu",
        "oez",
        array(
            "COMPONENT_TEMPLATE" => "oez",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600"
        ),
        array()
    ); ?>
    <?php if(LANG_DIR !== SezLang::CHINESE_DIR): ?>
    <div class="b-burger-wrap j-burger-click">
        <div class="b-burger">
            <div class="b-burger__line"></div>
            <div class="b-burger__line"></div>
            <div class="b-burger__line"></div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="b-search__mainContainer j-global-search">

    <div class="b-search__overlay">
    </div>

    <div class="b-search__container">
        <form autocomplete="off"
              class="j-search-header">
            <div class="b-search__autocomplete-wrap">
                <div class="b-search__input-wrap">
                    <div class="b-search__close j-search-close" aria-label="закрыть поиск"></div>
                    <input type="search" name="q" class="b-search__input" placeholder="Поиск по сайту">
                    <div class="b-search__icon">
                        <svg width="27" height="27" viewBox="0 0 27 27" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M22.0046 11.4577C22.0046 13.7798 21.2445 15.9251 19.9586 17.6605L25.5098 23.1882C26.1559 23.8315 26.1559 24.8744 25.5098 25.5177C24.8638 26.161 23.8163 26.161 23.1703 25.5177L17.6012 19.9723C15.8819 21.1956 13.7765 21.9153 11.5023 21.9153C5.70203 21.9153 1 17.2333 1 11.4577C1 5.68206 5.70203 1 11.5023 1C17.3025 1 22.0046 5.68206 22.0046 11.4577ZM11.477 19.2444C15.8097 19.2444 19.3221 15.747 19.3221 11.4327C19.3221 7.11835 15.8097 3.6209 11.477 3.6209C7.14427 3.6209 3.63191 7.11835 3.63191 11.4327C3.63191 15.747 7.14427 19.2444 11.477 19.2444Z"/>
                            <path d="M19.9586 17.6605L19.6774 17.4521L19.4973 17.6951L19.7116 17.9085L19.9586 17.6605ZM25.5098 23.1882L25.7568 22.9401L25.7568 22.9401L25.5098 23.1882ZM25.5098 25.5177L25.7568 25.7658L25.7568 25.7658L25.5098 25.5177ZM23.1703 25.5177L23.4173 25.2697L23.4173 25.2697L23.1703 25.5177ZM17.6012 19.9723L17.8481 19.7243L17.6389 19.5159L17.3983 19.6871L17.6012 19.9723ZM20.2398 17.8688C21.5688 16.0754 22.3546 13.8575 22.3546 11.4577H21.6546C21.6546 13.7021 20.9202 15.7749 19.6774 17.4521L20.2398 17.8688ZM25.7568 22.9401L20.2055 17.4125L19.7116 17.9085L25.2629 23.4362L25.7568 22.9401ZM25.7568 25.7658C26.5402 24.9857 26.5402 23.7202 25.7568 22.9401L25.2629 23.4362C25.7715 23.9427 25.7715 24.7632 25.2629 25.2697L25.7568 25.7658ZM22.9233 25.7658C23.7059 26.545 24.9742 26.545 25.7568 25.7658L25.2629 25.2697C24.7534 25.7771 23.9267 25.7771 23.4173 25.2697L22.9233 25.7658ZM17.3542 20.2203L22.9233 25.7658L23.4173 25.2697L17.8481 19.7243L17.3542 20.2203ZM11.5023 22.2653C13.8515 22.2653 16.0274 21.5216 17.8041 20.2575L17.3983 19.6871C15.7364 20.8695 13.7015 21.5653 11.5023 21.5653V22.2653ZM0.65 11.4577C0.65 17.428 5.51014 22.2653 11.5023 22.2653V21.5653C5.89393 21.5653 1.35 17.0386 1.35 11.4577H0.65ZM11.5023 0.65C5.51014 0.65 0.65 5.48735 0.65 11.4577H1.35C1.35 5.87676 5.89393 1.35 11.5023 1.35V0.65ZM22.3546 11.4577C22.3546 5.48735 17.4944 0.65 11.5023 0.65V1.35C17.1106 1.35 21.6546 5.87676 21.6546 11.4577H22.3546ZM18.9721 11.4327C18.9721 15.5523 15.6178 18.8944 11.477 18.8944V19.5944C16.0016 19.5944 19.6721 15.9417 19.6721 11.4327H18.9721ZM11.477 3.9709C15.6178 3.9709 18.9721 7.31305 18.9721 11.4327H19.6721C19.6721 6.92364 16.0016 3.2709 11.477 3.2709V3.9709ZM3.98191 11.4327C3.98191 7.31305 7.33616 3.9709 11.477 3.9709V3.2709C6.95238 3.2709 3.28191 6.92364 3.28191 11.4327H3.98191ZM11.477 18.8944C7.33616 18.8944 3.98191 15.5523 3.98191 11.4327H3.28191C3.28191 15.9417 6.95238 19.5944 11.477 19.5944V18.8944Z" />
                        </svg>
                    </div>
                </div>
                <? $APPLICATION->IncludeComponent(
                    "kelnik:questions.list",
                    "questions_main",
                    array(
                        "COMPONENT_TEMPLATE" => "questions_main",
                        "YEAR" => "",
                        "SHOW_FILTER" => "Y",
                        "ELEMENTS_COUNT" => "10",
                        "CACHE_GROUPS" => "N",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600",
                        "USE_AJAX" => "N",
                        "AJAX_TYPE" => "DEFAULT",
                        "AJAX_TEMPLATE_PAGE" => "",
                        "AJAX_COMPONENT_ID" => "info-proc"
                    ),
                    false
                ); ?>
            </div>
        </form>
    </div>
</div>
