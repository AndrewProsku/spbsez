<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if(empty($arResult['ELEMENTS'])): return; endif; ?>

<div class="b-disclosure-block  j-disclosure-block  j-disclosure-procurement">
    <div class="b-disclosure-title b-title">
        <h2>Информация о закупках</h2>
    </div>
    <form action="#" class="b-disclosure-filter b-mini-filter j-disclosure-filter" data-send="/api/infoProc/">
        <input type="hidden" name="compid" value="info-proc">
        <div class="b-mini-filter__group j-disclosure-select-group">
            <div class="b-mini-filter__values j-disclosure-select" data-title-default="<?= $arResult['YEAR']; ?>"><span class="j-disclosure-select-title"><?= $arResult['YEAR']; ?></span></div>
            <div class="b-mini-filter__group-wrap">
                <?php foreach ($arResult['YEARS'] as $element): ?>
                    <div class="b-mini-filter__item">
                        <input type="radio" name="year" value="<?= $element['NAME']; ?>" data-text="<?= $element['NAME']; ?>" id="procurement<?= $element['NAME']; ?>"<?php if($element['SELECTED']): ?> checked<?php endif; ?> class="b-mini-filter__input">
                        <label for="procurement<?= $element['NAME']; ?>" class="b-mini-filter__fake"><?= $element['NAME']; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </form>

    <div class="b-profile-document__list j-disclosure-content">
        <?php foreach ($arResult['ELEMENTS'] as $element): ?>
            <div class="b-disclosure-content-item j-disclosure-content-item">
                <div class="b-disclosure-content-item__title">
                    <a href="<?= $element['LINK']; ?>" target="_blank" class="b-link-line"><?= $element['NAME']; ?></a>
                </div>
                <div class="b-profile-document__item-desc">
                    <time datetime="<?= $element['DATE_SHOW_FORMAT']; ?>" class="b-profile-document__item-date"><?= $element['DATE_SHOW_FORMAT_HUMAN']; ?></time>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="button"
            class="b-disclosure-more button-add j-disclosure-load-more"
            data-send="/api/infoProc/"
            data-ajax-param="compid"
            data-ajax-param-value="info-proc"
            <?php if(!$arResult['MORE']): ?> style="display: none"<?php endif; ?>>Загрузить еще закупки</button>
</div>
