<?php

include realpath(__DIR__ . '/../../../vendor/autoload.php');

AddEventHandler("main", "OnBeforeEventAdd", "OnBeforeEventAddHandler");

function OnBeforeEventAddHandler(&$event, &$lid, $arFields) {
    if ($event == "FAVORITES") {
        require_once realpath(__DIR__ . '/lib/mail_attach/mail_attach.php');
        SendAttache($event, $lid, $arFields, "/upload/pdf/{$arFields['FILE_NAME']}");
        $event = 'null';
        $lid = 'null';
    }
}