<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Main page | SEZ");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Main page");
?>
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
                text
            </div>
        </div>
    </div>
    <div class="l-home__block_bg_fixed">
        <? $APPLICATION->IncludeComponent(
            "kelnik:refbook.list",
            "partners",
            array(
                "COMPONENT_TEMPLATE" => "partners",
                "SECTION" => "1",
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600"
            ),
            array()
        ); ?>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
