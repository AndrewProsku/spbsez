<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if (empty($arResult['ELEMENTS'])): return; endif; ?>
<div class="l-home-our-resident l-home__block">
    <h2>Резиденты</h2>
    <div class="b-carousel">
        <div class="glide j-residents-carousel">
            <div class="glide__track b-carousel__track" data-glide-el="track">
                <ul class="glide__slides">
                    <?php foreach ($arResult['ELEMENTS'] as $element): ?>
                    <li class="glide__slide">
                        <div class="b-carousel__item ">
                            <div class="b-carousel__item-logo">
                                <img src="<?= !empty($element['IMAGE_ID_PATH']) ? $element['IMAGE_ID_PATH'] : ''; ?>"
                                     alt="<?= htmlentities($element['NAME'], ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="b-carousel__item-desc">
                                <p class="b-carousel__item-title"><?= $element['NAME']; ?></p>
                                <p class="b-carousel__item-text"><?= $element['TEXT']; ?></p>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="glide__arrows b-carousel__arrows" data-glide-el="controls">
                <button class="b-carousel__arrow b-carousel__arrow-left" data-glide-dir="<" type="button"></button>
                <button class="b-carousel__arrow b-carousel__arrow-right" data-glide-dir="&#62;" type="button"></button>
            </div>
            <div class="b-carousel__dots" data-glide-el="controls[nav]">
                <button class="b-carousel__dot" data-glide-dir="=0" type="button"></button>
                <button class="b-carousel__dot" data-glide-dir="=1" type="button"></button>
                <button class="b-carousel__dot" data-glide-dir="=2" type="button"></button>
            </div>
        </div>
    </div>
    <div class="l-home__more">
        <a href="/residents/" class="button">Все резиденты</a>
    </div>
</div>
