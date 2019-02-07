<?php

namespace Kelnik\Messages\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;
use Kelnik\Userdata\Profile\ProfileModel;

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
            new Main\Entity\IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true
                ]
            ),
            new Main\Entity\IntegerField('MESSAGE_ID'),
            new Main\Entity\IntegerField('USER_ID'),

            (new Main\ORM\Fields\Relations\Reference(
                'MESSAGE',
                MessagesTable::class,
                Main\ORM\Query\Join::on('this.MESSAGE_ID', 'ref.ID')
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
}
