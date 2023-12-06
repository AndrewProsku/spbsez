<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php if (empty($arResult['ELEMENTS'])): ?>
    <div class="b-vacancies-empty" style="display: block">
        В настоящий момент открытых вакансий нет
    </div>
    <?php return; ?>
<?php endif; ?>

<div class="b-vacancies-list">
    <?php foreach ($arResult['ELEMENTS'] as $row): ?>
        <div class="b-vacancy j-vacancy">
            <h2 class="b-vacancy__header"><?= $row['NAME']; ?></h2>
            <div class="b-vacancy__content-wrapper">
                <div class="b-vacancy__content">
                    <div class="b-vacancy__salary">
                        <?php if($row['PRICE_MIN'] && $row['PRICE_MAX']): ?>
                            <?= $row['PRICE_MIN']; ?> - <?= $row['PRICE_MAX']; ?> ₽
                        <?php elseif($row['PRICE_MIN']): ?>
                            от <?= $row['PRICE_MIN']; ?> ₽
                        <?php elseif($row['PRICE_MAX']): ?>
                            до <?= $row['PRICE_MAX']; ?> ₽
                        <?php elseif($row['PRICE_TEXT']): ?>
                            <?= $row['PRICE_TEXT']; ?>
                        <?php endif; ?>
                    </div>
                    <div class="b-vacancy__characteristics">
                        <?php if($row['EXPERIENCE']): ?>
                            <div class="b-vacancy__characteristic">
                                <div class="b-vacancy__characteristic-name">Опыт работы</div>
                                <div class="b-vacancy__characteristic-value"><?= $row['EXPERIENCE']; ?></div>
                            </div>
                        <?php endif; ?>
                        <?php if($row['EMPLOYMENT']): ?>
                            <div class="b-vacancy__characteristic">
                                <div class="b-vacancy__characteristic-name">Занятость</div>
                                <div class="b-vacancy__characteristic-value"><?= $row['EMPLOYMENT']; ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="b-vacancy__characteristics">
                        <?php if($row['CONTACTS']): ?>
                            <div class="b-vacancy__characteristic">
                                <div class="b-vacancy__characteristic-name">Контактная информация</div>
                                <div class="b-vacancy__characteristic-value"><?= $row['CONTACTS']; ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if(!empty($row['DESCR'])): ?>
                    <div class="b-vacancy-description">
                        <?= $row['DESCR']; ?>
                    </div>
                    <?php endif; ?>
                    <?php
                        $blocks = [
                            'DUTIES' => 'Обязанности',
                            'REQUIREMENTS' => 'Требования',
                            'CONDITIONS' => 'Условия'
                        ];
                    ?>
                    <?php foreach ($blocks as $blockKey => $blockName): ?>
                        <?php if (empty($row[$blockKey])): continue; endif; ?>
                        <h3 class="b-vacancy__subtitle"><?= $blockName; ?></h3>
                        <div class="b-vacancy__description"><?= $row[$blockKey]; ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
