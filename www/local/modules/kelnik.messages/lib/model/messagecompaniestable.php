<?php

namespace Kelnik\Messages\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Kelnik\Helpers\Database\DataManager;

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

            (new IntegerField('MESSAGE_ID'))
                ->configureDefaultValue(0),
            (new IntegerField('USER_ID'))
                ->configureDefaultValue(0),

            (new Reference(
                'MESSAGE',
                MessagesTable::class,
                Join::on('this.MESSAGE_ID', 'ref.ID')
            ))->configureJoinType('INNER')
        ];
    }
}
