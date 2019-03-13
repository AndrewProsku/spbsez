<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php if($arResult['DISABLED']): ?>
    <div class="b-title b-reports-title">
        <h1>Отчетный период не&nbsp;наступил</h1>
    </div>
    <div class="b-reports-empty">
        Резидент заполняет отчеты 4 раза в&nbsp;год.
        Пришлем уведомление в&nbsp;«<a class="b-link-line" href="<?= LANG_DIR . 'cabinet/messages/'; ?>">Сообщения от&nbsp;ОЭЗ</a>», когда наступит отчетный период.
    </div>
    <?php return; ?>
<?php endif; ?>

<div class="b-title b-reports-title">
    <h1>Отчет&nbsp;о&nbsp;деятельности резидента</h1>
</div>
<?php
    $hasArchive = false;
?>
<?php foreach ($arResult['REPORTS'] as $year): ?>
    <?php
        if($year['IS_COMPLETE']) {
            $hasArchive = true;
            continue;
        }
    ?>
    <h2 class="b-reports-subtitle">Отчеты за&nbsp;<?= $year['NAME']; ?> год</h2>

    <div class="b-quarters">
        <?php foreach ($year['ELEMENTS'] as $report): ?>
            <section class="b-quarter <?= $report['STATUS_CSS_CLASS']; ?>">
                <h3 class="b-quarter__title"><?= $report['TYPE_NAME']; ?></h3>
                <div class="b-quarter__label"><?= $report['STATUS_NAME']; ?></div>
                <a class="button b-quarter__button button_icon_pen" href="<?= $report['LINK']; ?>"><?= $report['STATUS_BUTTON_NAME']; ?></a>
            </section>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<?php if(!$hasArchive): return; endif; ?>

<h2 class="b-reports-subtitle">Архив очетов</h2>

<?php foreach ($arResult['REPORTS'] as $year): ?>
    <?php if(!$year['IS_COMPLETE']): continue; endif; ?>
    <h3 class="b-reports-year"><?= $year['NAME']; ?></h3>
    <div class="b-quarters">
        <?php foreach ($year['ELEMENTS'] as $report): ?>
            <section class="b-quarter <?= $report['STATUS_CSS_CLASS']; ?>">
                <h3 class="b-quarter__title"><?= $report['TYPE_NAME']; ?></h3>
                <div class="b-quarter__label"><?= $report['STATUS_NAME']; ?></div>
                <a class="button b-quarter__button button_icon_pen" href="<?= $report['LINK']; ?>"><?= $report['STATUS_BUTTON_NAME']; ?></a>
            </section>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
