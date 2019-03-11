<?php

namespace Kelnik\Report\Component;

use Bex\Bbc;
use Bitrix\Main\Loader;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Report\Model\Report;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Report\Model\StatusTable;
use Kelnik\Userdata\Profile\Profile;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class ReportDetail extends Bbc\Basis
{
    use Bbc\Traits\Elements;

    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.report', 'kelnik.userdata', 'iblock'];
    protected $checkParams = [
        'ELEMENT_ID' => ['type' => 'int', 'error' => false],
        'CREATE_ELEMENT_TYPE' => ['type' => 'int', 'error' => false]
    ];

    /**
     * @var Profile
     */
    protected $profile;

    public function onPrepareComponentParams($arParams)
    {
        try {
            Loader::includeModule('kelnik.report');

            $arParams['CREATE_ELEMENT_TYPE'] = (int) str_replace(ReportsTable::NEW_ROW_PREFIX, '', $arParams['ELEMENT_ID']);

            if ((int) $arParams['ELEMENT_ID']
                || !in_array($arParams['CREATE_ELEMENT_TYPE'], array_keys(ReportsTable::getTypes()))
            ) {
                $arParams['CREATE_ELEMENT_TYPE'] = 0;
            }
        } catch (\Exception $e) {
            $arParams['CREATE_ELEMENT_TYPE'] = 0;
        }

        return parent::onPrepareComponentParams($arParams);
    }

    protected function executeProlog()
    {
        global $USER;

        if (!$this->arParams['ELEMENT_ID'] && !$this->arParams['CREATE_ELEMENT_TYPE']) {
            $this->return404(true);
        }

        $this->profile = Profile::getInstance($USER->GetID());

        Report::setUrlTemplate(
            $this->arParams['SEF_FOLDER'] .
            ArrayHelper::getValue($this->getParent()->arParams, 'SEF_URL_TEMPLATES.detail', '')
        );

        // Если отчет существует, то показываем карточку отчета
        //
        if ($this->arParams['ELEMENT_ID']) {
            return true;
        }

        // Поверяем возможность создания отчета.
        // Если создали, то переходим на карточку.
        //
        $res = $this->processNewReport($this->arParams['CREATE_ELEMENT_TYPE']);

        if (is_string($res)) {
            LocalRedirect($res);
        }

        $this->return404(true);
    }

    protected function executeMain()
    {
        self::registerCacheTag('kelnik:report_' . $this->profile->getCompanyId() . '_' . $this->arParams['ELEMENT_ID']);

        /* @var Report */
        $report = $this->getReport($this->arParams['ELEMENT_ID']);
        $prevYearRequired = false;

        if (!$report && $this->arParams['SET_404'] === 'Y') {
            $this->return404();
        }

        if ($report->getType() === ReportsTable::TYPE_1) {
            $prevYearRequired = !ReportsTable::yearIsComplete($this->profile->getCompanyId(), $report->getYear() - 1);
        }

        // Не заполнен предыдущий год
        //
        if ($prevYearRequired) {
            $this->abortResultCache();
            $this->arResult['PREV_YEAR_REQUIRED'] = true;

            return true;
        }

        if ($report->isLocked() && $report->getModifiedBy() !== $this->profile->getId()) {
            $this->abortResultCache();
            $this->arResult['IS_LOCKED'] = true;

            return true;
        }

        if (!$report->getIsLocked()) {
            $this->abortResultCache();

            $report->lock();
        }

        $this->arResult['REPORT'] = $report->getArray();
    }

    /**
     * Создает запись отчета в БД.
     *
     * @param int $elementType
     *
     * @return bool
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected function processNewReport(int $elementType)
    {
        if (!in_array($elementType, array_keys(ReportsTable::getTypes()))) {
            return false;
        }

        Report::setUrlTemplate(
            $this->arParams['SEF_FOLDER'] .
            ArrayHelper::getValue($this->getParent()->arParams, 'SEF_URL_TEMPLATES.detail', '')
        );

        $year = (int) date('Y');

        if (in_array($elementType, [ReportsTable::TYPE_ANNUAL, ReportsTable::TYPE_PRELIMINARY_ANNUAL])) {
            $year--;
        }

        // Проверяем наличие элемента для данного типа
        //
        $report = ReportsTable::getList([
            'filter' => [
                '=TYPE' => $elementType,
                '=YEAR' => $year,
                '=COMPANY_ID' => $this->profile->getCompanyId()
            ],
            'limit' => 1
        ])->fetchObject();

        if ($report) {
            return $report->getLink();
        }

        // Проверяем попадаем ли под даты периода
        //
        $typePeriod = ArrayHelper::getValue(ReportsTable::getTypePeriod($year), $elementType, []);
        // TODO: restore real date
        $curTime    = mktime(0, 0, 0, 4, 2, 2019);// time();

        if (!$typePeriod
            || $curTime <= $typePeriod['start']
            || $curTime >= $typePeriod['end']
        ) {
            return false;
        }

        // Создаем отчет
        //
        try {
            $report = new Report();
            $report->setYear($year)
                    ->setType($elementType)
                    ->setStatusId(StatusTable::NEW)
                    ->setCompanyId($this->profile->getCompanyId())
                    ->setName($this->profile->getCompanyName())
                    ->setModifiedBy($this->profile->getId());

            $res = $report->save();

            if ($res->isSuccess()) {
                return $res->getObject()->getLink();
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    protected function getReport(int $id)
    {
        try {
            $res = ReportsTable::getList([
                'select' => [
                    '*', 'STATUS'
                ],
                'filter' => [
                    '=ID' => $id,
                    '=COMPANY_ID' => $this->profile->getCompanyId()
                ],
                'limit' => 1
            ])->fetchObject();
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }


}
