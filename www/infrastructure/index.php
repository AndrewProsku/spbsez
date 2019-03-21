<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty('title', "Инфраструктура | АООЭЗ");
$APPLICATION->SetTitle("Площадки особой экономической зоны «Санкт-Петербург»");
?>

<?$APPLICATION->IncludeComponent(
    "kelnik:infrastructure",
    "",
    Array(
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "SEF_FOLDER" => "/infrastructure/",
        "SEF_MODE" => "Y",
        "SEF_URL_TEMPLATES" => Array("detail"=>"#ELEMENT_CODE#/","index"=>"","map"=>"map/"),
        "SET_404" => "Y"
    )
);?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
