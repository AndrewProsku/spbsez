<?php

namespace Kelnik\News\News;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
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

            new ReferenceField(
                'NEWS',
                NewsTable::class,
                [
                    'this.ENTITY_ID' => 'ref.ID'
                ]
            )
        ];
    }
}
