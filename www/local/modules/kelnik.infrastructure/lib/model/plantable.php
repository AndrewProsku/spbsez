<?php

namespace Kelnik\Infrastructure\Model;

use Bitrix\Main\Entity\Event;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Join;
use Kelnik\Refbook\Model\ResidentTable;

Loc::loadMessages(__FILE__);

class PlanTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_infrastructure_platform_plan';
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
            (new IntegerField('RESIDENT_ID'))
                ->configureDefaultValue(0)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_RESIDENT')),
            (new IntegerField('RESIDENT_IMAGE'))
                ->configureDefaultValue(0)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_RESIDENT_IMAGE')),

            (new BooleanField('ACTIVE'))
                ->configureValues(self::NO, self::YES)
                ->configureDefaultValue(self::NO)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_ACTIVE')),
            (new BooleanField('HEAT'))
                ->configureValues(self::NO, self::YES)
                ->configureDefaultValue(self::NO)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_HEAT')),
            (new BooleanField('ELECTRICITY'))
                ->configureValues(self::NO, self::YES)
                ->configureDefaultValue(self::NO)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_ELECTRICITY')),
            (new BooleanField('WATER'))
                ->configureValues(self::NO, self::YES)
                ->configureDefaultValue(self::NO)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_WATER')),
            (new BooleanField('STORM_SEWER'))
                ->configureValues(self::NO, self::YES)
                ->configureDefaultValue(self::NO)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_STORM_SEWER')),

            (new StringField('AREA'))
                ->configureSize(50)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_AREA')),
            (new StringField('PRICE_RU'))
                ->configureSize(100)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_PRICE_RU')),
            (new StringField('PRICE_EN'))
                ->configureSize(100)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_PRICE_EN')),
            (new StringField('RENT_RU'))
                ->configureSize(100)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_RENT_RU')),
            (new StringField('RENT_EN'))
                ->configureSize(100)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_RENT_EN')),

            (new StringField('COORDS'))
                ->configureSize(255)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_COORDS')),

            (new Reference(
                'PLATFORM',
                PlatformTable::class,
                Join::on('this.PLATFORM_ID', 'ref.ID')
            ))->configureJoinType('INNER'),

            (new Reference(
                'RESIDENT',
                ResidentTable::class,
                Join::on('this.RESIDENT_ID', 'ref.ID')
            ))->configureJoinType('LEFT')
        ];
    }

    public static function replaceFieldsByLang(array $fields, $langName)
    {
        if (!$fields || !$langName) {
            return $fields;
        }

        $langName = mb_strtoupper($langName);

        foreach ($fields as $key => $val) {
            if (!isset($fields[$key . '_' . $langName])) {
                continue;
            }
            $fields[$key] = $fields[$key . '_' . $langName];
        }

        return $fields;
    }

    public static function getFeatures()
    {
        return [
            'HEAT' => [
                'ICON' => 'heating',
                'NAME' => Loc::getMessage('KELNIK_INFRASTRUCTURE_HEAT')
            ],
            'ELECTRICITY' => [
                'ICON' => 'electricity',
                'NAME' => Loc::getMessage('KELNIK_INFRASTRUCTURE_ELECTRICITY')
            ],
            'WATER' => [
                'ICON' => 'water',
                'NAME' => Loc::getMessage('KELNIK_INFRASTRUCTURE_WATER')
            ],
            'STORM_SEWER' => [
                'ICON' => 'sanitation',
                'NAME' => Loc::getMessage('KELNIK_INFRASTRUCTURE_STORM_SEWER')
            ],
        ];
    }

    public static function clearComponentCache(Event $event)
    {
        parent::clearComponentCache($event); // TODO: Change the autogenerated stub
    }
}
