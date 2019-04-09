<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Вакансии");
$APPLICATION->SetPageProperty('title', "Вакансии | ОЭЗ СПб");
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
    <div class="l-vacancies">
        <div class="b-vacancies-title">
            <h1><? $APPLICATION->ShowTitle(false); ?></h1>
        </div>
        <div class="b-vacancies-description">
            <?$APPLICATION->IncludeComponent(
                "kelnik:textblocks",
                "",
                Array(
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CODE" => "about_vacancy"
                )
            );?>
        </div>

        <?$APPLICATION->IncludeComponent(
            "kelnik:vacancy.list",
            "",
            Array(
                "CACHE_TIME" => "360000",
                "CACHE_TYPE" => "A"
            )
        );?>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
