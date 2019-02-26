<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class StatusTable extends DataManager
{
    public const NEW = 1;
    public const IN_PROGRESS = 2;
    public const DONE = 3;
    public const DECLINED = 4;

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_report_status';
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('KELNIK_REPORT_ID')
                ]
            ),
            new IntegerField(
                'SORT',
                [
                    'title' => Loc::getMessage('KELNIK_REPORT_SORT'),
                    'default_value' => self::SORT_DEFAULT
                ]
            ),
            new StringField(
                'ACTIVE',
                [
                    'title' => Loc::getMessage('KELNIK_REPORT_ACTIVE'),
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES
                ]
            ),
            new StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_REPORT_NAME')
                ]
            )
        ];
    }
}
