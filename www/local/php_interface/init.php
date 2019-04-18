<?php

include realpath(__DIR__ . '/../../../vendor/autoload.php');

\Bitrix\Main\EventManager::getInstance()->addEventHandler("main", "OnBeforeEventAdd", "OnBeforeEventAddHandler");
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'main',
    'OnUserDelete',
    [
        SezHandler::class,
        'onUserDelete'
    ]
);
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'main',
    'OnBeforeUserDelete',
    [
        SezHandler::class,
        'onBeforeUserDelete'
    ]
);

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

class SezHandler
{
    public static function onBeforeUserDelete($userId)
    {
        global $APPLICATION, $USER;

        if (!\Bitrix\Main\Loader::includeModule('kelnik.userdata')) {
            return true;
        }

        $userGroups = CUser::GetUserGroup($USER->GetID());

        if (!$userGroups || !in_array(\Kelnik\Userdata\Profile\Profile::GROUP_SUPER_ADMIN, $userGroups)) {
            $APPLICATION->throwException('Недостаточно прав для удаления учетной записи');

            return false;
        }
    }

    public static function onUserDelete($userId)
    {
        if (!\Bitrix\Main\Loader::includeModule('kelnik.messages')) {
            return true;
        }

        $dbConn = \Bitrix\Main\Application::getConnection();

        $tables = [
            \Kelnik\Messages\Model\MessagesTable::getTableName(),
            \Kelnik\Messages\Model\NotifyTable::getTableName()
        ];

        try {
            foreach ($tables as $table) {
                $dbConn->query('DELETE FROM `' . $table . '` WHERE `USER_ID` = ' . $dbConn->getSqlHelper()->convertToDbInteger($userId));
            }
        } catch (Exception $e) {
        }

        return true;
    }
}

class SezLang
{
    public const CHINESE_DIR = '/ch/';
    public const ENGLISH_DIR = '/en/';
    public const RUSSIAN_DIR = '/';

    public static function getDirBySite($siteId)
    {
        $res = \Bitrix\Main\SiteTable::getById($siteId)->fetch();

        return isset($res['DIR']) ? $res['DIR'] : '/';
    }
}
