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
            <?php $fieldTitle = \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REPORT_FIELD_' . $field); ?>
            <div class="b-input-block">
                <div class="row-header"><?= $fieldTitle; ?></div>
                <div class="row-value"><?= htmlentities(\Kelnik\Helpers\ArrayHelper::getValue($this->data, $field), ENT_QUOTES, 'UTF-8'); ?></div>
                <div><input type="text" name="commentMain[<?= $field; ?>]" value="<?= $this->getComment($field); ?>" placeholder="Комментарий" size="40"></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
