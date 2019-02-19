<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "Инвесторам | АООЭЗ");
$APPLICATION->SetTitle("Инвесторам");
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
                    "CODE" => "investors_block_1"
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
                    "CODE" => "investors_block_2"
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
                    "CODE" => "investors_block_3"
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
                    "CODE" => "investors_block_4"
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
                        "CODE" => "investors_block_5"
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
                        "CODE" => "investors_block_6"
                    )
                ); ?>
            </div>
        </div>
    </div>

    <div class="l-investors__rate" id="investors-rate">
        <div class="b-reduced-rates">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_7"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__post" id="investors-post">
        <div class="b-invest-post">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_8"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__conditions" id="investors-conditions">
        <div class="b-invest-conditions">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_9"
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
                    "CODE" => "investors_block_10"
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
                        "CODE" => "investors_block_11"
                    )
                ); ?>
            </div>
        </div>
    </div>

    <div class="l-investors__resident" id="investors-resident">
        <div class="b-invest-resident">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_12"
                )
            ); ?>
        </div>
    </div>

    <div class="l-investors__documents" id="investors-documents">
        <? $APPLICATION->IncludeComponent(
            "kelnik:refbook.list",
            "docs",
            array(
                "COMPONENT_TEMPLATE" => "docs",
                "SECTION" => "5",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "360000"
            ),
            array()
        ); ?>
    </div>

    <div class="l-investors__department" id="investors-department">
        <div class="b-invest-depart">
            <? $APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "investors_block_13"
                )
            ); ?>
        </div>
    </div>

</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
