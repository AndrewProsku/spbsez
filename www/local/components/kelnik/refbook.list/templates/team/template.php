<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if(empty($arResult['ELEMENTS'])): return; endif; ?>

<section class="b-team">
    <div class="b-team__title b-title">
        <h2><?= $arResult['HEADER']; ?></h2>
    </div>
    <div class="b-team__wrapper">
        <div id="company-team"></div>
        <?php foreach ($arResult['ELEMENTS'] as $element): ?>
            <div class="b-team__item">
                <?php if(!empty($element['IMAGE_ID_PATH'])): ?>
                    <img src="<?= $element['IMAGE_ID_PATH']; ?>"
                         alt="<?= htmlentities($element['NAME'], ENT_QUOTES, 'UTF-8'); ?>"
                         class="b-team__photo">
                <?php endif; ?>
                <h3 class="b-team__name"><?= $element['NAME']; ?></h3>
                <div class="b-team__description"><?= $element['TEXT']; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
