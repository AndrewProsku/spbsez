<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
$fields = [
    'NAME', 'NAME_SEZ'
];

$fieldTitle = \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REPORT_FIELD_' . $field);
?>
<section class="b-report-block">
    <div class="b-report-block__body">
        <?php foreach ($fields as $field): ?>
            <?php
                $fieldTitle = \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REPORT_FIELD_' . $field);
                $val = htmlentities(\Kelnik\Helpers\ArrayHelper::getValue($this->data, $field), ENT_QUOTES, 'UTF-8');
            ?>
            <div class="b-input-block">
                <div class="row-header"><?= $fieldTitle; ?></div>
                <div class="row-value"><?= mb_strlen($val) ? $val : '&nbsp;'; ?></div>
                <?/*<div><input type="text" name="commentMain[<?= $field; ?>]" value="<?= $this->getComment($field); ?>" placeholder="Комментарий" size="40"></div>*/?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="b-report-block__body">
        <div class="b-input-block">
            <div class="row-header"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REPORT_FIELD_TYPE'); ?></div>
            <div class="row-value"><?= \Kelnik\Helpers\ArrayHelper::getValue($this->fields, 'TYPE.VARIANTS.' . $this->data['TYPE']) .' ' . $this->data['YEAR']; ?></div>
        </div>
        <div class="b-input-block">
            <div class="row-header"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REPORT_FIELD_STATUS'); ?></div>
            <div class="row-value"><?= \Kelnik\Helpers\ArrayHelper::getValue($this->fields, 'STATUS_ID.VARIANTS.' . $this->data['STATUS_ID']); ?></div>
        </div>
    </div>
</section>
