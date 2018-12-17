<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="b-alert-old-browser is-hidden">
    <div class="b-alert-old-browser__content">
        <button class="b-alert-old-browser__close">
            <!--[if IE 8 ]>
            <img src="/images/alert-old-browser/close.png" alt="close">
            <![endif]-->
            <!--[if (gt IE 9)|!(IE)]><!-->
            <img src="/images/alert-old-browser/close.svg" alt="close">
            <!--<![endif]-->
        </button>

        <div class="b-alert-old-browser__icon">
            <!--[if IE 8 ]>
            <img src="/images/alert-old-browser/bike.png" alt="bike">
            <![endif]-->
            <!--[if (gt IE 9)|!(IE)]><!-->
            <img src="/images/alert-old-browser/bike.svg" alt="bike">
            <!--<![endif]-->
        </div>

        <h2 class="b-alert-old-browser__title">
            Упс! Сайт может работать некорректно
        </h2>

        <div class="b-alert-old-browser__text">
            <p>
                Ваш браузер устарел и не поддерживает некоторые современные технологии сайта. Чтобы работали все
                функции,
                обновите браузер или скачайте другой из проверенных:
            </p>
        </div>

        <ul class="b-alert-old-browser__browsers">
            <li class="b-alert-old-browser__browser">
                <a class="b-alert-old-browser__browser-link" href="https://browser.yandex.ru/" target="_blank">
                    <!--[if IE 8 ]>
                    <img src="/images/alert-old-browser/yandex.png" alt="yandex">
                    <![endif]-->
                    <!--[if (gt IE 9)|!(IE)]><!-->
                    <img src="/images/alert-old-browser/yandex.svg" alt="yandex">
                    <!--<![endif]-->
                    <p class="b-alert-old-browser__browser-name">Яндекс.Браузер</p>
                </a>
            </li>
            <li class="b-alert-old-browser__browser">
                <a class="b-alert-old-browser__browser-link"
                   href="https://www.google.ru/chrome/browser/desktop/index.html" target="_blank">
                    <!--[if IE 8 ]>
                    <img src="/images/alert-old-browser/chrome.png" alt="chrome">
                    <![endif]-->
                    <!--[if (gt IE 9)|!(IE)]><!-->
                    <img src="/images/alert-old-browser/chrome.svg" alt="chrome">
                    <!--<![endif]-->
                    <p class="b-alert-old-browser__browser-name">Google Chrome</p>
                </a>
            </li>
            <li class="b-alert-old-browser__browser">
                <a class="b-alert-old-browser__browser-link" href="https://www.mozilla.org/ru/firefox/new/"
                   target="_blank">
                    <!--[if IE 8 ]>
                    <img src="/images/alert-old-browser/firefox.png" alt="firefox">
                    <![endif]-->
                    <!--[if (gt IE 9)|!(IE)]><!-->
                    <img src="/images/alert-old-browser/firefox.svg" alt="firefox">
                    <!--<![endif]-->
                    <p class="b-alert-old-browser__browser-name">Mozilla Firefox</p>
                </a>
            </li>
            <li class="b-alert-old-browser__browser">
                <a class="b-alert-old-browser__browser-link" href="http://www.opera.com/ru/download" target="_blank">
                    <!--[if IE 8 ]>
                    <img src="/images/alert-old-browser/opera.png" alt="opera">
                    <![endif]-->
                    <!--[if (gt IE 9)|!(IE)]><!-->
                    <img src="/images/alert-old-browser/opera.svg" alt="opera">
                    <!--<![endif]-->
                    <p class="b-alert-old-browser__browser-name"> Opera Browser</p>
                </a>
            </li>
        </ul>

        <button class="b-alert-old-browser__ignore-button">
            Все равно перейти на сайт
        </button>
    </div>
</div>