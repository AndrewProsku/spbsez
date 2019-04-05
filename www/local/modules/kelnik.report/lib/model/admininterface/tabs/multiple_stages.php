<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$groups = $this->getGroup($formNum, $block['multiple']['name']);
if (!$groups) {
    return '';
}
?>
<section class="b-report-block">
    <div class="b-report-block__header" style="height: 36px">
        <h3 class="b-report-block__title"><?= $i . '. ' . $block['title']; ?></h3>
    </div>
    <div class="b-report-block__body-grouped">
        <?php foreach ($groups as $group): ?>
            <?php $isExtra = true; ?>
            <div class="b-input-group">
                <?php foreach ($block['multiple']['fields'] as $field): ?>
                    <?php
                        if (!$isExtra) {
                            continue;
                        }
                        $val = $this->getValue($field['id'], $group);

                        if ($field['id'] == 'construction-stage') {
                            $isExtra = \Kelnik\Helpers\ArrayHelper::getValue($stages, $val . '.extra', false);
                            $val = \Kelnik\Helpers\ArrayHelper::getValue($stages, $val . '.name');
                        }

                        // Если поле - файл
                        if ($field['id'] === \Kelnik\Report\Model\ReportFieldsTable::FIELD_CONSTRUCTION_FILE
                            && $val = \CFile::GetFileArray($val)
                        ) {
                            $val = '<a href="' . $val['SRC'] . '" target="_blank">' . $val['ORIGINAL_NAME'] . '</a>';
                        }
                    ?>
                    <div class="b-input-block">
                        <div class="row-header"><?= $field['title']; ?></div>
                        <div class="row-value"><?= $val ? $val : '&nbsp;'; ?></div>
                        <div><input type="text" name="comment[<?= $this->getValue($field['id'], $group, 'ID'); ?>]" value="<?= $this->getValueComment($field['id'], $group); ?>" placeholder="Комментарий"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
