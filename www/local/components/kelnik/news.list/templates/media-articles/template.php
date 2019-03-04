<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php if (empty($arResult['ELEMENTS'])): return; endif; ?>
<div class="b-useful">
    <h2 class="b-useful__title">Полезная информация</h2>
    <div class="b-useful__content">
        <?php foreach($arResult['ELEMENTS'] as $arItem): ?>
        <div class="b-useful__item">
            <div class="b-useful__item">
                <div class="b-useful-item__caption-wrap">
                    <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="b-useful-item__caption b-link-line"><?= $arItem['NAME']; ?></a>
                </div>
                <div class="b-useful-item__text"><?= $arItem['TEXT_PREVIEW']; ?></div>
                <div class="b-useful-item__date">
                    <span>
                        <svg id="SVGDoc" width="14" height="14" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 14 14"><defs><path d="M277.851,977.063c-0.227,0.603 -0.351,1.256 -0.351,1.937c0,3.038 2.463,5.5 5.5,5.5c3.038,0 5.5,-2.462 5.5,-5.5c0,-3.037 -2.462,-5.5 -5.5,-5.5c-0.855,0 -1.666,0.196 -2.388,0.544" id="Path-0"/><path d="M282.75,976.5v3h2.25" id="Path-1"/></defs><desc>Generated with Avocode.</desc><g transform="matrix(1,0,0,1,-276,-972)"><g><title>Group 5</title><g><title>Stroke 1</title><use xlink:href="#Path-0" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g><g><title>Stroke 3</title><use xlink:href="#Path-1" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g></g><g><title>Stroke 3</title><use xlink:href="#Path-1" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g><g><title>Stroke 1</title><use xlink:href="#Path-0" fill-opacity="0" fill="#ffffff" stroke-linejoin="miter" stroke-linecap="round" stroke-opacity="1" stroke="#662d91" stroke-miterlimit="50" stroke-width="1.5"/></g></g></svg>
                        <?= $arItem['DATE_SHOW_FORMAT']; ?>
                    </span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="b-useful__button">
        <a href="<?= $arParams['SEF_FOLDER']; ?>" class="button button_theme_white">Посмотреть все статьи</a>
    </div>
</div>
