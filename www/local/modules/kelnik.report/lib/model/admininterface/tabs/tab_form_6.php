<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$formBlocks = \Kelnik\Helpers\ArrayHelper::getValue($formConfig, $formNum, []);
$i = 1;
?>
<?php foreach ($formBlocks['blocks'] as $block): ?>
    <?php
        $groups = $this->getGroup($formNum, $block['type']);
        if (!isset($block['title']) || !$groups):
            continue;
        endif;

        $countGroups = count($groups);
    ?>
    <h2><?php echo 'Количество ИД блоков: <span style="color:red">' . $countGroups . '</span>'; ?></h2>
    <section class="b-report-block">        
        <div class="b-report-block__body-grouped">
            <?php foreach ($groups as $group): ?>
                <div class="b-report-block__header">
                    <h3 class="b-report-block__title"><?= '<span style="font-size:20px">' . $i . '.</span> ' . $block['title']; ?></h3>
                </div>
                <div class="b-input-group">
                    <?php foreach ($block['fields'] as $field): ?>
                        <?php $val = $this->getValue($field['id'], $group, $formNum); ?>
                        <div class="b-input-block">
                            <div class="row-header"><?= $field['title']; ?></div>
                            <div class="row-value"><?= mb_strlen($val) ? $val : '&nbsp;'; ?></div>
                            <div>
                                <input 
                                    type="text" 
                                    name="comment[<?= $this->getValue($field['id'], $group, $formNum, 'ID'); ?>]" 
                                    value="<?= $this->getValueComment($field['id'], $group, $formNum); ?>" 
                                    placeholder="Комментарий"
                                >
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php $i++; ?>
            <?php endforeach; ?>
        </div>
    </section>    
<?php endforeach; ?>
