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

/**
 * Очистка кеша компонентов
 *
 * @param $dirName
 * @return mixed
 */
function clearKelnikComponentCache($dirName)
{
    $path = implode(
        DIRECTORY_SEPARATOR,
        [
            \Bitrix\Main\Application::getDocumentRoot(),
            trim(getLocalPath('cache'), DIRECTORY_SEPARATOR),
            SITE_ID,
            'kelnik'
        ]
    );

    $dirs = glob(
        $path . DIRECTORY_SEPARATOR . $dirName . '*',
        GLOB_ONLYDIR
    );

    if (!$dirs) {
        return false;
    }

    $res = [];

    foreach ($dirs as $v) {
        if ($v = str_replace($path, '', $v)) {
            $res[] = trim($v, DIRECTORY_SEPARATOR);
        }
    }

    foreach ($res as $v) {
        \Bitrix\Main\Data\Cache::createInstance()->cleanDir(
            implode(
                DIRECTORY_SEPARATOR,
                [
                    SITE_ID,
                    'kelnik',
                    $v
                ]
            )
        );
    }

    return true;
}
