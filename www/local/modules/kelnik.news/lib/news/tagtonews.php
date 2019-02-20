<?php

namespace Kelnik\News\News;

use Bitrix\Main\Entity\IntegerField;
use Kelnik\Helpers\Database\DataManager;

class TagToNewsTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_news_ref_tags';
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
