<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\EnumField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class StatusTable extends DataManager
{
    public const NEW = 1;
    public const CHECKING = 2;
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

    public static function getObjectClass()
    {
        return Status::class;
    }

    public static function getCssClassById($id)
    {
        return ArrayHelper::getValue(
            [
                self::NEW      => 'b-quarter_status_to-fill',
                self::CHECKING => 'b-quarter_status_check',
                self::DONE     => 'b-quarter_status_approved',
                self::DECLINED => 'b-quarter_status_rejected'
            ],
            $id,
            'b-quarter_status_approved'
        );
    }

    public static function getButtonNameById($id)
    {
        return ArrayHelper::getValue(
            [
                self::NEW => Loc::getMessage('KELNIK_REPORT_STATUS_BUTTON_NAME_FILL'),
                self::DECLINED => Loc::getMessage('KELNIK_REPORT_STATUS_BUTTON_NAME_EDIT')
            ],
            $id,
            Loc::getMessage('KELNIK_REPORT_STATUS_BUTTON_NAME_VIEW')
        );
    }

    public static function getNameById($id)
    {
        return $id
            ? ArrayHelper::getValue(self::getRowByIdCached($id), 'NAME')
            : 0;
    }
}
