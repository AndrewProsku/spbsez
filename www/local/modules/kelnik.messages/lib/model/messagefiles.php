<?php

namespace Kelnik\Messages\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\IntegerField;
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
            (new IntegerField('ID'))
                ->configureAutocomplete(true)
                ->configurePrimary(true),
            new IntegerField('ENTITY_ID'),
            new IntegerField('VALUE')
        ];
    }
}
