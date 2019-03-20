<?php

namespace Kelnik\Infrastructure\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class PlatformTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_infrastructure_platform';
    }

    public static function getMap()
    {
        $res = [
            (new IntegerField('ID'))
                ->configureTitle('ID')
                ->configurePrimary(true)
                ->configureAutocomplete(true),

            (new IntegerField('SORT'))
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_SORT'))
                ->configureDefaultValue(self::SORT_DEFAULT),

            (new IntegerField('IMAGE_ID'))
                ->configureDefaultValue(0)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_IMAGE')),
            (new IntegerField('VIDEO_ID'))
                ->configureDefaultValue(0)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_VIDEO')),

            (new BooleanField('ACTIVE'))
                ->configureValues(self::NO, self::YES)
                ->configureDefaultValue(self::YES)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_ACTIVE')),

            (new StringField('NAME_RU'))
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_NAME'))
                ->configureSize(255),
            (new StringField('NAME_EN'))
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_NAME_EN'))
                ->configureSize(255),

            (new StringField('MAP_COORDS_LAT'))
                ->configureSize(50)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_MAP_COORDS_LAT')),
            (new StringField('MAP_COORDS_LNG'))
                ->configureSize(50)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_MAP_COORDS_LNG')),

            (new TextField('TEXT_RU'))
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_TEXT')),
            (new TextField('TEXT_EN'))
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_TEXT_EN')),

            (new StringField('TEXT_RU_TEXT_TYPE'))
                ->configureDefaultValue('html'),
            (new StringField('TEXT_EN_TEXT_TYPE'))
                ->configureDefaultValue('html'),

            (new OneToMany(
                'TEXT_FIELDS',
                PlatformTextTable::class,
                'PLATFORM'
            ))
        ];

        foreach (self::getFields() as $field) {
            foreach (self::getLangs() as $fieldLang) {
                $res[] = (new ExpressionField(
                            $field . '_' . $fieldLang,
                    '(SELECT `TEXT_' . $fieldLang . '` FROM `' . PlatformTextTable::getTableName() . '` WHERE `PLATFORM_ID`=%s AND `TYPE`=\'' . $field . '\')',
                    'ID'
                        ))->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_' . $field . '_' . $fieldLang));
            }
        }

        return $res;
    }

    public static function getLangs()
    {
        return ['RU', 'EN'];
    }

    public static function getFields()
    {
        return [
            'TEXT_FEATURES',
            'TEXT_TRAITS',
            'TEXT_AREA',
            'TEXT_MAP',
            'TEXT_INFRA',
            'TEXT_CUSTOMS',
            'TEXT_GALLERY',
            'TEXT_ADVANTAGES1',
            'TEXT_ADVANTAGES2',
            'TEXT_ADVANTAGES3'
        ];
    }
}
