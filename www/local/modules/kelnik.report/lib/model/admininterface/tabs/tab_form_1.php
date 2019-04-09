<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$formBlocks = \Kelnik\Helpers\ArrayHelper::getValue($formConfig, $formNum, []);
$i = 1;
?>
<?php foreach ($formBlocks['blocks'] as $block): ?>
    <?php
        if (!isset($block['title'])):
            continue;
        endif;
        $addCss = !empty($block['type']) && $block['type'] == 'taxes'
                    ? ' taxes'
                    : '';
    ?>
    <section class="b-report-block">
        <div class="b-report-block__header" style="height: 36px">
            <h3 class="b-report-block__title"><?= $i . '. ' . $block['title']; ?></h3>
        </div>
        <div class="b-report-block__body">
        <?php if ($addCss): ?>
            <?php
                $total = [0, 0];
                foreach ($block['fields'] as $field) {
                    $key = false !== strpos($field['id'], 'all') ? 0 : 1;
                    $total[$key] += (float) $this->getValue($field['id'], 0, $formNum);
                }
            ?>
            <div class="b-input-block<?= $addCss; ?>">
                <div class="row-header"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REPORT_FORM_1_TOTAL_1'); ?></div>
                <div class="row-value"><?= $total[0]; ?></div>
            </div>
            <div class="b-input-block<?= $addCss; ?>">
                <div class="row-header"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REPORT_FORM_1_TOTAL_2'); ?></div>
                <div class="row-value"><?= $total[1]; ?></div>
            </div>
        <?php endif; ?>
        <?php foreach ($block['fields'] as $field): ?>
            <?php
                if (!isset($field['id']) || !empty($field['excludeAdmin'])):
                    continue;
                endif;
                $val = $this->getValue($field['id'], 0, $formNum);
            ?>
            <div class="b-input-block<?= $addCss; ?>">
                <div class="row-header"><?= $field['title']; ?></div>
                <div class="row-value"><?= $val ? $val : '&nbsp;'; ?></div>
                <div><input type="text" name="comment[<?= $this->getValue($field['id'], 0, $formNum, 'ID'); ?>]" value="<?= $this->getValueComment($field['id'], 0, $formNum); ?>" placeholder="Комментарий"></div>
            </div>
        <?php endforeach; ?>
        </div>
    </section>
    <?php $i++; ?>
<?php endforeach; ?>
