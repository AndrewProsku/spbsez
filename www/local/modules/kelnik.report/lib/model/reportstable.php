<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ReportsTable extends DataManager
{
    public const TYPE_1 = 1;
    public const TYPE_2 = 2;
    public const TYPE_3 = 3;
    public const TYPE_SEMI_ANNUAL = 4;
    public const TYPE_ANNUAL = 5;

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

            (new IntegerField('CREATED_BY'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_CREATED_BY')),

            (new IntegerField('MODIFIED_BY'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_MODIFIED_BY')),

            (new IntegerField('STATUS_ID'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_STATUS')),

            (new IntegerField('TYPE'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_TYPE')),

            (new IntegerField('YEAR'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_YEAR'))
                ->configureDefaultValue(date('Y')),

            (new DatetimeField('DATE_CREATED'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_DATE_CREATED')),

            (new DatetimeField('DATE_MODIFIED'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_DATE_MODIFIED')),

            (new BooleanField('IS_LOCKED'))
                ->configureStorageValues(self::NO, self::YES)
                ->configureDefaultValue(self::NO)
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_IS_LOCKED')),

            (new StringField('NAME'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_NAME')),

            (new StringField('NAME_RESIDENT'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_NAME_RESIDENT')),

            (new Reference(
                'STATUS',
                StatusTable::class,
                Join::on('this.STATUS_ID', 'ref.ID')
            ))->configureJoinType('LEFT'),

            (new OneToMany('FIELDS', ReportFieldsTable::class, 'REPORT'))
        ];
    }

    public static function getObjectClass()
    {
        return Report::class;
    }

    public static function getTypes()
    {
        return [
            self::TYPE_1 => Loc::getMessage('KELNIK_REPORT_TYPE_1'),
            self::TYPE_2 => Loc::getMessage('KELNIK_REPORT_TYPE_2'),
            self::TYPE_3 => Loc::getMessage('KELNIK_REPORT_TYPE_3'),
            self::TYPE_SEMI_ANNUAL => Loc::getMessage('KELNIK_REPORT_TYPE_SEMI_ANNUAL'),
            self::TYPE_ANNUAL => Loc::getMessage('KELNIK_REPORT_TYPE_ANNUAL')
        ];
    }

    public static function prepareType($number)
    {
        return ArrayHelper::getValue(self::getTypes(), $number, '');
    }

    public static function getTypePeriod($year = false)
    {
        if (!$year) {
            $year = date('Y');
        }

        return [
            self::TYPE_1 => [
                'start' => mktime(0, 0, 0, 4, 1, $year),
                'end' => mktime(23, 59, 59, 4, 31, $year)
            ],
            self::TYPE_2 => [
                'start' => mktime(0, 0, 0, 7, 1, $year),
                'end' => mktime(23, 59, 59, 7, 31, $year)
            ],
            self::TYPE_3 => [
                'start' => mktime(0, 0, 0, 9, 1, $year),
                'end' => mktime(23, 59, 59, 9, 30, $year)
            ],
            self::TYPE_SEMI_ANNUAL => [
                'start' => mktime(0, 0, 0, 1, 8, $year + 1),
                'end' => mktime(23, 59, 59, 1, 31, $year + 1)
            ],
            self::TYPE_ANNUAL => [
                'start' => mktime(0, 0, 0, 4, 1, $year + 1),
                'end' => mktime(23, 59, 59, 4, 31, $year + 1)
            ]
        ];
    }
}
