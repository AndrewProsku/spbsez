<?php

namespace Kelnik\Messages\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;
use Kelnik\Userdata\Profile\ProfileModel;

Loc::loadMessages(__FILE__);

class MessageUsersTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_messages_users';
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return [
            new Main\Entity\IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true
                ]
            ),
            new Main\Entity\IntegerField('MESSAGE_ID'),
            new Main\Entity\IntegerField('USER_ID'),
            new Main\Entity\DatetimeField('DATE_MODIFIED'),
            new Main\Entity\StringField(
                'IS_NEW',
                [
                    'default_value' => self::YES
                ]
            )
        ];
    }

    public static function getAdminAssocList(): array
    {
        $res = [];

        $tmp = \CUser::GetList(
            ($by = 'ID'),
            ($order = 'DESC'),
            [
                'GROUPS_ID' => ProfileModel::GROUP_RESIDENT_ADMIN
            ],
            [
                'SELECT' => [],
                'FIELDS' => [
                    'ID', 'WORK_COMPANY'
                ]
            ]
        );

        if (!$tmp->AffectedRowsCount()) {
            return $res;
        }

        while ($row = $tmp->Fetch()) {
            $res[$row['ID']] = '[' . $row['ID'] . '] ' . $row['WORK_COMPANY'];
        }

        return $res;
    }

    public static function add(array $data)
    {
        $data['DATE_CREATED'] = $data['DATE_MODIFIED'] = new Main\Type\DateTime();

        return parent::add($data);
    }
}
