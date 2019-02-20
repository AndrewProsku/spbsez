<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if(empty($arResult['ELEMENTS'])): return; endif; ?>

<div class="b-presentations__info">
    <div class="b-presentations__title">Презентации</div>
    <div class="b-presentations__files">
        <div class="b-files-list ">
            <?php foreach ($arResult['ELEMENTS'] as $element): ?>
                <div class="b-files-list__item">
                    <div class="b-files-list__left">
                        <span class="b-files-list__icon"><?= $element['FILE_ID']['EXT']; ?></span>
                        <div class="b-files-list__name-wrap">
                            <a href="<?= $element['FILE_ID']['SRC']; ?>" class="b-files-list__name b-link-line"><?= $element['NAME']; ?></a>
                        </div>
                        <div class="b-files-list__size"><?= $element['FILE_ID']['FILE_SIZE_FORMAT']; ?></div>
                    </div>
                    <div class="b-files-list__right">
                        <div class="b-files-list__size b-files-list__size-adapt"><?= $element['FILE_ID']['FILE_SIZE_FORMAT']; ?></div>
                        <div class="b-files-list__date"><?= $element['FILE_ID']['DATE_FORMAT']; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>