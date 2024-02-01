<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();?>

<div class="b-area-main-screen j-area-main-screen area-<?= $arResult['ELEMENT']['ALIAS']; ?>">
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
                <li class="b-page-submenu__item">
                    <a href="<?= $arParams['SEF_FOLDER']; ?>" class="b-page-submenu__link b-link-line">
                        <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_GENERAL_INFO'); ?>
                    </a>
                </li>
                <?php foreach ($arResult['ELEMENTS'] as $arItem): ?>
                    <li class="b-page-submenu__item<?php if($arItem['ID'] == $arResult['ELEMENT']['ID']): ?> is-active<?php endif; ?>">
                        <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="b-page-submenu__link b-link-line">
                            <?if ($arItem['NAME'] == 'Инновационный центр' || $arItem['NAME'] == 'Innovational center') { ?>
                                <?= $arItem['NAME']; ?>
                            <?} else {?>
                                <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_PLATFORM'); ?> «<?= $arItem['NAME']; ?>»
                            <?}?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="b-area-main-screen__title">
        <h1>
            <?if ($arResult['ELEMENT']['NAME'] == 'Инновационный центр' || $arResult['ELEMENT']['NAME'] == 'Innovational center') {?>
                <?= $arResult['ELEMENT']['NAME']; ?>
            <?} else {?>
                <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_PLATFORM'); ?> «<?= $arResult['ELEMENT']['NAME']; ?>»
            <?}?>
        </h1>
    </div>
    <div class="b-area-background">
        <?= $arResult['ELEMENT']['TEXT_FEATURES']; ?>
        <?= $arResult['ELEMENT']['TEXT_TRAITS']; ?>
        <?= $arResult['ELEMENT']['TEXT_AREA']; ?>
        <div class="b-area-map" id="area_map">
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
            <div class="b-area-plan" id="area_plan">
                <div class="b-area-plan__title">
                    <h2><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_TERRITORY'); ?></h2>
                    <?php if ($arResult['ELEMENT']['NAME'] != 'Парнас' && $arResult['ELEMENT']['NAME'] != 'Шушары'):?>
                        <div class="b-offers__text">
                            <p>
                                <span class="b-visual__point is-empty b-area-plan__point"></span> &mdash;
                                <?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_TERRITORY_SUB'); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="b-visual<?php if($arResult['ELEMENT']['SHOW_TITLE'] != \Kelnik\Infrastructure\Model\PlatformTable::YES): ?> b-visual_theme_points<?php endif; ?>" data-area="<?= $arResult['ELEMENT']['ALIAS']; ?>" id="area_plan_center">
                    <svg class="b-visual__svg" width="100%" height="100%" viewBox="0 0 1920 1080">
                        <image
                            class="b-visual__image"
                            x="0"
                            y="0"
                            width="1920"
                            height="1080"
                            preserveAspectRatio="none"
                            xlink:href="<?= $arResult['ELEMENT']['AREA_BG_ID']['SRC']; ?>"
                            style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></image>
                        <?php if($arResult['ELEMENT']['ID'] == \Kelnik\Infrastructure\Model\PlatformTable::ID_NOIDORF): ?>
                            <rect
                                class="b-visual__overlay"
                                width="100%"
                                height="100%"
                                fill-opacity="0"
                                mask="url(#b-visual__hole)"></rect>
                        <?php endif; ?>
                        <g class="b-visual__masks">
                            <?php foreach ($arResult['ELEMENT']['PLAN'] as $plan): ?>
                                <path data-id="<?= $plan['ID']; ?>"
                                      data-json="<?= $plan['IS_BUSY'] == 'Y' ? '' : $plan['JSON']; ?>"
                                      <?php if(empty($plan['RESIDENT']['ID'])): ?>
                                          class="<?php if($plan['IS_BUSY'] == 'Y'): ?>is-busy<?php else: ?>is-empty<?php endif; ?>"
                                      <?php elseif(!empty($plan['RESIDENT']['NAME'])): ?>
                                          data-title="<?= $plan['RESIDENT']['NAME']; ?>"
                                      <?php endif; ?>
                                      d="<?= $plan['COORDS']; ?>"
                                      fill="#cacee5"
                                      stroke="#30409a"
                                      stroke-miterlimit="10"
                                      stroke-width="2"></path>
                            <?php endforeach; ?>
                        </g>
                    </svg>
                </div>
            </div>
        <?php endif; ?>
        <?php/* if(!empty($arResult['ELEMENT']['PLANOPLAN'])): ?>
        <div class="b-area-planoplan">
            <script src="https://widget.planoplan.com/etc/multiwidget/release/static/js/main.js"></script>
            <div id="planoplan-widget"></div>
            <script>
                Planoplan.init({
                    uid: '<?= $arResult['ELEMENT']['PLANOPLAN']; ?>',
                    el: 'planoplan-widget',
                    fontFamily: 'Brutal, sans-serif',
                    textColor: '#30409a'
                });
                window._babelPolyfill = null;
            </script>
        </div>
        <?php endif; */?>

        <?= $arResult['ELEMENT']['TEXT_INFRA']; ?>
        <?= $arResult['ELEMENT']['TEXT_CUSTOMS']; ?>

        <?php if ($arResult['SUB_AREA']) {?>
            <div class="b-area-main-screen__video-wrapper _inner-area" id="subarea_<?=$arResult['SUB_AREA']['ID']?>">
                <div class="b-area-overlay"></div>
                <?php if(!empty($arResult['SUB_AREA']['IMAGE_BG_ID_PATH'])): ?>
                    <img src='<?= $arResult['SUB_AREA']['IMAGE_BG_ID_PATH']; ?>'>
                <?php endif; ?>
                <?php if(!empty($arResult['SUB_AREA']['VIDEO_ID']['SRC'])): ?>
                    <video class="b-area-main-screen__video" width="100%" height="auto" preload="auto" autoplay="autoplay"
                           loop="loop" muted>
                        <source src="<?= $arResult['SUB_AREA']['VIDEO_ID']['SRC']; ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                    </video>
                <?php endif; ?>
            </div>
            <div class="b-area-main-screen__title _inner-area">
                <h1><?= $arResult['SUB_AREA']['NAME']; ?></h1>
            </div>
            <?= $arResult['SUB_AREA']['TEXT_FEATURES']; ?>
            <?= $arResult['SUB_AREA']['TEXT_AREA']; ?>
        <?php }?>

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
