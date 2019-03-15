<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
define("PATH_TO_404", '/404.php'); ?>
<!doctype html>
<html lang="<?= LANGUAGE_ID; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?php $APPLICATION->ShowTitle() ?></title>
    <?php
        $APPLICATION->SetAdditionalCSS("/styles/app.css");
        $isRegularPage = !in_array($APPLICATION->GetCurDir(), [LANG_DIR]) || defined('ERROR_404');
        $showAnimation = $APPLICATION->GetProperty('showAnimation') === true || defined('ERROR_404');
    ?>

    <meta name="theme-color" content="#000000">
    <link rel="shortcut icon" type="image/x-icon" href="/favicons/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="32x32" href="/favicons/apple-touch-icon-32x32.png">
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/favicons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="/favicons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="/favicons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/favicons/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon-precomposed" sizes="180x180" href="/favicons/apple-touch-icon-180x180.png">
    <link rel="icon" sizes="192x192" href="/favicons/touch-icon-192x192.png">

    <?php
        $APPLICATION->ShowHead();
        \Bitrix\Main\Localization\Loc::loadMessages(__DIR__ . DIRECTORY_SEPARATOR . 'kelnik.php');
    ?>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(52811566, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/52811566" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-136324578-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-136324578-1');
    </script>

</head>
<body>
    <div id="panel"><?$APPLICATION->ShowPanel();?></div>
    <?php include 'inc_notify_old_browser.php'; ?>
    <div class="l-layout">
        <header class="l-home__header<?php if($isRegularPage): ?> inner-header<?php endif; ?> j-home__header">
            <div class="l-home__header-left">
                <a href="<?= LANG_DIR; ?>" class="b-logo__link lang-<?=LANGUAGE_ID; ?>" title="<?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_BACK_TO_MAIN'); ?>"></a>
            </div>
            <div class="l-home__header-center">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "top",
                    Array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(""),
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "top",
                        "USE_EXT" => "N"
                    )
                );?>
            </div>
            <? $APPLICATION->IncludeComponent(
                "kelnik:user.menu",
                "header",
                array(
                    "COMPONENT_TEMPLATE" => "header",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "360000"
                ),
                array()
            ); ?>
        </header>
        <main class="l-layout__content<? if($isRegularPage): ?> l-layout__content-inner<?php endif; ?>">
            <?php if($showAnimation): include 'inc_animation.php'; endif; ?>
