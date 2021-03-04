<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$APPLICATION->IncludeComponent(
    "kelnik:report.chat",
    "",
    Array(
    	"REPORT_ID" => $request->get("reportId"),
        "CACHE_TIME" => "360000",
        "CACHE_TYPE" => "N"
    )
);