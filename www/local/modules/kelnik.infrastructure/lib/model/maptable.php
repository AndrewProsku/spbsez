<?php

namespace Kelnik\Infrastructure\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

class MapTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_infrastructure_platform_map';
    }

    public static function getMap()
    {
        return [
            (new IntegerField('ID'))
                ->configureTitle('ID')
                ->configurePrimary(true)
                ->configureAutocomplete(true),

            (new IntegerField('PLATFORM_ID'))
                ->configureDefaultValue(0)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_PLATFORM')),

            (new BooleanField('ACTIVE'))
                ->configureValues(self::NO, self::YES)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_ACTIVE')),
            (new BooleanField('MAKE_ROUTE'))
                ->configureValues(self::NO, self::YES)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_MAKE_ROUTE')),

            (new StringField('NAME_RU'))
                ->configureSize(255)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_NAME_RU')),
            (new StringField('NAME_EN'))
                ->configureSize(255)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_NAME_EN')),
            (new StringField('MAP_COORDS_LAT'))
                ->configureSize(50)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_MAP_COORDS_LAT')),
            (new StringField('MAP_COORDS_LNG'))
                ->configureSize(50)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_MAP_COORDS_LNG')),

            (new Reference(
                'PLATFORM',
                PlatformTable::class,
                Join::on('this.PLATFORM_ID', 'ref.ID')
            ))->configureJoinType('INNER')
        ];
    }
}
