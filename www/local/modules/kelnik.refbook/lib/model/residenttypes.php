<?php

namespace Kelnik\Refbook\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ResidentTypesTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_refbook_resident_types';
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

            new Main\Entity\ReferenceField(
                'RESIDENTS',
                ResidentTable::class,
                [
                    '=this.ID' => 'ref.TYPE_ID'
                ]
            )
        ];
    }
}
