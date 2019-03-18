<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle(\Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_404'));
$APPLICATION->SetPageProperty('title', \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_404'));
$APPLICATION->SetPageProperty('mainAdditionalClass', 'l-layout__content-inner');

$APPLICATION->IncludeFile('inc_animation.php'); ?>

<div class="l-404">
    <div class="b-title b-404-title">
        <h1><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_404'); ?></h1>
    </div>
    <div class="b-404-desc">
        <p><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_404_TEXT', ['#MAIN_PAGE#' => LANG_DIR, '#SITE_PAGE#' => '#']); ?></p>
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
