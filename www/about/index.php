<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Особая экономическая зона");
$APPLICATION->SetPageProperty('title', 'Особая экономическая зона | РОСОЭЗ');
?>

    <div class="b-animation-block j-animation-block">
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
        <div class="b-animation-block__line"></div>
    </div>

    <?$APPLICATION->IncludeComponent(
        "bitrix:menu",
        "submenu-about",
        Array(
            "ALLOW_MULTI_SELECT" => "N",
            "DELAY" => "N",
            "MAX_LEVEL" => "1",
            "MENU_CACHE_GET_VARS" => array(""),
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_TYPE" => "A",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "ROOT_MENU_TYPE" => "left",
            "USE_EXT" => "N"
        )
    );?>
    <div class="l-about">
        <div class="b-title b-about-title">
            <h1><? $APPLICATION->ShowTitle(false); ?></h1>
        </div>

        <div class="b-about-desc">
            <div class="b-about-desc__img">
                <img src="/images/about/about_UK-mission.png" alt="картинка">
            </div>
            <div class="b-about-desc__content">
                <?$APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "about_block_1"
                    )
                );?>
            </div>
        </div>

        <div class="b-about-info" id="resident">
            <div class="b-about-info__item">
                <div class="b-about-info__text">
                    <?$APPLICATION->IncludeComponent(
                        "kelnik:textblocks",
                        "",
                        Array(
                            "CACHE_TIME" => "360000",
                            "CACHE_TYPE" => "A",
                            "CODE" => "about_block_2-1"
                        )
                    );?>
                </div>
            </div>
            <div class="b-about-info__item">
                <div class="b-about-info__item-block">
                    <div class="b-about-info__text">
                        <?$APPLICATION->IncludeComponent(
                            "kelnik:textblocks",
                            "",
                            Array(
                                "CACHE_TIME" => "360000",
                                "CACHE_TYPE" => "A",
                                "CODE" => "about_block_2-2"
                            )
                        );?>
                    </div>
                </div>
                <div class="b-about-info__item-block"></div>
                <div class="b-about-info__item-block"></div>
                <div class="b-about-info__item-block">
                    <div class="b-about-info__text">
                        <?$APPLICATION->IncludeComponent(
                            "kelnik:textblocks",
                            "",
                            Array(
                                "CACHE_TIME" => "360000",
                                "CACHE_TYPE" => "A",
                                "CODE" => "about_block_2-3"
                            )
                        );?>
                    </div>
                </div>
            </div>
            <div class="b-about-info__item" style="background-image:url('/images/about/about-info-img.jpg');">
                <div class="b-about-info__text">
                    <?$APPLICATION->IncludeComponent(
                        "kelnik:textblocks",
                        "",
                        Array(
                            "CACHE_TIME" => "360000",
                            "CACHE_TYPE" => "A",
                            "CODE" => "about_block_2-4"
                        )
                    );?>
                </div>
            </div>
            <div class="b-about-info__item">
                <div class="b-about-info__item-block"></div>
                <div class="b-about-info__item-block"></div>
                <div class="b-about-info__item-block">
                    <div class="b-about-info__text">
                        <?$APPLICATION->IncludeComponent(
                            "kelnik:textblocks",
                            "",
                            Array(
                                "CACHE_TIME" => "360000",
                                "CACHE_TYPE" => "A",
                                "CODE" => "about_block_2-5"
                            )
                        );?>
                    </div>
                </div>
            </div>
            <div class="b-about-info__item">
                <div class="b-about-info__item-block"
                     style="background-image:url('/images/about/about-info-img2.jpg');">
                    <div class="b-about-info__text">
                        <?$APPLICATION->IncludeComponent(
                            "kelnik:textblocks",
                            "",
                            Array(
                                "CACHE_TIME" => "360000",
                                "CACHE_TYPE" => "A",
                                "CODE" => "about_block_2-6"
                            )
                        );?>
                    </div>
                </div>
                <div class="b-about-info__item-block"></div>
                <div class="b-about-info__item-block"></div>
            </div>
            <div class="b-about-info__item">
                <div class="b-about-info__text">
                    <?$APPLICATION->IncludeComponent(
                        "kelnik:textblocks",
                        "",
                        Array(
                            "CACHE_TIME" => "360000",
                            "CACHE_TYPE" => "A",
                            "CODE" => "about_block_2-7"
                        )
                    );?>
                </div>
            </div>
            <div class="b-about-info__item">
                <div class="b-about-info__text">
                    <?$APPLICATION->IncludeComponent(
                        "kelnik:textblocks",
                        "",
                        Array(
                            "CACHE_TIME" => "360000",
                            "CACHE_TYPE" => "A",
                            "CODE" => "about_block_2-8"
                        )
                    );?>
                </div>
            </div>
        </div>

        <div class="b-about-privileges" id="privileges">
            <div class="b-about-privileges__block">
                <div class="b-about-privileges__main-block">

                    <div class="b-about-privileges__main-circle one"></div>
                    <div class="b-about-privileges__main-circle two"></div>
                    <div class="b-about-privileges__main-circle three"></div>
                    <div class="b-about-privileges__main-circle four"></div>
                    <div class="b-about-privileges__main-circle five"></div>
                    <div class="b-about-privileges__main-circle six"></div>

                    <div class="b-about-privileges__main-block-text">
                        <?$APPLICATION->IncludeComponent(
                            "kelnik:textblocks",
                            "",
                            Array(
                                "CACHE_TIME" => "360000",
                                "CACHE_TYPE" => "A",
                                "CODE" => "about_block_3-1"
                            )
                        );?>
                        <p>
                            <?$APPLICATION->IncludeComponent(
                                "kelnik:textblocks",
                                "",
                                Array(
                                    "CACHE_TIME" => "360000",
                                    "CACHE_TYPE" => "A",
                                    "CODE" => "about_block_3-2"
                                )
                            );?>
                        </p>
                    </div>
                    <div class="b-about-privileges__content">
                        <div class="b-about-privileges__item">
                            <p>
                                <?$APPLICATION->IncludeComponent(
                                    "kelnik:textblocks",
                                    "",
                                    Array(
                                        "CACHE_TIME" => "360000",
                                        "CACHE_TYPE" => "A",
                                        "CODE" => "about_block_3-3"
                                    )
                                );?>
                            </p>
                        </div>
                        <div class="b-about-privileges__item">
                            <p>
                                <?$APPLICATION->IncludeComponent(
                                    "kelnik:textblocks",
                                    "",
                                    Array(
                                        "CACHE_TIME" => "360000",
                                        "CACHE_TYPE" => "A",
                                        "CODE" => "about_block_3-7"
                                    )
                                );?>
                            </p>
                        </div>
                        <div class="b-about-privileges__item">
                            <p>
                                <?$APPLICATION->IncludeComponent(
                                    "kelnik:textblocks",
                                    "",
                                    Array(
                                        "CACHE_TIME" => "360000",
                                        "CACHE_TYPE" => "A",
                                        "CODE" => "about_block_3-4"
                                    )
                                );?>
                            </p>
                        </div>
                        <div class="b-about-privileges__item">
                            <p>
                               <?$APPLICATION->IncludeComponent(
                                    "kelnik:textblocks",
                                    "",
                                    Array(
                                        "CACHE_TIME" => "360000",
                                        "CACHE_TYPE" => "A",
                                        "CODE" => "about_block_3-8"
                                    )
                                );?>
                            </p>
                        </div>
                        <div class="b-about-privileges__item">
                            <p>
                                <?$APPLICATION->IncludeComponent(
                                    "kelnik:textblocks",
                                    "",
                                    Array(
                                        "CACHE_TIME" => "360000",
                                        "CACHE_TYPE" => "A",
                                        "CODE" => "about_block_3-9"
                                    )
                                );?>
                            </p>
                        </div>
                        <div class="b-about-privileges__item">
                            <p>
                                <?$APPLICATION->IncludeComponent(
                                    "kelnik:textblocks",
                                    "",
                                    Array(
                                        "CACHE_TIME" => "360000",
                                        "CACHE_TYPE" => "A",
                                        "CODE" => "about_block_3-5"
                                    )
                                );?>
                            </p>
                        </div>
                        <div class="b-about-privileges__item">
                            <p>
                                <?$APPLICATION->IncludeComponent(
                                    "kelnik:textblocks",
                                    "",
                                    Array(
                                        "CACHE_TIME" => "360000",
                                        "CACHE_TYPE" => "A",
                                        "CODE" => "about_block_3-10"
                                    )
                                );?>
                            </p>
                        </div>
                        <div class="b-about-privileges__item">
                            <p>
                                <?$APPLICATION->IncludeComponent(
                                    "kelnik:textblocks",
                                    "",
                                    Array(
                                        "CACHE_TIME" => "360000",
                                        "CACHE_TYPE" => "A",
                                        "CODE" => "about_block_3-6"
                                    )
                                );?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="l-home-plots" id="area">
            <div class="l-home-plots__title-mobile">
                Расположение зон
            </div>
            <div class="l-home-plots__list">

                <div class="l-home-plots__item">

                    <div class="l-home-plots__map l-home-plots__map-neudorf">
                        <div class="l-home-plots__all-map-images">
                            <div class="l-home-plots__all-map-icon-wrap">
                                <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-one">
                            <span class="b-tooltip">
                                Новоорловская
                            </span>
                                </a>
                                <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-two">
                            <span class="b-tooltip">
                                Нойдорф
                            </span>
                                </a>
                                <div class="l-home-plots__all-map-icon icon-port">
                                    <div class="b-tooltip">
                                        Порт
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                                    <div class="b-tooltip">
                                        Вокзал
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-center-city">
                                    <div class="b-tooltip">
                                        Центр города
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                                    <div class="b-tooltip b-tooltip_position_left">
                                        Ладожский вокзал
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-airport">
                                    <div class="b-tooltip">
                                        Аэропорт
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="l-home-plots__content">
                        <div class="l-home-plots__title">
                            <a href="#" class="b-link-line">
                                Участок «Нойдорф»
                            </a>
                        </div>
                        <div class="l-home-plots__desc">
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>18,99 га</strong> <span>площадь участка</span>
                                </p>
                            </div>
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>30 км</strong> <span>от центра</span>
                                </p>
                            </div>
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>Стрельна</strong> <span>юго — запад Петербурга</span>
                                </p>
                            </div>
                        </div>
                        <div class="l-home-plots__text">
                            <p>
                                В окружении развитой городской инфраструктуры и культурного наследия. Менее
                                10 минут до выезда на КАД. 20 минут на автотранспорте до аэропорта
                            </p>
                        </div>
                        <div class="l-home-plots__link">
                            <a href="#" class="b-link-line">
                                Узнать больше
                            </a>
                        </div>
                    </div>
                </div>

                <div class="l-home-plots__item">

                    <div class="l-home-plots__map l-home-plots__map-novoorlovskaya">
                        <div class="l-home-plots__all-map-images">
                            <div class="l-home-plots__all-map-icon-wrap">
                                <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-one">
                            <span class="b-tooltip">
                                Новоорловская
                            </span>
                                </a>
                                <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-two">
                            <span class="b-tooltip">
                                Нойдорф
                            </span>
                                </a>
                                <div class="l-home-plots__all-map-icon icon-port">
                                    <div class="b-tooltip">
                                        Порт
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                                    <div class="b-tooltip">
                                        Вокзал
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-center-city">
                                    <div class="b-tooltip">
                                        Центр города
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                                    <div class="b-tooltip b-tooltip_position_left">
                                        Ладожский вокзал
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-airport">
                                    <div class="b-tooltip">
                                        Аэропорт
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="l-home-plots__content">
                        <div class="l-home-plots__title">
                            <a href="#" class="b-link-line">
                                Участок «Новоорловская»
                            </a>
                        </div>
                        <div class="l-home-plots__desc">
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>163,33 га</strong> <span>площадь участка</span>
                                </p>
                            </div>
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>20 км</strong> <span>от центра</span>
                                </p>
                            </div>
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>Приморский район</strong> <span>В лесопарке «Новоорловский»</span>
                                </p>
                            </div>
                        </div>
                        <div class="l-home-plots__text">
                            <p>
                                Развитая транспортная инфраструктура. Менее 10 минут до выезда на КАД.
                                30 минут до центра города на автотранспорте. 20 минут
                                до метро на автотранспорте
                            </p>
                        </div>
                        <div class="l-home-plots__link">
                            <a href="#" class="b-link-line">
                                Узнать больше
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="l-home-plots__all-map">
                <div class="l-home-plots__all-map-images">
                    <div class="l-home-plots__all-map-icon-wrap">
                        <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-novoorlovskaya">
                            <span class="b-tooltip">
                                Новоорловская
                            </span>
                        </a>
                        <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-neudorf">
                            <span class="b-tooltip">
                                Нойдорф
                            </span>
                        </a>
                        <div class="l-home-plots__all-map-icon icon-port">
                            <div class="b-tooltip">
                                Порт
                            </div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                            <div class="b-tooltip">
                                Вокзал
                            </div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-center-city">
                            <div class="b-tooltip">
                                Центр города
                            </div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                            <div class="b-tooltip b-tooltip_position_left">
                                Ладожский вокзал
                            </div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-airport">
                            <div class="b-tooltip">
                                Аэропорт
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="b-about-government" id="government">
            <div class="b-government">
                <div class="b-government__title">
                    <?$APPLICATION->IncludeComponent(
                        "kelnik:textblocks",
                        "",
                        Array(
                            "CACHE_TIME" => "360000",
                            "CACHE_TYPE" => "A",
                            "CODE" => "about_block_4-1"
                        )
                    );?>
                </div>

                <div class="b-government__list">
                    <div class="b-government__item">
                        <div class="b-government__item-header">
                            <?$APPLICATION->IncludeComponent(
                                "kelnik:textblocks",
                                "",
                                Array(
                                    "CACHE_TIME" => "360000",
                                    "CACHE_TYPE" => "A",
                                    "CODE" => "about_block_4-2-1"
                                )
                            );?>
                        </div>
                        <div class="b-government__item-text">
                            <?$APPLICATION->IncludeComponent(
                                "kelnik:textblocks",
                                "",
                                Array(
                                    "CACHE_TIME" => "360000",
                                    "CACHE_TYPE" => "A",
                                    "CODE" => "about_block_4-2-2"
                                )
                            );?>
                        </div>
                    </div>

                    <div class="b-government__item">
                        <div class="b-government__item-header">
                            <?$APPLICATION->IncludeComponent(
                                "kelnik:textblocks",
                                "",
                                Array(
                                    "CACHE_TIME" => "360000",
                                    "CACHE_TYPE" => "A",
                                    "CODE" => "about_block_4-3-1"
                                )
                            );?>
                        </div>
                        <div class="b-government__item-text">
                            <?$APPLICATION->IncludeComponent(
                                "kelnik:textblocks",
                                "",
                                Array(
                                    "CACHE_TIME" => "360000",
                                    "CACHE_TYPE" => "A",
                                    "CODE" => "about_block_4-3-2"
                                )
                            );?>
                        </div>
                    </div>

                    <div class="b-government__item">
                        <div class="b-government__item-header">
                            <?$APPLICATION->IncludeComponent(
                                "kelnik:textblocks",
                                "",
                                Array(
                                    "CACHE_TIME" => "360000",
                                    "CACHE_TYPE" => "A",
                                    "CODE" => "about_block_4-4-1"
                                )
                            );?>
                        </div>
                        <div class="b-government__item-text">
                            <?$APPLICATION->IncludeComponent(
                                "kelnik:textblocks",
                                "",
                                Array(
                                    "CACHE_TIME" => "360000",
                                    "CACHE_TYPE" => "A",
                                    "CODE" => "about_block_4-4-2"
                                )
                            );?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
