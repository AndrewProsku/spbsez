<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$formBlocks = \Kelnik\Helpers\ArrayHelper::getValue($formConfig, \Kelnik\Report\Model\ReportFieldsTable::FORM_TAXES, []);
$i = 1;
foreach ($formBlocks['blocks'] as $block) {
    if (!isset($block['title'])) {
        continue;
    }
    ?>
    <section class="b-report-block">
        <div class="b-report-block__header" style="height: 36px">
            <h3 class="b-report-block__title"><?= $i . '. ' . $block['title']; ?></h3>
        </div>
        <div class="b-report-block__body">
        <?php
        foreach ($block['fields'] as $field) {
            if (!isset($field['id']) || !empty($field['exclude'])) {
                continue;
            }
            ?>
            <div class="b-input-block">
                <div class="row-header"><?= $field['title']; ?></div>
                <div class="row-value"><?= $this->getValue($field['id']); ?></div>
                <div><input type="text" name="comment[<?= \Kelnik\Report\Model\ReportFieldsTable::FORM_TAXES; ?>][0][<?= $field['id']; ?>]" value="<?= $this->getComment($field['id']); ?>" placeholder="Комментарий"></div>
            </div>
            <?php
        }
        ?>
        </div>
    </section>
    <?php
    $i++;
}
