<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$formBlocks = \Kelnik\Helpers\ArrayHelper::getValue($formConfig, $formNum, []);
$i = 1;
?>
<?php foreach ($formBlocks['blocks'] as $block): ?>
    <?php if (!isset($block['title'])): continue; endif; ?>
    <?php if (!empty($block['multiple'])): ?>
        <?php
            $type = $block['multiple']['name'] == 'innovations'
                    ? 'groups'
                    : $block['multiple']['name'];
            $type = 'multiple_' . $type . '.php';
            if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $type)) {
                include $type;
                $i++;
            }
            continue;
        ?>
    <?php endif; ?>
    <section class="b-report-block">
        <div class="b-report-block__header" style="height: 36px">
            <h3 class="b-report-block__title"><?= $i . '. ' . $block['title']; ?></h3>
        </div>
        <div class="b-report-block__body">

        <?php foreach ($block['fields'] as $field): ?>
            <?php
            if (!isset($field['id']) || !empty($field['excludeAdmin'])):
                continue;
            endif;

            $val = $this->getValue($field['id'], 0, $formNum);

            if (!empty($field['type']) && $field['type'] == 'boolean') {
                $val = $val == 'yes' ? 'Да' : 'Нет';
            }

            if ($field['id'] == 'project-okved') {
                $val = \Kelnik\Helpers\ArrayHelper::getValue($okvedSections, $val);
            }
            if ($field['id'] == 'project-group-okved') {
                $val = \Kelnik\Helpers\ArrayHelper::getValue($okvedGroups, $val);
            }
            if ($field['id'] == 'project-code-okved') {
                $val = \Kelnik\Helpers\ArrayHelper::getValue($okvedCodes, $val);
            }
            if ($field['id'] == 'project-area') {
                $val = \Kelnik\Helpers\ArrayHelper::getValue($projectAreas, $val);
            }
            if ($field['id'] == 'project-measure') {
                $val = \Kelnik\Report\Model\ReportFieldsTable::normalizeFloat($val);
            }

            ?>
        <?php if($field['extra-title']) :?>
                <div class="b-report-block__header__extra" style="height: 36px;flex: 0 0 100%;">
                    <h3 class="b-report-block__title__extra"><?=$field['title']?></h3>
                </div>
            <?php continue; ?>
        <?endif; ?>

            <div class="b-input-block">
                <div class="row-header"><?= $field['title']; ?></div>
                <div class="row-value"><?= mb_strlen($val) ? $val : '&nbsp;'; ?></div>
                <div><input type="text" name="comment[<?= $this->getValue($field['id'], 0, $formNum, 'ID'); ?>]" value="<?= $this->getValueComment($field['id'], 0, $formNum); ?>" placeholder="Комментарий"></div>
            </div>
        <?php endforeach; ?>
        </div>
    </section>
    <?php $i++; ?>
<?php endforeach; ?>
