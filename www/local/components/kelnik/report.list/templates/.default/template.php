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

<?php if($arResult['ERROR']): ?>
    <div class="b-title b-reports-title">
        <h1>Ошибка сервиса</h1>
    </div>
    <div class="b-reports-empty">
        <?= $arResult['ERROR_MSG']; ?>
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
        if($year['IS_COMPLETE'] && $year['NAME'] < date('Y')) {
            $hasArchive = true;
            continue;
        }
    ?>
    <h2 class="b-reports-subtitle">Отчеты за&nbsp;<?= $year['NAME']; ?> год</h2>

    <div class="b-quarters">
        <?php foreach ($year['ELEMENTS'] as $report): ?>
            <section class="b-quarter <?= $report['STATUS_CSS_CLASS']; ?>">
                <h3 class="b-quarter__title"><?= $report['TYPE_NAME']; ?></h3>
                <?php if($report['STATUS_ID'] < 0): ?>
                    <div class="b-quarter__description">Отчетный период не&nbsp;наступил</div>
                <?php else: ?>
                    <div class="b-quarter__label"><?= $report['STATUS_NAME']; ?></div>                    
                    <a class="button b-quarter__button button_icon_pen" href="<?= $report['LINK']; ?>"><?= $report['STATUS_BUTTON_NAME']; ?></a>
                    <?php if ($report['ID'] > 0) { ?>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="resident" value="<?php echo $report['COMPANY_ID']?>">
                            <input type="hidden" name="period" value="<?php echo $report['YEAR'] . '_' . $report['TYPE']?>">
                            <input type="submit" name="export_report" value="Экспорт в xls" class="export_report">                    
                        </form>
                    <?php } ?>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<?php if(!$hasArchive): return; endif; ?>

<h2 class="b-reports-subtitle">Архив очетов</h2>

<?php foreach ($arResult['REPORTS'] as $year): ?>
    <?php if(!$year['IS_COMPLETE'] || $year['NAME'] == date('Y')): continue; endif; ?>
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
