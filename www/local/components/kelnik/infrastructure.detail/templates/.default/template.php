<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();?>

<div class="b-area-main-screen j-area-main-screen">
    <div class="b-area-main-screen__video-wrapper"<?php if(!empty($arResult['ELEMENT']['IMAGE_BG_ID_PATH'])): ?> style="background-image: url('<?= $arResult['ELEMENT']['IMAGE_BG_ID_PATH']; ?>');"<?php endif; ?>>
        <?php if(!empty($arResult['ELEMENT']['VIDEO_ID']['SRC'])): ?>
        <video class="b-area-main-screen__video" width="100%" height="auto" preload="auto" autoplay="autoplay"
               loop="loop" muted>
            <source src="<?= $arResult['ELEMENT']['VIDEO_ID']['SRC']; ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        </video>
        <?php endif; ?>
    </div>
    <div class="b-page-submenu">
        <div class="b-page-submenu__block">
            <ul class="b-page-submenu__list">
                <?php foreach ($arResult['ELEMENTS'] as $arItem): ?>
                    <li class="b-page-submenu__item<?php if($arItem['ID'] == $arResult['ELEMENT']['ID']): ?> is-active<?php endif; ?>">
                        <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="b-page-submenu__link b-link-line">
                            <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_PLATFORM'); ?> «<?= $arItem['NAME']; ?>»
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="b-area-main-screen__title">
        <h1><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_PLATFORM'); ?> «<?= $arResult['ELEMENT']['NAME']; ?>»</h1>
    </div>
    <div class="b-area-background">
        <?= $arResult['ELEMENT']['TEXT_FEATURES']; ?>
        <?= $arResult['ELEMENT']['TEXT_TRAITS']; ?>
        <?= $arResult['ELEMENT']['TEXT_AREA']; ?>
        <div class="b-area-map">
            <div class="b-area-map__title">
                <h2><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_PLACEMENT'); ?></h2>
            </div>
            <div class="b-area-map__content">
                <div class="b-infrastructure-map__yandex-map b-yandex-map b-yandex-map_theme_novoorlov j-yandex-map-noidorf"
                     data-lang="<?= LANGUAGE_ID; ?>"
                     data-json="<?= base64_encode(json_encode($arResult['ELEMENT']['MAP_DATA'])); ?>">
                    <div id="first" class="b-yandex-map__base"></div>
                </div>
            </div>
            <div class="b-area-map__description">
                <?= $arResult['ELEMENT']['TEXT_MAP']; ?>
            </div>
        </div>
        <?php if(!empty($arResult['ELEMENT']['AREA_BG_ID']['SRC'])): ?>
            <div class="b-area-plan">
                <div class="b-area-plan__title"><h2><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_TERRITORY'); ?></h2></div>
                <div class="b-visual" data-area="<?= $arResult['ELEMENT']['ALIAS']; ?>">
                    <svg class="b-visual__svg" width="100%" height="100%" viewBox="0 0 <?= $arResult['ELEMENT']['AREA_BG_ID']['WIDTH']; ?> <?= $arResult['ELEMENT']['AREA_BG_ID']['HEIGHT']; ?>">
                        <image
                            class="b-visual__image"
                            x="0"
                            y="0"
                            width="<?= $arResult['ELEMENT']['AREA_BG_ID']['WIDTH']; ?>"
                            height="<?= $arResult['ELEMENT']['AREA_BG_ID']['HEIGHT']; ?>"
                            preserveAspectRatio="none"
                            xlink:href="<?= $arResult['ELEMENT']['AREA_BG_ID']['SRC']; ?>"
                            style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></image>
                        <?php
                            $maskFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'territory' . $arResult['ELEMENT']['ID'] . '.php';
                            if (file_exists($maskFile)) {
                                include $maskFile;
                            }
                        ?>
                    </svg>
                </div>
            </div>
        <?php endif; ?>
        <?php if(!empty($arResult['ELEMENT']['PLANOPLAN'])): ?>
        <div class="b-area-planoplan">
            <iframe src="https://widget.planoplan.com/<?= $arResult['ELEMENT']['PLANOPLAN']; ?>" frameborder="0" allowfullscreen="" scrolling="no"></iframe>
        </div>
        <?php endif; ?>
        <?= $arResult['ELEMENT']['TEXT_INFRA']; ?>
        <?= $arResult['ELEMENT']['TEXT_CUSTOMS']; ?>
        <?php if(!empty($arResult['ELEMENT']['IMAGES'])): ?>
            <section class="b-area-centre">
                <div class="b-area-centre__title">
                    <h2><?= $arResult['ELEMENT']['HEADER_GALLERY']; ?></h2>
                </div>

                <div class="glide j-area-slider">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            <?php foreach ($arResult['ELEMENT']['IMAGES'] as $img): ?>
                                <li class="glide__slide">
                                    <img src="<?= $img['SRC']; ?>" class="j-glide-slide-img" alt="">
                                    <span class="glide__slide-description"><?= $img['DESCRIPTION']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="glide__arrows" data-glide-el="controls">
                        <button class="glide__cust-arrow glide__cust-arrow_is_left j-glide-arrow" data-glide-dir="<"
                                type="button">
                            <img src="/images/home/slide-arrow-white.svg" alt="стрелка влево">
                        </button>
                        <button class="glide__cust-arrow glide__cust-arrow_is_right j-glide-arrow"
                                data-glide-dir="&#62;" type="button">
                            <img src="/images/home/slide-arrow-white.svg" alt="стрелка вправо">
                        </button>
                    </div>

                    <div class="glide__cust-dots j-glide-dots" data-glide-el="controls[nav]">
                        <?php $i = 0; ?>
                        <?php foreach ($arResult['ELEMENT']['IMAGES'] as $img): ?>
                        <button class="glide__cust-dot" data-glide-dir="=<?= $i++; ?>" type="button"></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?= $arResult['ELEMENT']['TEXT_GALLERY']; ?>
            </section>
        <?php endif; ?>
        <?= $arResult['ELEMENT']['TEXT_ADVANTAGES1']; ?>
        <?= $arResult['ELEMENT']['TEXT_ADVANTAGES2']; ?>
        <?= $arResult['ELEMENT']['TEXT_ADVANTAGES3']; ?>
    </div>
</div>
