<?php

include realpath(__DIR__ . '/../../../vendor/autoload.php');

AddEventHandler("main", "OnBeforeEventAdd", "OnBeforeEventAddHandler");

function OnBeforeEventAddHandler(&$event, &$lid, $arFields)
{
    if ($event == "FAVORITES") {
        require_once realpath(__DIR__ . '/lib/mail_attach/mail_attach.php');
        SendAttache($event, $lid, $arFields, "/upload/pdf/{$arFields['FILE_NAME']}");
        $event = 'null';
        $lid = 'null';
    }
}

if (!function_exists('getSiteBaseUrl')) {
    function getSiteBaseUrl()
    {
        return empty($_SERVER)
            ? ''
            : (($_SERVER['SERVER_PORT'] == 443 || strtolower($_SERVER['HTTPS']) == 'on') ? 'https' : 'http') .
                '://' . $_SERVER['HTTP_HOST'];
    }
}

class SezLang
{
    public const CHINESE_DIR = '/ch/';
    public const ENGLISH_DIR = '/en/';
    public const RUSSIAN_DIR = '/';
}
