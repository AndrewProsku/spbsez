<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Entity\Event;
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
use Kelnik\Report\Events;
use Kelnik\UserData\Profile\Profile;

Loc::loadMessages(__FILE__);

class ReportsTable extends DataManager
{
    public const BASE_URL = 'cabinet/report/';

    public const TYPE_QUARTER_1 = 1;
    public const TYPE_QUARTER_2 = 2;
    public const TYPE_QUARTER_3 = 3;
    public const TYPE_PRELIMINARY_ANNUAL = 4;
    public const TYPE_ANNUAL = 5;

    public const NEW_ROW_PREFIX = 'new-';
    public const LOCK_TIME_LEFT = 900; // 15 min

    protected static $completeYear = [];

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

            (new BooleanField('IS_PRE_FILLED'))
                ->configureValues(self::NO, self::YES)
                ->configureDefaultValue(self::NO)
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_IS_PRE_FILLED')),

            (new StringField('NAME'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_NAME')),

            (new StringField('NAME_SEZ'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_NAME_SEZ')),

            (new StringField('NAME_COMMENT'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_NAME_COMMENT')),

            (new StringField('NAME_SEZ_COMMENT'))
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_NAME_SEZ_COMMENT')),

            (new Reference(
                'STATUS',
                StatusTable::class,
                Join::on('this.STATUS_ID', 'ref.ID')
            ))->configureJoinType('LEFT'),

            (new OneToMany('FIELDS', ReportFieldsTable::class, 'REPORT')),
            (new OneToMany('GROUPS', ReportFieldsGroupTable::class, 'REPORT'))
        ];
    }

    public static function getObjectClass()
    {
        return Report::class;
    }

    public static function update($id, $data)
    {
        $data['DATE_MODIFIED'] = new DateTime();
        $data['MODIFIED_BY'] = self::getUserId();

        return parent::update($id, $data);
    }

    public static function onAfterAdd(Event $event)
    {
        // Добавляем группы полей и некоторые поля по-умолчанию
        //
        ReportFieldsGroupTable::addReportGroups((int)  ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0));

        static::clearComponentCache($event);
        parent::onAfterAdd($event);
    }

    public static function onBeforeAdd(\Bitrix\Main\ORM\Event $event)
    {
        Events::setStatus($event);
        parent::onBeforeAdd($event);
    }

    public static function onAfterUpdate(Event $event)
    {
        $id = ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0);

        if (!$id) {
            parent::onAfterUpdate($event);

            return;
        }

        try {
            $status = (int) ArrayHelper::getValue(self::getRowByIdCached($id), 'STATUS_ID');

            if ($status === StatusTable::DONE) {
                $sqlHelper = Application::getConnection()->getSqlHelper();
                Application::getConnection()->query(
                    'UPDATE `' . ReportFieldsTable::getTableName() . '` ' .
                    'SET `COMMENT` = NULL ' .
                    'WHERE `REPORT_ID` = ' . $sqlHelper->convertToDbInteger($id)
                );
            } elseif ($status === StatusTable::CHECKING) {
                $sqlHelper = Application::getConnection()->getSqlHelper();
                Application::getConnection()->query(
                    'UPDATE `' . ReportFieldsTable::getTableName() . '` ' .
                    'SET `IS_PRE_FILLED` = ' . $sqlHelper->convertToDbString(ReportFieldsTable::NO) . ' ' .
                    'WHERE `REPORT_ID` = ' . $sqlHelper->convertToDbInteger($id)
                );
            }
        } catch (\Exception $e) {
        }

        Events::sendNotify($event);
        parent::onAfterUpdate($event);
    }

    public static function onBeforeDelete(Event $event)
    {
        $id  = ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0);

        try {
            if ($id) {
                $sqlHelper = Application::getConnection()->getSqlHelper();
                Application::getConnection()->query(
                    'DELETE FROM `' . ReportFieldsTable::getTableName() . '` ' .
                    'WHERE `REPORT_ID` = ' . $sqlHelper->convertToDbInteger($id)
                );
                Application::getConnection()->query(
                    'DELETE FROM `' . ReportFieldsGroupTable::getTableName() . '` ' .
                    'WHERE `REPORT_ID` = ' . $sqlHelper->convertToDbInteger($id)
                );
            }
        } catch (\Exception $e) {
        }

        parent::onBeforeDelete($event);
    }

    public static function clearComponentCache(Event $event)
    {
        global $USER;

        try {
            $id = ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0);
            $companyId = Context::getCurrent()->getRequest()->isAdminSection()
                        ? ReportsTable::getByPrimary($id)->fetchObject()->getCompanyId()
                        : Profile::getInstance((int)$USER->GetID())->getCompanyId();

            if (!$id && !$companyId) {
                return;
            }

            Application::getInstance()->getTaggedCache()->clearByTag('kelnik:reportList_' . $companyId);
            if ($id) {
                Application::getInstance()->getTaggedCache()->clearByTag('kelnik:report_' . $companyId . '_' . $id);
            }
        } catch (\Exception $e) {
        }
    }

