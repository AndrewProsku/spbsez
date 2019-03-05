<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php if($arResult['DISABLED']): ?>
    <div class="b-title b-reports-title">
        <h1>Отчетный период не&nbsp;наступил</h1>
    </div>
    <div class="b-reports-empty">
        Резидент заполняет отчеты 4 раза в&nbsp;год.
        Следующий отчетный период во&nbsp;2-м квартале 2019 года.
        Пришлем уведомление в&nbsp;«<a class="b-link-line" href="<?= LANG_DIR . 'cabinet/messages/'; ?>">Сообщения от&nbsp;ОЭЗ</a>», когда наступит отчетный период.
    </div>
    <?php return; ?>
<?php endif; ?>

<div class="b-title b-reports-title">
    <h1>Отчет&nbsp;о&nbsp;деятельности резидента</h1>
</div>

<h2 class="b-reports-subtitle">Отчеты за&nbsp;2018 год</h2>

<div class="b-quarters">
    <section class="b-quarter b-quarter_status_to-fill">
        <h3 class="b-quarter__title">1 квартал</h3>
        <div class="b-quarter__label">Нужно заполнить</div>
        <a class="button b-quarter__button button_icon_pen" href="<?= $arParams['SEF_FOLDER']; ?>1/">Заполнить</a>
    </section>
    <section class="b-quarter b-quarter_status_check">
        <h3 class="b-quarter__title">1 квартал</h3>
        <div class="b-quarter__label">На&nbsp;проверке</div>
        <a class="button b-quarter__button button_icon_pen" href="">Заполнить</a>
    </section>
    <section class="b-quarter b-quarter_status_approved">
        <h3 class="b-quarter__title">2 квартал</h3>
        <div class="b-quarter__label">Принят</div>
        <a class="button b-quarter__button" href="#">Посмотреть</a>
    </section>
    <section class="b-quarter b-quarter_status_rejected">
        <h3 class="b-quarter__title">3 квартал</h3>
        <div class="b-quarter__label">Отклонен</div>
        <a class="button b-quarter__button" href="#">Редактировать</a>
    </section>
    <section class="b-quarter b-quarter_status_preliminary">
        <h3 class="b-quarter__title">Предварительный годовой отчет</h3>
        <div class="b-quarter__description">Отчетный период не&nbsp;наступил</div>
    </section>
    <section class="b-quarter b-quarter_status_disabled">
        <h3 class="b-quarter__title">Годовой отчет</h3>
        <div class="b-quarter__description">Отчетный период не&nbsp;наступил</div>
    </section>
</div>

<h2 class="b-reports-subtitle">Архив очетов</h2>
<h3 class="b-reports-year">2017</h3>
<div class="b-quarters">
    <section class="b-quarter b-quarter_status_approved">
        <h3 class="b-quarter__title">1 квартал</h3>
        <div class="b-quarter__label">Принят</div>
        <a class="button b-quarter__button" href="#">Посмотреть</a>
    </section>
    <section class="b-quarter b-quarter_status_approved">
        <h3 class="b-quarter__title">2 квартал</h3>
        <div class="b-quarter__label">Принят</div>
        <a class="button b-quarter__button" href="#">Посмотреть</a>
    </section>
    <section class="b-quarter b-quarter_status_approved">
        <h3 class="b-quarter__title">3 квартал</h3>
        <div class="b-quarter__label">Принят</div>
        <a class="button b-quarter__button" href="#">Посмотреть</a>
    </section>
    <section class="b-quarter b-quarter_status_approved">
        <h3 class="b-quarter__title">Годовой отчет</h3>
        <div class="b-quarter__label">Принят</div>
        <a class="button b-quarter__button" href="#">Посмотреть</a>
    </section>
</div>
<h3 class="b-reports-year">2016</h3>
<div class="b-quarters">
    <section class="b-quarter b-quarter_status_approved">
        <h3 class="b-quarter__title">1 квартал</h3>
        <div class="b-quarter__label">Принят</div>
        <a class="button b-quarter__button" href="#">Посмотреть</a>
    </section>
    <section class="b-quarter b-quarter_status_approved">
        <h3 class="b-quarter__title">2 квартал</h3>
        <div class="b-quarter__label">Принят</div>
        <a class="button b-quarter__button" href="#">Посмотреть</a>
    </section>
    <section class="b-quarter b-quarter_status_approved">
        <h3 class="b-quarter__title">3 квартал</h3>
        <div class="b-quarter__label">Принят</div>
        <a class="button b-quarter__button" href="#">Посмотреть</a>
    </section>
    <section class="b-quarter b-quarter_status_approved">
        <h3 class="b-quarter__title">Годовой отчет</h3>
        <div class="b-quarter__label">Принят</div>
        <a class="button b-quarter__button" href="#">Посмотреть</a>
    </section>
</div>
