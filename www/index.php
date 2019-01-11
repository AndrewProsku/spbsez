<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Главная | РОСОЭЗ");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Главная");
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

    <div class="l-home__main-screen j-home__main-screen">
        <? $APPLICATION->IncludeComponent(
            'kelnik:site.info',
            'main-video',
            array(
                "COMPONENT_TEMPLATE" => "main-video",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "360000"
            ),
            array()

        ); ?>
        <? $APPLICATION->IncludeComponent(
            'kelnik:site.info',
            'social',
            array(
                "COMPONENT_TEMPLATE" => "social",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "360000"
            ),
            array()

        ); ?>
        <div class="b-main-screen-content">
            <div class="b-main-screen-text">
                <?$APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "main_screen_1"
                    )
                );?>
            </div>
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "main_screen_2"
                )
            );?>
        </div>
    </div>

    <div class="l-home__block_bg_fixed">
        <div class="l-home__privileges l-home__block">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "main_screen_3"
                )
            );?>
        </div>

        <? $APPLICATION->IncludeComponent(
            "kelnik:news.list",
            "main",
            array(
                "COMPONENT_TEMPLATE" => "main",
                "SECTION_ID" => "1",
                "SECTION_CODE" => "",
                "SORT_BY_1" => "DATE_SHOW",
                "SORT_ORDER_1" => "DESC",
                "SORT_BY_2" => "ID",
                "SORT_ORDER_2" => "ASC",
                "ELEMENTS_COUNT" => "4",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "360000",
                "USE_AJAX" => "N",
                "AJAX_TYPE" => "DEFAULT",
                "AJAX_TEMPLATE_PAGE" => "",
                "AJAX_COMPONENT_ID" => "",
                "SET_SEO_TAGS" => "N",
                "SET_404" => "N",
                "SEF_URL_TEMPLATES" => array(
                    "detail" => "#ELEMENT_CODE#/"
                ),
                "SEF_FOLDER" => "/news/"
            ),
            array()
        ); ?>

        <div class="l-home-resident l-home__block">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "main_screen_4"
                )
            );?>
        </div>
        <? $APPLICATION->IncludeComponent(
            "kelnik:refbook.list",
            "residents",
            array(
                "COMPONENT_TEMPLATE" => "residents",
                "SECTION" => "2",
                "CACHE_GROUPS" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "360000"
            ),
            array()
        ); ?>
        <? $APPLICATION->IncludeComponent(
            "kelnik:refbook.list",
            "reviews",
            array(
                "COMPONENT_TEMPLATE" => "reviews",
                "SECTION" => "3",
                "CACHE_GROUPS" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "360000"
            ),
            array()
        ); ?>

        <div class="l-home-plots j-home-plots">
            <div class="l-home-plots__title-mobile">
                Расположение зон
            </div>
            <div class="l-home-plots__list">

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
                                    <div class="b-tooltip  b-tooltip_position_left">
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
                                    <div class="b-tooltip  b-tooltip_position_left">
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
                            <div class="b-tooltip  b-tooltip_position_left">
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
        <? $APPLICATION->IncludeComponent(
            "kelnik:refbook.list",
            "partners",
            array(
                "COMPONENT_TEMPLATE" => "partners",
                "SECTION" => "1",
                "CACHE_GROUPS" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "360000"
            ),
            array()
        ); ?>
    </div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
