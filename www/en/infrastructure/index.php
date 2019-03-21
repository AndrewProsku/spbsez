<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "Infrastructure | SEZ");
$APPLICATION->SetTitle("Sites of the special economic zone «St. Petersburg»");
?>

<?$APPLICATION->IncludeComponent(
    "kelnik:infrastructure",
    "",
    Array(
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "SEF_FOLDER" => "/en/infrastructure/",
        "SEF_MODE" => "Y",
        "SEF_URL_TEMPLATES" => Array("detail"=>"#ELEMENT_CODE#/","index"=>"","map"=>"map/"),
        "SET_404" => "Y"
    )
);?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
