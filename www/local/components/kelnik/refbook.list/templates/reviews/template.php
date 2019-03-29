<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if (empty($arResult['ELEMENTS'])): return; endif; ?>
<div class="l-home-reviews">
    <div class="b-slider-reviews">
        <div class="glide j-reviews-carousel">
            <div class="glide__track b-carousel__track" data-glide-el="track">
                <div class="glide__slides">
                    <?php foreach ($arResult['ELEMENTS'] as $element): ?>
                    <div class="glide__slide">
                        <div class="b-slider-reviews__item">
                            <div class="b-slider-reviews__item-image"<?php if($element['IMAGE_BG_ID_PATH']): ?> style="background-image:url('<?= $element['IMAGE_BG_ID_PATH']; ?>')"<?php endif; ?>></div>
                            <div class="b-slider-reviews__person">
                                <div class="b-slider-reviews__person-img">
                                    <?php if($element['IMAGE_ID_PATH']): ?>
                                    <img src="<?= $element['IMAGE_ID_PATH']; ?>" alt="<?= htmlentities($element['NAME'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php endif; ?>
                                    <div class="b-slider-reviews__person-name-wrap">
                                        <div class="b-slider-reviews__person-name"><?= $element['NAME']; ?></div>
                                        <?php if(!empty($element['COMMENT'])): ?><p><?= $element['COMMENT']; ?></p><?php endif; ?>
                                    </div>
                                </div>
                                <div class="b-slider-reviews__person-content">
                                    <?php if(!empty($element['PREVIEW'])): ?>
                                        <div class="b-slider-reviews__person-text"><?= $element['PREVIEW']; ?></div>
                                    <?php endif; ?>
                                    <?php if(LANGUAGE_ID == 'ru'): ?>
                                        <span class="b-slider-reviews__person-link b-link-line-two j-popup-review" data-json="<?= $element['JSON']; ?>">Отзыв полностью</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="glide__arrows b-slider-reviews__arrows" data-glide-el="controls">
                <button class="b-slider-reviews__arrow b-slider-reviews__arrow-left" data-glide-dir="<" type="button">
                    <img src="/images/home/slide-arrow-white.svg" alt="стрелка влево">
                </button>
                <button class="b-slider-reviews__arrow b-slider-reviews__arrow-right" data-glide-dir="&#62;" type="button">
                    <img src="/images/home/slide-arrow-white.svg" alt="стрелка вправо">
                </button>
            </div>

            <div class="b-slider-reviews__dots" data-glide-el="controls[nav]">
                <?php $i = 0; ?>
                <?php foreach ($arResult['ELEMENTS'] as $element): ?>
                    <button class="b-slider-reviews__dot" data-glide-dir="=<?= $i++; ?>" type="button"></button>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</div>
