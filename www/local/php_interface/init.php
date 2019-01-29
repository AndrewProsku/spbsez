<?php

include realpath(__DIR__ . '/../../../vendor/autoload.php');

\Bitrix\Main\Loader::includeModule('kelnik.userdata');

AddEventHandler("main", "OnBeforeEventAdd", "OnBeforeEventAddHandler");
AddEventHandler('main', 'OnUserTypeBuildList', [\Kelnik\Userdata\FieldTypeUser::class, 'GetUserTypeDescription']);

function OnBeforeEventAddHandler(&$event, &$lid, $arFields) {
    if ($event == "FAVORITES") {
        require_once realpath(__DIR__ . '/lib/mail_attach/mail_attach.php');
        SendAttache($event, $lid, $arFields, "/upload/pdf/{$arFields['FILE_NAME']}");
        $event = 'null';
        $lid = 'null';
    }
}
