<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "Стратегия устойчивого развития");
$APPLICATION->SetTitle("Стратегия устойчивого развития");
?>

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
    <div class="l-management-company">
        <div class="b-title b-about-title">
            <h1><? $APPLICATION->ShowTitle(false); ?></h1>
        </div>

        <div class="b-about-desc">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "s_block_1"
                )
            );?>
        </div>

        <div class="l-investors__post" id="principles">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "s_block_2"
                )
            );?>
        </div>

        <div class="l-investors__advantage" id="industry">
            <div class="b-advantage-location">
                <?$APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "s_block_3"
                    )
                );?>
            </div>
        </div>

        <div class="l-investors__documents _strategy" id="documents">
            <? $APPLICATION->IncludeComponent(
                "kelnik:refbook.list",
                "docs",
                array(
                    "COMPONENT_TEMPLATE" => "docs",
                    "SECTION" => "7",
                    "CACHE_GROUPS" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600"
                ),
                array()
            ); ?>
        </div>

    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
