<?php

namespace Kelnik\Messages\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Kelnik\Helpers\Database\DataManager;
use Kelnik\Userdata\Profile\Profile;

Loc::loadMessages(__FILE__);

class MessageCompaniesTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_messages_companies';
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return [
            (new IntegerField('ID'))
                ->configureAutocomplete(true)
                ->configurePrimary(true),

            new IntegerField('MESSAGE_ID'),
            new IntegerField('USER_ID'),

            (new Reference(
                'MESSAGE',
                MessagesTable::class,
                Join::on('this.MESSAGE_ID', 'ref.ID')
            ))->configureJoinType('INNER')
        ];
    }

    public static function getAdminAssocList(): array
    {
        $res = [];

        $tmp = \CUser::GetList(
            ($by = 'ID'),
            ($order = 'DESC'),
            [
                'GROUPS_ID' => Profile::GROUP_RESIDENT_ADMIN
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

    public static function getAdminTreeList(): array
    {
        $res = [];

        $tmp = \CUser::GetList(
            ($by = 'ID'),
            ($order = 'DESC'),
            [
                'GROUPS_ID' => Profile::GROUP_RESIDENT_ADMIN
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
            $res[$row['ID']] = $row['WORK_COMPANY'];
        }

        return $res;
    }
}
