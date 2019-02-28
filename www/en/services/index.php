<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "Services | SEZ");
$APPLICATION->SetTitle("Services");
?>

<div class="l-services">
    <div class="b-title b-services__title">
        <h1><? $APPLICATION->ShowTitle(false); ?></h1>
    </div>

    <div class="b-services__text">
        <?$APPLICATION->IncludeComponent(
            "kelnik:textblocks",
            "",
            Array(
                "CACHE_TIME" => "360000",
                "CACHE_TYPE" => "A",
                "CODE" => "services_block_en_1"
            )
        );?>
    </div>

    <div class="l-services__wrap">
        <?php $APPLICATION->IncludeFile('inc_animation.php'); ?>

        <section class="b-offers">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "services_block_en_2"
                )
            );?>
        </section>
    </div>

    <div class="l-services__wrap">
        <section class="b-offers">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "services_block_en_3"
                )
            );?>
        </section>
    </div>

    <div class="l-services__wrap">
        <?php $APPLICATION->IncludeFile('inc_animation.php'); ?>

        <section class="b-offers">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "services_block_en_4"
                )
            );?>
        </section>
    </div>

    <div class="l-services__advantage">
        <section class="b-advantages b-advantages_theme_gradient">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "services_block_en_5"
                )
            );?>
        </section>
    </div>

    <div class="l-services__advantage">
        <?php $APPLICATION->IncludeFile('inc_animation.php'); ?>

        <section class="b-advantages ">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "services_block_en_6"
                )
            );?>
        </section>
    </div>

    <div class="l-services__advantage">
        <section class="b-advantages b-advantages_theme_gradient">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "services_block_en_7"
                )
            );?>
        </section>
    </div>

    <div class="l-services__advantage">
        <?php $APPLICATION->IncludeFile('inc_animation.php'); ?>

        <section class="b-advantages ">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "services_block_en_8"
                )
            );?>
        </section>
    </div>
</div>

<? $APPLICATION->IncludeComponent(
    "kelnik:request.form",
    "service",
    array(
        "COMPONENT_TEMPLATE" => "service",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "3600",
        "SUB_TYPE" => "service"
    ),
    array()
); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
