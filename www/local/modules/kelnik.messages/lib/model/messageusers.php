<?php

namespace Kelnik\Messages\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

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
            ),

            (new Main\ORM\Fields\Relations\Reference(
                'MESSAGE',
                MessagesTable::class,
                Main\ORM\Query\Join::on('this.MESSAGE_ID', 'ref.ID')
            ))->configureJoinType('INNER')
        ];
    }

    public static function add(array $data)
    {
        $data['DATE_MODIFIED'] = new Main\Type\DateTime();

        return parent::add($data);
    }
}
