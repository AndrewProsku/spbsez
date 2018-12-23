<?php

namespace Kelnik\Userdata;

use Kelnik\Helpers\ArrayHelper;

class Data
{
    public static function getUserInfo($userId)
    {
        $userId = (int) $userId;

        if (!$userId) {
            return [];
        }

        $additionalFields = self::getUserFields();

        $userData = \CUser::GetList(
            ($by = 'ID'),
            ($order = 'ASC'),
            [
                'ID' => $userId
            ],
            [
                'SELECT' => $additionalFields
            ]
        )->fetch();

        if (!$userData) {
            return [];
        }

        $res = [
            'STATUS' => 'Супер-Администратор',
        ];

        foreach ($additionalFields as $field) {
            $res[$field] = ArrayHelper::getValue($userData, $field);
        }

        $res['FULL_NAME'] = implode(' ', [$res['LAST_NAME'], $res['NAME'], $res['SECOND_NAME']]);

        return $res;
    }

    public static function getUserFields()
    {
        return [
            'NAME',
            'LAST_NAME',
            'SECOND_NAME',
            'EMAIL',
            'PERSONAL_PHONE',
            'WORK_COMPANY',
            'WORK_PHONE',
            'WORK_FAX',
            'UF_INN',
            'UF_EMAIL',
            'UF_OWNER_FIO',
            'UF_ADDR_LEGAL',
            'UF_ADDR_POST'
        ];
    }
}