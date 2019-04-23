<?php

namespace Kelnik\UserData;

use CUser;
use Exception;
use Kelnik\Userdata\Profile\Profile;

class EventHandler
{
    public static function onBeforeUserDelete($userId)
    {
        global $APPLICATION, $USER;

        $userGroups = CUser::GetUserGroup($USER->GetID());

        if (!$userGroups || !in_array(Profile::GROUP_SUPER_ADMIN, $userGroups)) {
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
