<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\EnumField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
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
            (new IntegerField('ID'))
                ->configureAutocomplete(true)
                ->configurePrimary(true)
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_ID')),

            (new IntegerField('SORT'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_SORT'))
                ->configureDefaultValue(self::SORT_DEFAULT),

            (new EnumField('ACTIVE'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_ACTIVE'))
                ->configureValues([self::NO, self::YES])
                ->configureDefaultValue(self::YES),

            (new StringField('NAME'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_NAME'))
        ];
    }
}
