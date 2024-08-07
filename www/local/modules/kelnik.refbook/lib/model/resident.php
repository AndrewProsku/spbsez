<?php

namespace Kelnik\Refbook\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Bitrix\Main\Type\DateTime;

Loc::loadMessages(__FILE__);

class ResidentTable extends DataManager
{
    const PLACE_NEUDORF = 1;
    const PLACE_NOVOORLOVSKAYA = 2;
    const PLACE_PARNAS = 4;
    const PLACE_SHUSHARY = 5;
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_refbook_resident';
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
                    'autocomplete' => true,
                    'title' => Loc::getMessage('KELNIK_RESIDENT_ID')
                ]
            ),
            new Main\Entity\IntegerField(
                'TYPE_ID',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_TYPE')
                ]
            ),
            new Main\Entity\IntegerField(
                'PLACE',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_PLACE')
                ]
            ),
            new Main\Entity\IntegerField(
                'IMAGE_ID',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_IMAGE'),
                ]
            ),
            new Main\Entity\IntegerField(
                'IMAGE_ID_EN',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_IMAGE_EN'),
                ]
            ),
            new Main\Entity\IntegerField(
                'SORT',
                [
                    'default_value' => 500,
                    'title' => Loc::getMessage('KELNIK_RESIDENT_SORT'),
                ]
            ),
            new Main\Entity\StringField(
                'ACTIVE',
                [
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES,
                    'title' => Loc::getMessage('KELNIK_RESIDENT_ACTIVE'),
                ]
            ),
            new Main\Entity\StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_NAME'),
                ]
            ),
            new Main\Entity\StringField(
                'NAME_EN',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_NAME_EN'),
                ]
            ),
            new Main\Entity\StringField(
                'SITE',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_SITE'),
                ]
            ),
            new Main\Entity\StringField('TEXT_TEXT_TYPE'),
            new Main\Entity\StringField('TEXT_EN_TEXT_TYPE'),

            new Main\Entity\TextField(
                'TEXT',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_TEXT'),
                ]
            ),
            new Main\Entity\TextField(
                'TEXT_EN',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_TEXT_EN'),
                ]
            ),

            new Main\Entity\ReferenceField(
                'TYPE',
                ResidentTypesTable::class,
                [
                    '=this.TYPE_ID' => 'ref.ID'
                ]
            ),

            new Main\Entity\DatetimeField(
                'STATUS_DATE',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_STATUS_DATE'),
                    'default_value' => new DateTime()
                ]
            ),

            new Main\Entity\StringField(
                'PROJECT_STAGE',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_PROJECT_STAGE'),
                ]
            ),

            new Main\Entity\StringField(
                'PHONE',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_PHONE'),
                ]
            ),

            new Main\Entity\StringField(
                'ADDRESS',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_ADDRESS'),
                ]
            ),

            new Main\Entity\StringField(
                'ADDRESS_EN',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_ADDRESS_EN'),
                ]
            ),

            new Main\Entity\StringField(
                'PROJECT_STAGE_EN',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_PROJECT_STAGE_EN'),
                ]
            ),

            new Main\Entity\ReferenceField(
                'IMAGES',
                ImageToResidentTable::class,
                [
                    '=this.ID' => 'ref.ENTITY_ID',
                ]
            )
        ];
    }

    public static function getPlaces()
    {
        return [
            self::PLACE_NEUDORF => Loc::getMessage('KELNIK_RESIDENT_NEUDORF'),
            self::PLACE_NOVOORLOVSKAYA => Loc::getMessage('KELNIK_RESIDENT_NOVOORLOVSKAYA'),
            self::PLACE_PARNAS => Loc::getMessage('KELNIK_RESIDENT_PARNAS'),
            self::PLACE_SHUSHARY => Loc::getMessage('KELNIK_RESIDENT_SHUSHARY')
        ];
    }

    public static function getPlaceName($id)
    {
        return ArrayHelper::getValue(self::getPlaces(), $id);
    }

    public static function getPlaceLink($id)
    {
        return ArrayHelper::getValue(
            [
                self::PLACE_NEUDORF => '/infrastructure/noidorf/',
                self::PLACE_NOVOORLOVSKAYA => '/infrastructure/novoorlovskaya/',
                self::PLACE_PARNAS => '/infrastructure/parnas/',
                self::PLACE_SHUSHARY => '/infrastructure/shushary/'
            ],
            $id
        );
    }
}
