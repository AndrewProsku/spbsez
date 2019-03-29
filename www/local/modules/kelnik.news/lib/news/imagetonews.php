<?php

namespace Kelnik\News\News;

use Bitrix\Main\Entity\IntegerField;
use Kelnik\Helpers\Database\DataManager;

class ImageToNewsTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_news_ref_images';
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
