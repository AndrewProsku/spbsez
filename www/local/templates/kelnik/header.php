<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
define("PATH_TO_404", "/404.php"); ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="cmsmagazine" content="f2d72d3408f63252b7c735ac5b026ced">
    <link type="text/plain" rel="author" href="/humans.txt">
    <meta name="format-detection" content="telephone=no">

    <title><?php $APPLICATION->ShowTitle() ?></title>
    <? $APPLICATION->ShowMeta("keywords"); ?>
    <? $APPLICATION->ShowMeta("description"); ?>
    <? $APPLICATION->ShowMeta("robots"); ?>
    <? $APPLICATION->SetAdditionalCSS("/scripts/lib/jquery-selectric/public/selectric.css"); ?>
    <? $APPLICATION->SetAdditionalCSS("/scripts/lib/magnific-popup/dist/magnific-popup.css"); ?>
    <? $APPLICATION->SetAdditionalCSS("/scripts/lib/fotorama/fotorama.css"); ?>
    <? $APPLICATION->SetAdditionalCSS("/styles/app.css"); ?>
    <?php if (CSite::InDir('/visual/')): ?>
        <? $APPLICATION->SetAdditionalCSS("/styles/apartments.css"); ?>
    <?php endif; ?>

    <link rel="shortcut icon" type="image/x-icon" href="/favicons/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/favicons/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="/favicons/apple-touch-icon-76x76-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="/favicons/apple-touch-icon-120x120-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/favicons/apple-touch-icon-152x152-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="180x180" href="/favicons/apple-touch-icon-180x180-precomposed.png">
    <link rel="icon" sizes="192x192" href="/favicons/touch-icon-192x192.png">

    <? $APPLICATION->ShowCSS(); ?>
    <link rel="stylesheet" href="/styles/print.css" media="print">

    <? if (!empty($USER) && $USER->IsAdmin()) {
        $APPLICATION->ShowHeadStrings();
    }
    ?>
</head>
<?php if (CSite::InDir('/visual/')): ?>
    <?php include('inc_header_visual.php'); ?>
<?php else: ?>
    <?php include('inc_header.php'); ?>
<?php endif; ?>
