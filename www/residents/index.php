<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Резиденты");
$APPLICATION->SetPageProperty('title', 'Резиденты');
?>

<div class="l-residents-menu">
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
</div>

<div class="l-residents j-residents-page <? $APPLICATION->ShowProperty('residentCategory'); ?>">
    <div class="b-title b-residents-title"><h1><?= $APPLICATION->ShowTitle(false); ?></h1></div>

    <div class="l-residents-banner">
        <?php
        $APPLICATION->IncludeComponent(
            'kelnik:banners',
            '',
            Array(
                'POSITION' => 'RESIDENTS'
            )
        );
        ?>
    </div>


    <? $APPLICATION->IncludeComponent(
        "kelnik:refbook.list",
        "residents-full",
        array(
            "COMPONENT_TEMPLATE" => "residents-full",
            "SECTION" => "2",
            "CACHE_GROUPS" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600"
        ),
        array()
    ); ?>

</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
