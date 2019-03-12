<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Join;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ReportFieldsTable extends DataManager
{
    public const FORM_COMMON = 0;
    public const FORM_TAXES = 1;
    public const FORM_BUILDING = 2;
    public const FORM_RENT = 3;
    public const FORM_INDICATORS = 4;
    public const FORM_ADDITIONAL_INDICATORS = 5;
    public const FORM_RESULT = 6;

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_report_fields';
    }

    public static function getCollectionClass()
    {
        return Fields::class;
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

            (new IntegerField('REPORT_ID'))
                ->configureRequired(true),

            (new StringField('PARENT_FIELD_NAME')),
            (new StringField('FIELD_NAME')),
            (new StringField('VALUE')),

            (new Reference(
                'REPORT',
                ReportsTable::class,
                Join::on('this.REPORT_ID', 'ref.ID')
            ))->configureJoinType('INNER')
        ];
    }

    public static function getForms()
    {
        return [
            self::FORM_COMMON => [],
            self::FORM_TAXES => [],
            self::FORM_BUILDING => [],
            self::FORM_RENT => [],
            self::FORM_INDICATORS => [],
            self::FORM_ADDITIONAL_INDICATORS => [],
            self::FORM_RESULT => [
                'type' => 'results'
            ]
        ];
    }
}