    public static function getTypes()
    {
        return [
            self::TYPE_QUARTER_1          => Loc::getMessage('KELNIK_REPORT_TYPE_1'),
            self::TYPE_QUARTER_2          => Loc::getMessage('KELNIK_REPORT_TYPE_2'),
            self::TYPE_QUARTER_3          => Loc::getMessage('KELNIK_REPORT_TYPE_3'),
            self::TYPE_PRELIMINARY_ANNUAL => Loc::getMessage('KELNIK_REPORT_TYPE_SEMI_ANNUAL'),
            self::TYPE_ANNUAL             => Loc::getMessage('KELNIK_REPORT_TYPE_ANNUAL')
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
            self::TYPE_QUARTER_1 => [
                'start' => mktime(0, 0, 0, 4, 1, $year),
                'end' => mktime(23, 59, 59, 4, 31, $year)
            ],
            self::TYPE_QUARTER_2 => [
                'start' => mktime(0, 0, 0, 7, 1, $year),
                'end' => mktime(23, 59, 59, 7, 31, $year)
            ],
            self::TYPE_QUARTER_3 => [
                'start' => mktime(0, 0, 0, 9, 1, $year),
                'end' => mktime(23, 59, 59, 9, 30, $year)
            ],
            self::TYPE_PRELIMINARY_ANNUAL => [
                'start' => mktime(0, 0, 0, 1, 8, $year + 1),
                'end' => mktime(23, 59, 59, 1, 31, $year + 1)
            ],
            self::TYPE_ANNUAL => [
                'start' => mktime(0, 0, 0, 4, 1, $year + 1),
                'end' => mktime(23, 59, 59, 4, 31, $year + 1)
            ]
        ];
    }

    public static function getUserId()
    {
        global $USER;

        return (int) $USER->GetID();
    }

    /**
     * @param int $companyId
     * @param int $id
     *
     * @return Report|bool
     */
    public static function getReport(int $companyId, int $id)
    {
        if (!$id || !$companyId) {
            return false;
        }

        try {
            $res = self::getList([
                'select' => [
                    '*', 'STATUS'
                ],
                'filter' => [
                    '=ID' => $id,
                    '=COMPANY_ID' => $companyId
                ],
                'limit' => 1
            ])->fetchObject();
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }

    /**
     * Проверка списка отчетов за год.
     * Если список отчетов пуст, либо все отчеты приняты, год считается как сдан.
     *
     * @param int $companyId
     * @param int $year
     * @return bool
     */
    public static function yearIsComplete(int $companyId, int $year)
    {
        if (isset(self::$completeYear[$companyId . '_' . $year])) {
            return self::$completeYear[$companyId . '_' . $year];
        }

        try {
            $reports = self::getList([
                'filter' => [
                    '=YEAR'       => $year,
                    '=COMPANY_ID' => $companyId
                ],
                'order' => [
                    'TYPE' => 'ASC'
                ]
            ])->fetchCollection();
        } catch (\Exception $e) {
            $reports = new \Kelnik\Report\Model\EO_Reports_Collection();
        }

        if (!$reports->count()) {
            return self::$completeYear[$companyId . '_' . $year] = true;
        }

        if ($reports->count() < count(self::getTypes())) {
            return self::$completeYear[$companyId . '_' . $year] = false;
        }

        foreach ($reports as $report) {
            if (!$report->isComplete()) {
                return self::$completeYear[$companyId . '_' . $year] = false;
            }
        }

        return self::$completeYear[$companyId . '_' . $year] = true;
    }

    /**
     * Проверка предыдущих отчетов в течение года
     *
     * @param int $companyId ID компании
     * @param int $type период отчета
     * @param int $year год
     * @return bool
     */
    public static function prevRequired(int $companyId, int $type, int $year)
    {
        if ($type === ReportsTable::TYPE_QUARTER_1) {
            return !ReportsTable::yearIsComplete($companyId, $year - 1);
        }

        try {
            $reports = self::getList([
                'filter' => [
                    '=YEAR' => $year,
                    '=COMPANY_ID' => $companyId,
                    '<TYPE' => $type
                ],
                'order' => [
                    'TYPE' => 'ASC'
                ]
            ])->fetchCollection();
        } catch (\Exception $e) {
            $reports = new \Kelnik\Report\Model\EO_Reports_Collection();
        }

        if (!$reports->count()) {
            return false;
        }

        foreach ($reports as $report) {
            if (!$report->isComplete()) {
                return true;
            }
        }

        return false;
    }

    public static function getCurrentTime()
    {
        // TODO: restore real date
        return mktime(0, 0, 0, 7, 2, 2019); // time();
    }
}
