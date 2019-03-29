<?php

namespace Kelnik\Infrastructure\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ImagesEnTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_infrastructure_platform_images_en';
    }

    public static function getMap()
    {
        return [
            (new IntegerField('ID'))
                ->configureTitle('ID')
                ->configurePrimary(true)
                ->configureAutocomplete(true),

            (new IntegerField('ENTITY_ID'))
                ->configureDefaultValue(0),
            (new IntegerField('VALUE'))
                ->configureDefaultValue(0),

            (new Reference(
                'PLATFORM',
                PlatformTable::class,
                Join::on('this.ENTITY_ID', 'ref.ID')
            ))->configureJoinType('INNER')
        ];
    }
}
