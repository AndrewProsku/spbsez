<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "經濟特區 | SEZ");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("經濟特區");
?>

<div class="l-about">
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
    <div class="l-investors__advantage" id="advantage">
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
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
