<?php

namespace Sportsoft\Banner\Model;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class BannerTable extends DataManager
{
    public static function getTableName()
    {
        return 'sportsoft_banner';
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => 'ID'
                ]
            ),
            new StringField(
                'ACTIVE',
                [
                    'values' => ['N', 'Y'],
                    'title'  => Loc::getMessage('SPORTSOFT_BANNER_ACTIVE')
                ]
            ),

            new StringField(
                'NAME',
                [
                    'title'  => Loc::getMessage('SPORTSOFT_BANNER_NAME')
                ]
            ),

            new StringField(
                'NAME_EN',
                [
                    'title'  => Loc::getMessage('SPORTSOFT_BANNER_NAME_EN')
                ]
            ),
            
            new StringField(
                'SUBTITLE',
                [
                    'title' => Loc::getMessage('SPORTSOFT_BANNER_SUBTITLE')
                ]
            ),

            new StringField(
                'SUBTITLE_EN',
                [
                    'title' => Loc::getMessage('SPORTSOFT_BANNER_SUBTITLE_EN')
                ]
            ),

            new StringField(
                'LINK',
                [
                    'title'  => Loc::getMessage('SPORTSOFT_BANNER_LINK')
                ]
            ),

            new IntegerField(
                'IMAGE',
                [
                    'title'  => Loc::getMessage('SPORTSOFT_BANNER_IMAGE')
                ]
            ),

            new StringField(
                'OVERLAY',
                [
                    'title'  => Loc::getMessage('SPORTSOFT_BANNER_OVERLAY')
                ]
            ),

            new IntegerField(
                'POSITION_ID',
                [
                    'title'  => Loc::getMessage('SPORTSOFT_BANNER_POSITION_ID')
                ]
            ),

            new ReferenceField(
                'POSITION',
                BannerPositionTable::class,
                [
                    '=this.POSITION_ID' => 'ref.ID'
                ]
            )
        ];
    }
}
