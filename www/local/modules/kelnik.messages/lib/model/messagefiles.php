<?php

namespace Kelnik\Messages\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class MessageFilesTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_messages_files';
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
            new Main\Entity\IntegerField('ENTITY_ID'),
            new Main\Entity\IntegerField('VALUE')
        ];
    }
}
