<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();?>

<div class="l-news-single">
    <div class="l-news-single__back"><a href="<?= $arParams['SEF_FOLDER']; ?>"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="8" height="13" viewBox="0 0 8 13"><defs><path id="prgga" d="M214.44 194.5l-.94 1.074 4.977 4.927-4.977 4.925.94 1.074 6.06-5.999z"/></defs><g><g transform="rotate(180 110.5 103.5)"><use fill="#30409a" xlink:href="#prgga"/></g></g></svg> Назад к статьям</a></div>
    <div class="l-news-single__title b-title"><h1><?= $arResult['NAME']; ?></h1></div>
    <div class="l-news-single__tags">
        <ul class="b-news-tags">
            <li class="b-news-tags__date">
                <svg id="SVGDoc" width="14" height="14" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 14 14"><defs><path d="M277.851,977.063c-0.227,0.603 -0.351,1.256 -0.351,1.937c0,3.038 2.463,5.5 5.5,5.5c3.038,0 5.5,-2.462 5.5,-5.5c0,-3.037 -2.462,-5.5 -5.5,-5.5c-0.855,0 -1.666,0.196 -2.388,0.544" id="Path-0"/><path d="M282.75,976.5v3h2.25" id="Path-1"/></defs><desc>Generated with Avocode.</desc><g transform="matrix(1,0,0,1,-276,-972)"><g><title>Group 5</title><g><title>Stroke 1</title><use xlink:href="#Path-0" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g><g><title>Stroke 3</title><use xlink:href="#Path-1" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g></g><g><title>Stroke 3</title><use xlink:href="#Path-1" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g><g><title>Stroke 1</title><use xlink:href="#Path-0" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g></g></svg>
                <?= $arResult['DATE_SHOW_FORMAT']; ?>
            </li>
        </ul>
    </div>
    <div class="l-news-single__top-text">
        <div class="b-news-single-text">
            <?= $arResult['TEXT_PREVIEW']; ?>
        </div>
    </div>
    <?php if($arResult['IMAGES']): ?>
        <div class="l-news-single__slider">
            <div class="b-news-slider">
                <div class="b-news-slider__container">

                    <div class="glide j-news-slider">
                        <div class="glide__track" data-glide-el="track">
                            <ul class="glide__slides">
                                <?php foreach ($arResult['IMAGES'] as $img): ?>
                                <li class="glide__slide">
                                    <img src="<?= $img['SRC']; ?>" class="j-glide-slide-img" alt="">
                                    <span class="glide__slide-description"><?= $img['DESCRIPTION']; ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="glide__arrows" data-glide-el="controls">
                            <button class="glide__cust-arrow glide__cust-arrow_is_left j-glide-arrow" data-glide-dir="<" type="button">
                                <img src="/images/home/slide-arrow-white.svg" alt="стрелка влево">
                            </button>
                            <button class="glide__cust-arrow glide__cust-arrow_is_right j-glide-arrow" data-glide-dir="&#62;" type="button">
                                <img src="/images/home/slide-arrow-white.svg" alt="стрелка вправо">
                            </button>
                        </div>

                        <div class="glide__cust-dots j-glide-dots" data-glide-el="controls[nav]">
                            <?php $i = 0; ?>
                            <?php foreach ($arResult['IMAGES'] as $img): ?>
                            <button class="glide__cust-dot" data-glide-dir="=<?= $i++; ?>" type="button"></button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="l-news-single__bottom-text">
        <div class="b-news-single-text b-news-single-text_theme_border">
            <?= $arResult['TEXT']; ?>
        </div>
    </div>

    <div class="l-news-single__actions">
        <div class="l-news-single__actions-wrap">
            <div class="l-news-single__button">
                <a href="<?= $arParams['SEF_FOLDER']; ?>" class="button button_reserve_icon button_theme_gray">Назад к статьям</a>
            </div>
            <div class="l-news-single__share">
                <div class="b-share">
                    <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                    <script src="//yastatic.net/share2/share.js"></script>

                    <div class="b-share__caption">Поделиться</div>
                    <div class="ya-share2" data-services="facebook,vkontakte,odnoklassniki"></div>
                </div>
            </div>

        </div>
    </div>
</div>
