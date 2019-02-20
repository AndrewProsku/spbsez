<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "Управляющая компания | АООЭЗ");
$APPLICATION->SetTitle("Управляющая компания");
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
                    "CODE" => "mc_block_1"
                )
            );?>
        </div>

        <div class="l-management-company__quote" id="mission">
            <?php $APPLICATION->IncludeFile('inc_animation.php'); ?>
            <div class="b-quote">
                <?$APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "mc_block_2"
                    )
                );?>
            </div>
        </div>

        <div class="l-management-company__functions" id="functions">
            <div class="b-functions">
                <?$APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "mc_block_3"
                    )
                );?>
            </div>
        </div>

        <div class="l-management-company__team" id="team">
            <? $APPLICATION->IncludeComponent(
                "kelnik:refbook.list",
                "team",
                array(
                    "COMPONENT_TEMPLATE" => "team",
                    "SECTION" => "4",
                    "CACHE_GROUPS" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600"
                ),
                array()
            ); ?>
        </div>
        <div class="l-management-company__ask-for-advice">
            <section class="b-ask-for-advice">
                <h2 class="b-ask-for-advice__title b-title">Стать резидентом</h2>
                <div class="b-ask-for-advice__text">
                    <p>Обратитесь за консультацией по процедуре получения статуса резидента ОЭЗ «Санкт-Петербург» к нашим специалистам</p>
                </div>
                <a href="#" class="b-ask-for-advice__link button">Контакты отдела</a>
            </section>
        </div>

    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
