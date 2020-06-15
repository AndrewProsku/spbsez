<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$APPLICATION->IncludeComponent(
    "kelnik:search",
    "",
    Array(
        "CACHE_TIME" => "360000",
        "CACHE_TYPE" => "A",
    )
);
