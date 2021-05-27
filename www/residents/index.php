<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Резиденты");
$APPLICATION->SetPageProperty('title', 'Резиденты');
?>
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
