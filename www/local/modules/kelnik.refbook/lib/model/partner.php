<?php

namespace Kelnik\Refbook\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class PartnerTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_refbook_partner';
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
                    'title' => Loc::getMessage('KELNIK_PARTNER_ID')
                ]
            ),
            new Main\Entity\IntegerField(
                'IMAGE_ID',
                [
                    'title' => Loc::getMessage('KELNIK_PARTNER_IMAGE'),
                ]
            ),
            new Main\Entity\IntegerField(
                'IMAGE_ID_EN',
                [
                    'title' => Loc::getMessage('KELNIK_PARTNER_IMAGE_EN'),
                ]
            ),
            new Main\Entity\IntegerField(
                'SORT',
                [
                    'default_value' => 500,
                    'title' => Loc::getMessage('KELNIK_PARTNER_SORT'),
                ]
            ),
            new Main\Entity\StringField(
                'ACTIVE',
                [
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES,
                    'title' => Loc::getMessage('KELNIK_PARTNER_ACTIVE'),
                ]
            ),
            new Main\Entity\StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_PARTNER_NAME'),
                ]
            ),
            new Main\Entity\StringField(
                'NAME_EN',
                [
                    'title' => Loc::getMessage('KELNIK_PARTNER_NAME_EN'),
                ]
            ),
            new Main\Entity\StringField('TEXT_TEXT_TYPE'),
            new Main\Entity\TextField(
                'TEXT',
                [
                    'title' => Loc::getMessage('KELNIK_PARTNER_TEXT'),
                ]
            )
        ];
    }
}
