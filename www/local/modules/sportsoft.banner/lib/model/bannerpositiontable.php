<?php

namespace Sportsoft\Banner\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class BannerPositionTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'sportsoft_banner_position';
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
                    'title' => 'ID'
                ]
            ),
            new Main\Entity\StringField(
                'ACTIVE',
                [
                    'values' => ['N', 'Y'],
                    'default_value' => 'Y',
                    'title' => Loc::getMessage('SPORTSOFT_BANNER_POSITION_ACTIVE'),
                ]
            ),
            new Main\Entity\StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('SPORTSOFT_BANNER_POSITION_NAME'),
                ]
            ),
            new Main\Entity\StringField(
                'CODE',
                [
                    'title' => Loc::getMessage('SPORTSOFT_BANNER_POSITION_CODE'),
                ]
            ),
            new Main\Entity\ReferenceField(
                'BANNER',
                BannerTable::class,
                [
                    '=this.ID' => 'ref.POSITION_ID'
                ]
            )
        ];
    }
}
