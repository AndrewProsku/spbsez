<?php

namespace Kelnik\Userdata;


use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class FieldTypeUser extends \CUserTypeInteger
{
    const USER_TYPE_ID = 'kelnikUserToUser';

//    public function InstallEvents()
//    {
//        $eventManager = \Bitrix\Main\EventManager::getInstance();
//
//        $eventManager->registerEventHandler(
//            'main',
//            'OnUserTypeBuildList',
//            $this->MODULE_ID,
//            \Kelnik\Userdata\FieldTypeUser::class,
//            'GetUserTypeDescription'
//        );
//    }
//
//    public function UnInstallEvents()
//    {
//        $eventManager = \Bitrix\Main\EventManager::getInstance();
//
//        $eventManager->UnregisterEventHandler(
//            'main',
//            'OnUserTypeBuildList',
//            $this->MODULE_ID,
//            \Kelnik\Userdata\FieldTypeUser::class,
//            'GetUserTypeDescription'
//        );
//    }

    public function GetUserTypeDescription()
    {
        return [
            'USER_TYPE_ID' => self::USER_TYPE_ID,
            'CLASS_NAME' => self::class,
            'DESCRIPTION' => Loc::getMessage('KELNIK_USER_TYPE_FIELD'),
            'BASE_TYPE' => \CUserTypeManager::BASE_TYPE_INT
        ];
    }

    public function GetDBColumnType($arUserField)
    {
        global $DB;

        $type = strtolower($DB->type);
        $types = [
            'mysql' => 'int(11)',
            'oracle' => 'number(11)',
            'mssql' => 'int'
        ];

        return isset($types[$type]) ? $types[$type] : '';
    }

    public function PrepareSettings($arUserField)
    {
        return [];
    }

    public function GetSettingsHTML($arUserField = false, $arHtmlControl, $bVarsFromForm)
    {
        return '';
    }

    public function GetFilterHTML($arUserField, $arHtmlControl)
    {
    }

    public function GetAdminListViewHTML($arUserField, $arHtmlControl)
    {
    }
}
