<?php

namespace Kelnik\Report\Component;

use Bex\Bbc;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Objectify\Collection;
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

Loc::loadMessages(__FILE__);

class ReportList extends Bbc\Basis
{
    use Bbc\Traits\Elements;

    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.report', 'iblock', 'kelnik.userdata'];
    protected $checkParams = [];

    /**
     * @var Profile
     */
    protected $profile;

    protected function executeProlog()
    {
        global $USER;

        $this->addCacheAdditionalId($USER->GetID());
        $this->addCacheAdditionalId(date('Y'));

        $this->profile = Profile::getInstance($USER->GetID());

        if (!$this->profile->canReport()) {
            LocalRedirect(LANG_DIR . 'cabinet/');
        }

        Report::setUrlTemplate(
            $this->arParams['SEF_FOLDER'] .
            ArrayHelper::getValue($this->getParent()->arParams, 'SEF_URL_TEMPLATES.detail', '')
        );
    }

    protected function executeMain()
    {
        $this->arResult['DISABLED'] = true;
        $this->arResult['REPORTS']  = [];
        $this->arResult['YEAR']     = date('Y');

        self::registerCacheTag('kelnik:report_list_' . $this->profile->getCompanyId());

        try {
            $reports = ReportsTable::getList([
                'select' => [
                    '*', 'STATUS'
                ],
                'filter' => [
                    '=COMPANY_ID' => $this->profile->getCompanyId()
                ],
                'order' => [
                    'YEAR' => 'DESC',
                    'TYPE' => 'ASC'
                ]
            ])->fetchCollection();
        } catch (\Exception $e) {
            return;
        }

        if (!$reports->count()) {
            return;
        }

        $this->arResult['DISABLED'] = false;

        $this->arResult['REPORTS'] = $this->prepareReports(
            $this->checkList($reports)
        );
    }

    /**
     * Подготовка списка отчетов по годам
     *
     * @param Collection $reports
     * @return array
     */
    protected function prepareReports(Collection $reports)
    {
        $res = [];

        foreach ($reports as $v) {
            $year = $v->getYear();

            if (!isset($res[$year])) {
                $res[$year] = [
                    'NAME' => $year,
                    'IS_COMPLETE' => true,
                    'ELEMENTS' => []
                ];
            }

            if (!$v->isComplete()) {
                $res[$year]['IS_COMPLETE'] = false;
            }

            $res[$year]['ELEMENTS'][$v->getType()] = $v;
        }

        ksort($res);
        $res = array_reverse($res);

        return $res;
    }

    /**
     * Проверка списка отчетов на отсутствие требуемых.
     *
     * @param Collection $reports
     * @return array|Collection
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    protected function checkList(Collection $reports)
    {
        if (!$reports->count()) {
            return $reports;
        }

        $types     = array_keys(ReportsTable::getTypes());
        $curYear   = (int) date('Y');
        $curTime   = mktime(0, 0, 0, 4, 2, 2019);// time();
        $defStatus = StatusTable::getByPrimary(StatusTable::NEW)->fetchObject();

        $reportsByYear = [];

        foreach ($reports as $report) {
            $reportsByYear[$report->getYear()][$report->getType()] = $report->getType();
        }

        if (!isset($reportsByYear[$curYear])) {
            $reportsByYear[$curYear] = $curYear;
        }


        foreach ($reportsByYear as $year => $yearTypes) {
            $typePeriod = ReportsTable::getTypePeriod($year);

            foreach ($types as $type) {
                if (isset($yearTypes[$type])
                    || $curTime > $typePeriod[$type]['end']
                    || $curTime < $typePeriod[$type]['start']
                ) {
                    continue;
                }

                $reports->add(
                    (new Report())
                    ->setId(0)
                    ->setYear($year)
                    ->setType($type)
                    ->setStatus($defStatus)
                );
            }
        }

        return $reports;
    }
}
