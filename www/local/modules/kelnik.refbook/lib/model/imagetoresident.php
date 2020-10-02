<?php

namespace Kelnik\Refbook\Model;

use Bitrix\Main\Entity\IntegerField;
use Kelnik\Helpers\Database\DataManager;

class ImageToResidentTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_refbook_resident_images';
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                ]
            ),
            new IntegerField('ENTITY_ID'),
            new IntegerField('VALUE'),
        ];
    }
}