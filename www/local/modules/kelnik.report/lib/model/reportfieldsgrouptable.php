<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Join;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ReportFieldsGroupTable extends DataManager
{
    public const TYPE_STAGE = 'stages';
    public const TYPE_GROUP = 'groups';
    public const TYPE_INNOVATION = 'innovations';

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_report_fields_group';
    }

    public static function getCollectionClass()
    {
        return FieldsGroup::class;
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
                ->configureDefaultValue(0),
            (new IntegerField('FORM_NUM'))
                ->configureDefaultValue(0),

            (new StringField('TYPE'))
                ->configureDefaultValue(null),

            (new Reference(
                'REPORT',
                ReportsTable::class,
                Join::on('this.REPORT_ID', 'ref.ID')
            ))->configureJoinType('INNER')
        ];
    }

    public static function getGroupFields()
    {
        return [
            ReportFieldsGroupTable::TYPE_STAGE => [
                ReportFieldsTable::FORM_BUILDING,
                ReportFieldsTable::FORM_RENT
            ],
            ReportFieldsGroupTable::TYPE_GROUP => [
                ReportFieldsTable::FORM_INDICATORS
            ],
            ReportFieldsGroupTable::TYPE_INNOVATION => [
                ReportFieldsTable::FORM_INDICATORS
            ]
        ];
    }

    /**
     * Создаем записи множественных полей для формы
     *
     * @param $id
     */
    public static function addReportGroups($id)
    {
        if (!$id) {
            return;
        }

        $sqlHelper = Application::getConnection()->getSqlHelper();
        $sql = "INSERT INTO `" . ReportFieldsGroupTable::getTableName() . "` (`REPORT_ID`, `FORM_NUM`, `TYPE`) VALUES ";
        $rows = [];

        foreach (self::getGroupFields() as $type => $formNums) {
            foreach ($formNums as $formNum) {
                $rows[] = "(" .
                            $sqlHelper->convertToDbInteger($id) . ", " .
                            $sqlHelper->convertToDbInteger($formNum) . ", " .
                            $sqlHelper->convertToDbString($type)
                            . ")";
            }
        }

        if (!$rows) {
            return;
        }

        $sql .= implode(', ', $rows);

        try {
            Application::getConnection()->query($sql);
        } catch (\Exception $e) {
        }
    }
}
