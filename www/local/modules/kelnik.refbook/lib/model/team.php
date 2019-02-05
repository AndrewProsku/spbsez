<?php

namespace Kelnik\Refbook\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class TeamTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_refbook_team';
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
                    'title' => Loc::getMessage('KELNIK_TEAM_ID')
                ]
            ),
            new Main\Entity\IntegerField(
                'IMAGE_ID',
                [
                    'title' => Loc::getMessage('KELNIK_TEAM_IMAGE'),
                ]
            ),
            new Main\Entity\IntegerField(
                'SORT',
                [
                    'default_value' => 500,
                    'title' => Loc::getMessage('KELNIK_TEAM_SORT'),
                ]
            ),
            new Main\Entity\StringField(
                'ACTIVE',
                [
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES,
                    'title' => Loc::getMessage('KELNIK_TEAM_ACTIVE'),
                ]
            ),
            new Main\Entity\StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_TEAM_NAME'),
                ]
            ),
            new Main\Entity\StringField(
                'TEXT',
                [
                    'title' => Loc::getMessage('KELNIK_TEAM_DESCR'),
                ]
            )
        ];
    }
}
