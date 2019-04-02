<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();?>
<?php if(!empty($arResult['PREV_YEAR_REQUIRED'])): ?>
    <div class="l-reports">
        <div class="b-title b-reports-title">
            <h1>Не закрыт предыдущий отчетный пероид</h1>
        </div>
        <div class="b-reports-empty">
            В данный момент вы не можете заполнить текущий отчет, т.к. имеется незавершенный отчет предшествующего пероида.<br><br>
            Вернуться к <a class="b-link-line" href="<?= $this->__component->getParent()->arParams['SEF_FOLDER']; ?>">списку отчетов</a> резидента.
        </div>
    </div>
    <?php return; ?>
<?php endif; ?>
<?php if(!empty($arResult['IS_LOCKED'])): ?>
    <div class="l-reports">
        <div class="b-title b-reports-title">
            <h1>Данный отчет уже редактируется</h1>
        </div>
        <div class="b-reports-empty">
            В данный момент отчет редактируется другим резидентом.<br>
            Отчет, возможно, будет доступен для редактирования через <?= $arResult['TIME_LEFT']; ?>.<br><br>
            Вернуться к <a class="b-link-line" href="<?= $this->__component->getParent()->arParams['SEF_FOLDER']; ?>">списку отчетов</a> резидента.
        </div>
    </div>
    <?php return; ?>
<?php endif; ?>
<div class="l-report-form">
    <div class="b-title b-reports-title">
        <h1>Отчет&nbsp;о&nbsp;деятельности резидента</h1>
    </div>

    <h2 class="b-reports-subtitle"><?= $arResult['REPORT']['TYPE_NAME']; ?>, <?= $arResult['REPORT']['YEAR']; ?></h2>

    <div class="b-inputs-row b-report-oez">
        <div class="b-input-block j-report-resident-name">
            <input id="resident-name" class="b-input-text" type="text" name="residentName" maxlength="" autocomplete="" value="" placeholder="">

            <label class="b-input-block__label" for="resident-name">Наименование резидента ОЭЗ</label>
        </div>
        <div class="b-input-block j-report-oez-name">
            <input id="oez-name" class="b-input-text" type="text" name="oezName" maxlength="" autocomplete="" value="" placeholder="">

            <label class="b-input-block__label" for="resident-name">Наименование ОЭЗ</label>
        </div>
    </div>

    <?php include 'tabs.php'; ?>

    <div class="b-report-comments"></div>
    <div class="b-report-form j-report-form"
         data-current-form="0"
         data-report-id="<?= $arResult['REPORT']['ID']; ?>"
        <?php if(empty($arResult['EDITABLE'])): ?> data-read-only="true"<?php endif; ?>>
        <div>

        </div>
    </div>

    <?php include 'tabs.php'; ?>

    <button class="button button_without_icon b-report-submit j-report-submit" type="button" disabled="">Отправить отчет</button>
</div>
