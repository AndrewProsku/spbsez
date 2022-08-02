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

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

Loader::includeModule('highloadblock'); 

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
    public const ENV_PRODUCTION = 'prod';

    protected static $completeYear = [];
    protected static $env = false;

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

            /* ALTER TABLE kelnik_report ADD COLUMN IS_REDACTING INTEGER(1) NOT NULL DEFAULT 0 AFTER IS_LOCKED; */
            (new IntegerField('IS_REDACTING'))
                ->configureDefaultValue(0)
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_IS_REDACTING')),

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

        $nextYear = $year + 1;

        //settings from HLIB for report periods
        $hlbl = 2;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
        $entity_data_class = $entity->getDataClass(); 
        $rsData = $entity_data_class::getList(array(
           'select' => array('*'),
           'order' => array('ID' => 'ASC'),
           'filter' => array()
        ));
        $arPeriods = array();
        while ($arData = $rsData->Fetch()) {
           $arPeriods[$arData['UF_CODE']] = [
                'START' => $arData['UF_DATE_START'],
                'END' => $arData['UF_DATE_END']
           ];
        }

        if (!empty($arPeriods['FIRST_QUARTER']['START']) && !empty($arPeriods['FIRST_QUARTER']['END'])) {
            $arStart = explode('.', $arPeriods['FIRST_QUARTER']['START']);
            $arEnd = explode('.', $arPeriods['FIRST_QUARTER']['END']);
            $firstQuarterStart = mktime(0, 0, 0, $arStart[1], $arStart[0], $year);
            $firstQuarterEnd = mktime(0, 0, 0, $arEnd[1], $arEnd[0], $year);
        } else {
            $firstQuarterStart = mktime(0, 0, 0, 4, 1, $year);
            $firstQuarterEnd = mktime(23, 59, 59, 4, 30, $year);
        }

        if (!empty($arPeriods['SECOND_QUARTER']['START']) && !empty($arPeriods['SECOND_QUARTER']['END'])) {
            $arStart = explode('.', $arPeriods['SECOND_QUARTER']['START']);
            $arEnd = explode('.', $arPeriods['SECOND_QUARTER']['END']);
            $secondQuarterStart = mktime(0, 0, 0, $arStart[1], $arStart[0], $year);
            $secondQuarterEnd = mktime(0, 0, 0, $arEnd[1], $arEnd[0], $year);
        } else {
            $secondQuarterStart = mktime(0, 0, 0, 8, 1, $year);
            $secondQuarterEnd = mktime(23, 59, 59, 9, 30, $year);
        }

        if (!empty($arPeriods['THIRD_QUARTER']['START']) && !empty($arPeriods['THIRD_QUARTER']['END'])) {
            $arStart = explode('.', $arPeriods['THIRD_QUARTER']['START']);
            $arEnd = explode('.', $arPeriods['THIRD_QUARTER']['END']);
            $thirdQuarterStart = mktime(0, 0, 0, $arStart[1], $arStart[0], $year);
            $thirdQuarterEnd = mktime(0, 0, 0, $arEnd[1], $arEnd[0], $year);
        } else {
            $thirdQuarterStart = mktime(0, 0, 0, 10, 1, $year);
            $thirdQuarterEnd = mktime(23, 59, 59, 11, 30, $year);
        }

        if (!empty($arPeriods['PRE_ANNUAL']['START']) && !empty($arPeriods['PRE_ANNUAL']['END'])) {
            $arStart = explode('.', $arPeriods['PRE_ANNUAL']['START']);
            $arEnd = explode('.', $arPeriods['PRE_ANNUAL']['END']);            
            $preAnnualStart = mktime(0, 0, 0, $arStart[1], $arStart[0], $nextYear);
            $preAnnualEnd = mktime(0, 0, 0, $arEnd[1], $arEnd[0], $nextYear);
        } else {
            $preAnnualStart = mktime(0, 0, 0, 1, 8, $nextYear);
            $preAnnualEnd = mktime(23, 59, 59, 1, 31, $nextYear);
        }

        if (!empty($arPeriods['ANNUAL']['START']) && !empty($arPeriods['ANNUAL']['END'])) {
            $arStart = explode('.', $arPeriods['ANNUAL']['START']);
            $arEnd = explode('.', $arPeriods['ANNUAL']['END']);
            $annualStart = mktime(0, 0, 0, $arStart[1], $arStart[0], $nextYear);
            $annualEnd = mktime(0, 0, 0, $arEnd[1], $arEnd[0], $nextYear);
        } else {
            $annualStart = mktime(0, 0, 0, 4, 1, $nextYear);
            $annualEnd = mktime(23, 59, 59, 4, 30, $nextYear);
        }

        return [
            self::TYPE_QUARTER_1 => [
                'start' => $firstQuarterStart,
                'end' => $firstQuarterEnd
            ],
            self::TYPE_QUARTER_2 => [
                'start' => $secondQuarterStart,
                'end' => $secondQuarterEnd
            ],
            self::TYPE_QUARTER_3 => [
                'start' => $thirdQuarterStart,
                'end' => $thirdQuarterEnd
            ],
            self::TYPE_PRELIMINARY_ANNUAL => [
                'start' => $preAnnualStart,
                'end' => $preAnnualEnd
            ],
            self::TYPE_ANNUAL => [
                'start' => $annualStart,
                'end' => $annualEnd
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
                    'TYPE' => 'DESC'
                ]
            ])->fetchCollection();
        } catch (\Exception $e) {
            $reports = new \Kelnik\Report\Model\EO_Reports_Collection();
        }

        if (!$reports->count()) {
            return self::$completeYear[$companyId . '_' . $year] = true;
        }

        $reports->rewind();
        $lastReport = $reports->current();
        $isYearComplete = ($lastReport->getType() == self::TYPE_ANNUAL && $lastReport->isComplete());

        return self::$completeYear[$companyId . '_' . $year] = $isYearComplete;
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
        $envPath = Application::getDocumentRoot() . '/../env.lock';

        if (!self::$env && file_exists($envPath)) {
            self::$env = trim(file_get_contents($envPath));
        }

        if (!self::$env) {
            self::$env = self::ENV_PRODUCTION;
        }

        return self::$env === self::ENV_PRODUCTION
                ? time()
                : mktime(0, 0, 0, 4, 2, 2019);
    }
}
