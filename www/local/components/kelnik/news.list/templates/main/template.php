<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php if (empty($arResult['ELEMENTS'])): return; endif; ?>
<div class="l-home__news l-home__block">
    <h2>Новости</h2>
    <div class="l-home__news-block">
        <?php
            $arItem = array_shift($arResult['ELEMENTS']);
        ?>
        <div class="l-home__news-main">
            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>"
               class="l-home__news-main-img"
               <?php if(!empty($arItem['IMAGE_PREVIEW_PATH'])): ?>style="background-image:url('<?= $arItem['IMAGE_PREVIEW_PATH']; ?>');"<?php endif; ?>></a>
            <div class="l-home__news-main-content">
                <div class="l-home__news-main-title">
                    <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="b-link-line"><?= $arItem['NAME']; ?></a>
                </div>
                <time class="l-home__news-date label-bg"><?= $arItem['DATE_SHOW_FORMAT']; ?></time>
                <?php foreach ($arResult['TAGS'] as $tag): ?>
                    <?php if(!in_array($arItem['ID'], $tag['NEWS_IDS'])): continue; endif; ?>
                    <a href="<?= $tag['LINK']; ?>" class="l-home__news-subsection label-bg"><?= $tag['NAME']; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if($arResult['ELEMENTS']): ?>
            <div class="l-home__news-list">
                <?php foreach ($arResult['ELEMENTS'] as $arItem): ?>
                    <div class="l-home__news-item">
                        <div class="l-home__news-title">
                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="b-link-line"><?= $arItem['NAME']; ?></a>
                        </div>
                        <time class="l-home__news-date label-bg"><?= $arItem['DATE_SHOW_FORMAT']; ?></time>
                        <?php foreach ($arResult['TAGS'] as $tag): ?>
                            <?php if(!in_array($arItem['ID'], $tag['NEWS_IDS'])): continue; endif; ?>
                            <a href="<?= $tag['LINK']; ?>" class="l-home__news-subsection label-bg"><?= $tag['NAME']; ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="l-home__more">
            <a href="<?= $arParams['SEF_FOLDER']; ?>" class="button">Все новости</a>
        </div>
    </div>
</div>
