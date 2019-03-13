<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if(empty($arResult['ELEMENTS'])): return; endif; ?>

<div class="b-profile-document__list b-profile-documents">
    <?php foreach ($arResult['ELEMENTS'] as $element): ?>
        <div class="b-profile-document__item b-profile-document__item_ext_<?= $element['FILE_ID']['EXT']; ?>">
            <div class="b-profile-document__item-title">
                <a href="<?= $element['FILE_ID']['SRC']; ?>" class="b-profile-document__item-title-link b-link-line"><?= $element['NAME']; ?></a>
                <span class="b-profile-document__item-title-weight"><?= $element['FILE_ID']['FILE_SIZE_FORMAT']; ?></span>
            </div>
            <div class="b-profile-document__item-desc">
                <time datetime="<?= $element['DATE_SHOW_FORMAT']; ?>" class="b-profile-document__item-date"><?= $element['DATE_SHOW_FORMAT_HUMAN']; ?></time>
            </div>
        </div>
    <?php endforeach; ?>
</div>
