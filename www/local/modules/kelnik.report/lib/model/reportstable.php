<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Event;
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
use Kelnik\Userdata\Profile\Profile;

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
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_CREATED_BY'))
                ->configureDefaultValue(self::getUserId()),

            (new IntegerField('MODIFIED_BY'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_MODIFIED_BY'))
                ->configureDefaultValue(self::getUserId()),

            (new IntegerField('STATUS_ID'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_STATUS')),

            (new IntegerField('TYPE'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_TYPE')),

            (new IntegerField('YEAR'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_YEAR'))
                ->configureDefaultValue(date('Y')),

            (new DatetimeField('DATE_CREATED'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_DATE_CREATED'))
                ->configureDefaultValue(new DateTime()),

            (new DatetimeField('DATE_MODIFIED'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_DATE_MODIFIED'))
                ->configureDefaultValue(new DateTime()),

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

    public static function onAfterAdd(Event $event)
    {
        self::clearComponentCache($event);
        parent::onAfterAdd($event);
    }

    public static function onAfterUpdate(Event $event)
    {
        self::clearComponentCache($event);
        parent::onAfterUpdate($event);
    }

    public static function onBeforeDelete(Event $event)
    {
        self::clearComponentCache($event);
        parent::onBeforeDelete($event);
    }

    protected static function clearComponentCache(Event $event)
    {
        global $USER;

        try {
            $id = ArrayHelper::getValue($event->getParameter('id'), 'ID', 0);
            $companyId = Context::getCurrent()->getRequest()->isAdminSection()
                        ? ReportsTable::getByPrimary($id)->fetchObject()->getCompanyId()
                        : Profile::getInstance($USER->GetID())->getCompanyId();

            if (!$id && !$companyId) {
                return;
            }

            Application::getInstance()->getTaggedCache()->clearByTag('kelnik:report_list_' . $companyId);
            if ($id) {
                Application::getInstance()->getTaggedCache()->clearByTag('kelnik:report_' . $companyId . '_' . $id);
            }
        } catch (\Exception $e) {
        }
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

    public static function getTypeName($number)
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

    protected static function getUserId()
    {
        global $USER;

        return !empty($USER) && $USER->IsAuthorized()
                ? $USER->GetID()
                : 0;
    }
}
