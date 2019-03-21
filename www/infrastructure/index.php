<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "Инфраструктура | АООЭЗ");
$APPLICATION->SetTitle("Инфраструктура");
?>

<?$APPLICATION->IncludeComponent(
    "kelnik:infrastructure",
    "",
    Array(
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "SEF_FOLDER" => "/infrastructure/",
        "SEF_MODE" => "Y",
        "SEF_URL_TEMPLATES" => Array("detail"=>"#ELEMENT_ID#/","index"=>"","map"=>"map/"),
        "SET_404" => "Y"
    )
);?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
