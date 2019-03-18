<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "For Investors | SEZ");
$APPLICATION->SetTitle("For Investors");
?>

<div class="l-investors">
    <div class="l-investors__top">
        <h1 class="b-title"><? $APPLICATION->ShowTitle(false); ?></h1>

        <div class="b-investors-info ">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_en_1"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__priorities" id="investors-priorities">
        <div class="b-priorities-direction">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_en_2"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__advantage" id="investors-advantage">
        <div class="b-advantage-location">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_en_3"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__privileges" id="investors-privileges">
        <div class="b-investors-info b-investors-info_big_title">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_en_4"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__taxes">
        <div class="b-income-tax">
            <div class="b-income-tax__top">
                <? $APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "investors_block_en_5"
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
                        "CODE" => "investors_block_en_6"
                    )
                ); ?>
            </div>
        </div>
    </div>

    <div class="l-investors__rate">
        <div class="b-reduced-rates">
            <div id="investors-rate"></div>

            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_en_7"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__post">
        <div class="b-invest-post">
            <div id="investors-post"></div>

            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_en_8"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__conditions">
        <div class="b-invest-conditions">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_en_9"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__infrastructure" id="investors-infrastructure">
        <div class="b-invest-infr">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_en_10"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__benefits" id="investors-benefits">
        <div class="b-invest-benefits">
            <div class="b-invest-benefits__content">
                <? $APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "investors_block_en_11"
                    )
                ); ?>
            </div>
        </div>
    </div>

    <div class="l-investors__resident" >
        <div class="b-invest-resident">
            <div id="investors-resident"></div>

            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_en_12"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__documents">
        <? $APPLICATION->IncludeComponent(
            "kelnik:refbook.list",
            "docs",
            array(
                "COMPONENT_TEMPLATE" => "docs",
                "SECTION" => "5",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            ),
            array()
        ); ?>
    </div>

    <div class="l-investors__department">
        <div class="b-invest-depart">
            <div id="investors-department"></div>

            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_en_13"
                )
            ); ?>
        </div>
    </div>

</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
