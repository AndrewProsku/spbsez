<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if (empty($arResult['ELEMENTS'])): return; endif; ?>

<form action="#" class="b-news__filter b-mini-filter j-news-filter">
    <div class="b-mini-filter__group j-news-select-group">
        <div class="b-mini-filter__values j-news-select" data-title-default="Все"><span
                    class="j-news-select-title">Все</span></div>

        <div class="b-mini-filter__group-wrap">
            <div class="b-mini-filter__item">
                <input type="checkbox" name="years[]" value="2018" data-text="2018" id="years2018"
                       class="b-mini-filter__input">
                <label for="years2018" class="b-mini-filter__fake">2018</label>
            </div>

            <div class="b-mini-filter__item">
                <input type="checkbox" name="years[]" value="2017" data-text="2017" id="years2017"
                       class="b-mini-filter__input">
                <label for="years2017" class="b-mini-filter__fake">2017</label>
            </div>

            <div class="b-mini-filter__item">
                <input type="checkbox" name="years[]" value="2016" data-text="2016" id="years2016"
                       class="b-mini-filter__input">
                <label for="years2016" class="b-mini-filter__fake">2016</label>
            </div>

            <div class="b-mini-filter__item">
                <input type="checkbox" name="years[]" value="2015" data-text="2015" id="years2015"
                       class="b-mini-filter__input">
                <label for="years2015" class="b-mini-filter__fake">2015</label>
            </div>
        </div>
    </div>

    <div class="b-mini-filter__group j-news-select-group">

        <div class="b-mini-filter__values j-news-select" data-title-default="Все"><span
                    class="j-news-select-title">Все</span></div>

        <div class="b-mini-filter__group-wrap">

            <div class="b-mini-filter__item">
                <input type="checkbox" name="types[]" value="anons" data-text="Анонс" id="typesAnons"
                       class="b-mini-filter__input">
                <label for="typesAnons" class="b-mini-filter__fake">Анонс</label>
            </div>

            <div class="b-mini-filter__item">
                <input type="checkbox" name="types[]" value="press" data-text="Пресс-релиз" id="typesPress"
                       class="b-mini-filter__input">
                <label for="typesPress" class="b-mini-filter__fake">Пресс-релиз</label>
            </div>

            <div class="b-mini-filter__item">
                <input type="checkbox" name="types[]" value="resid" data-text="Новости резидентов"
                       id="typesResid" class="b-mini-filter__input">
                <label for="typesResid" class="b-mini-filter__fake">Новости резидентов</label>
            </div>

            <div class="b-mini-filter__item">
                <input type="checkbox" name="types[]" value="news" data-text="Новости" id="typesNews"
                       class="b-mini-filter__input">
                <label for="typesNews" class="b-mini-filter__fake">Новости</label>
            </div>

            <div class="b-mini-filter__item">
                <input type="checkbox" name="types[]" value="about" data-text="СМИ о нас" id="typesAbout"
                       class="b-mini-filter__input">
                <label for="typesAbout" class="b-mini-filter__fake">СМИ о нас</label>
            </div>
        </div>

    </div>
</form>

<div class="b-news__content j-news-container">
    <?php foreach($arResult['ELEMENTS'] as $arItem): ?>
        <div class="b-news__item b-news-item">
            <div class="b-news-item__top">
                <div class="b-news-item__image">
                    <img src="<?= !empty($arItem['IMAGE_PREVIEW_PATH']) ? $arItem['IMAGE_PREVIEW_PATH'] : '/images/news/no-img.svg'; ?>" alt="">
                </div>
                <div class="b-news-item__title">
                    <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="b-link-line"><?= $arItem['NAME']; ?></a>
                </div>
            </div>

            <ul class="b-news-item__tags">
                <li class="b-news-item__date">
                    <svg id="SVGDoc" width="14" height="14" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 14 14"><defs><path d="M277.851,977.063c-0.227,0.603 -0.351,1.256 -0.351,1.937c0,3.038 2.463,5.5 5.5,5.5c3.038,0 5.5,-2.462 5.5,-5.5c0,-3.037 -2.462,-5.5 -5.5,-5.5c-0.855,0 -1.666,0.196 -2.388,0.544" id="Path-0"/><path d="M282.75,976.5v3h2.25" id="Path-1"/></defs><desc>Generated with Avocode.</desc><g transform="matrix(1,0,0,1,-276,-972)"><g><title>Group 5</title><g><title>Stroke 1</title><use xlink:href="#Path-0" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g><g><title>Stroke 3</title><use xlink:href="#Path-1" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g></g><g><title>Stroke 3</title><use xlink:href="#Path-1" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g><g><title>Stroke 1</title><use xlink:href="#Path-0" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g></g></svg>
                    <?= $arItem['DATE_SHOW_FORMAT']; ?>
                </li>
                <?php foreach ($arResult['TAGS'] as $tag): ?>
                    <?php if(!in_array($arItem['ID'], $tag['NEWS_IDS'])): continue; endif; ?>
                    <li><a href="<?= $tag['LINK']; ?>"><?= $tag['NAME']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>

<div class="b-news__button">
    <a href="#" class="button button_theme_gray button_icon_refresh j-news-load-more">Загрузить еще новости</a>
</div>
