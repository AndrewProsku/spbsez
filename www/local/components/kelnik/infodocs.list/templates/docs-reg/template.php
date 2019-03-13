<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if(empty($arResult['ELEMENTS'])): return; endif; ?>

<div class="b-disclosure-block j-disclosure-block">
    <?php if(!empty($arResult['TYPE_NAME'])): ?>
        <div class="b-disclosure-title b-title">
            <h2><?= $arResult['TYPE_NAME']; ?></h2>
        </div>
    <?php endif; ?>

    <form action="#" class="b-disclosure-filter b-mini-filter j-disclosure-filter"  data-send="/api/info-docs/">
        <div class="b-mini-filter__group j-disclosure-select-group">
            <div class="b-mini-filter__values j-disclosure-select" data-title-default="<?= $arResult['YEAR']; ?>"><span class="j-disclosure-select-title"><?= $arResult['YEAR']; ?></span></div>
            <div class="b-mini-filter__group-wrap">
                <?php foreach ($arResult['YEARS'] as $element): ?>
                    <div class="b-mini-filter__item">
                        <input type="radio" name="disclosure-water" value="<?= $element['NAME']; ?>" data-text="<?= $element['NAME']; ?>" id="waterbranch<?= $element['NAME']; ?>"<?php if($element['SELECTED']): ?> checked<?php endif; ?> class="b-mini-filter__input">
                        <label for="waterbranch<?= $element['NAME']; ?>" class="b-mini-filter__fake"><?= $element['NAME']; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </form>

    <div class="b-profile-document__list b-profile-documents j-disclosure-content">
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
</div>
