<?php

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if (!\Bitrix\Main\Loader::includeModule('kelnik.api')) {
    header('Content-type:application/json; charset=UTF-8');
    die(json_encode([
        'request' => [
            'status' => false,
            'error' => 'Не подключен модуль API'
        ]
    ]));
}

\Kelnik\Api\Api::instance()->listen();
