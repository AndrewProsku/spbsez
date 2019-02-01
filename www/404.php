<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");
$APPLICATION->SetPageProperty('mainAdditionalClass', 'l-layout__content-inner');

?>

<div class="b-animation-block j-animation-block">
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
    <div class="b-animation-block__line"></div>
</div>

<div class="l-404">

    <div class="b-title b-404-title">
        <h1>Такой страницы не существует</h1>
    </div>

    <div class="b-404-desc">
        <p>
            Попробуйте начать с <a href="/" class="b-link-line">главной страницы</a> или <a href="#" class="b-link-line">посмотрите наши участки</a>
        </p>
    </div>

</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
