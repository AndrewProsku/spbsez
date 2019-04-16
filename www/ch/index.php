<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "經濟特區");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("經濟特區");
?>

<div class="l-china">
    <div class="b-title b-about-title">
        <h1><? $APPLICATION->ShowTitle(false); ?></h1>
    </div>

    <div class="b-about-desc">
        <div class="b-about-desc__img">
            <img src="/images/about/about_UK-mission.png" alt="">
        </div>
        <div class="b-about-desc__content">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "about_block_ch_1"
                )
            );?>
        </div>
    </div>
    <div id="advantage">
        <?$APPLICATION->IncludeComponent(
            "kelnik:textblocks",
            "",
            Array(
                "CACHE_TIME" => "360000",
                "CACHE_TYPE" => "A",
                "CODE" => "about_block_ch_2"
            )
        );?>
    </div>

    <? $APPLICATION->IncludeFile('inc_zone_map.php'); ?>

    <div class="l-investors__priorities l-investors__priorities_margin_top" id="priorities">
        <div class="b-priorities-direction">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_ch_2"
                )
            ); ?>
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
                            "CODE" => "about_block_ch_3-1"
                        )
                    );?>
                    <p>
                        <?$APPLICATION->IncludeComponent(
                            "kelnik:textblocks",
                            "",
                            Array(
                                "CACHE_TIME" => "360000",
                                "CACHE_TYPE" => "A",
                                "CODE" => "about_block_ch_3-2"
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
                                    "CODE" => "about_block_ch_3-3"
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
                                    "CODE" => "about_block_ch_3-7"
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
                                    "CODE" => "about_block_ch_3-4"
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
                                    "CODE" => "about_block_ch_3-8"
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
                                    "CODE" => "about_block_ch_3-9"
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
                                    "CODE" => "about_block_ch_3-5"
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
                                    "CODE" => "about_block_ch_3-10"
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
                                    "CODE" => "about_block_ch_3-6"
                                )
                            );?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="l-investors__taxes" id="taxes">
        <div class="b-income-tax">
            <div class="b-income-tax__top">
                <? $APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "investors_block_ch_5"
                    )
                ); ?>
            </div>

            <div class="b-income-tax__bottom" id="investors-taxes">
                <? $APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "investors_block_ch_6"
                    )
                ); ?>
            </div>
        </div>
    </div>

    <div id="rates">
        <div class="b-reduced-rates">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_ch_7"
                )
            ); ?>
        </div>
    </div>

    <div class="b-area-descr b-area-descr_theme_grey" id="customs">
        <? $APPLICATION->IncludeComponent(
            "kelnik:textblocks",
            "",
            Array(
                "CACHE_TIME" => "360000",
                "CACHE_TYPE" => "A",
                "CODE" => "investors_block_ch_8"
            )
        ); ?>
    </div>

    <div id="soft-terms">
        <div class="b-invest-conditions">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_ch_9"
                )
            ); ?>
        </div>
    </div>

    <div id="infrastructure">
        <div class="b-invest-infr">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_ch_10"
                )
            ); ?>
        </div>
    </div>

    <div id="benefits">
        <div class="b-invest-benefits">
            <div class="b-invest-benefits__content">
                <? $APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "investors_block_ch_11"
                    )
                ); ?>
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
                        "CODE" => "about_block_ch_4-1"
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
                                "CODE" => "about_block_ch_4-2-1"
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
                                "CODE" => "about_block_ch_4-2-2"
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
                                "CODE" => "about_block_ch_4-3-1"
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
                                "CODE" => "about_block_ch_4-3-2"
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
                                "CODE" => "about_block_ch_4-4-1"
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
                                "CODE" => "about_block_ch_4-4-2"
                            )
                        );?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="l-investors__department" id="department">
        <div class="b-invest-depart">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_ch_13"
                )
            ); ?>
        </div>
    </div>

</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
