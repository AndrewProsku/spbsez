<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Residents");
$APPLICATION->SetPageProperty('title', 'Residents | SEZ');
?>
<div class="l-residents j-residents-page <? $APPLICATION->ShowProperty('residentCategory'); ?>">
    <div class="b-title b-residents-title"><h1><?= $APPLICATION->ShowTitle(false); ?></h1></div>

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
