<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Join;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ReportsTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_report';
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

            (new IntegerField('COMPANY_ID'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_COMPANY')),

            (new IntegerField('USER_ID'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_USER')),

            (new IntegerField('STATUS_ID'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_STATUS')),

            (new IntegerField('QUARTER'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_QUARTER')),

            (new IntegerField('YEAR'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_YEAR'))
                ->configureDefaultValue(date('Y')),

            (new DatetimeField('DATE_CREATED'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_DATE_CREATED')),

            (new DatetimeField('DATE_MODIFIED'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_DATE_MODIFIED')),

            (new StringField('NAME'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_NAME')),

            (new StringField('NAME_RESIDENT'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_NAME_RESIDENT')),

            (new Reference(
                'STATUS',
                StatusTable::class,
                Join::on('this.STATUS_ID', 'ref.ID')
            ))->configureJoinType('LEFT')
        ];
    }

    public static function getQuarters()
    {
        $res = [];

        for ($i = 1; $i<= 4; $i++) {
            $res[$i] = Loc::getMessage('KELNIK_REPORT_QUARTER_' . $i);
        }

        return $res;
    }

    public static function prepareQuarter($number)
    {
        return ArrayHelper::getValue(self::getQuarters(), $number, '');
    }
}
