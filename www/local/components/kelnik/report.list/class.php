<?php

namespace Kelnik\Report\Component;

use Bex\Bbc;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Objectify\Collection;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Report\Model\Report;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Report\Model\Status;
use Kelnik\Report\Model\StatusTable;
use Kelnik\UserData\Profile\Profile;

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
        $this->addCacheAdditionalId(date('Y-m-d'));

        $this->profile = Profile::getInstance((int)$USER->GetID());

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
        $this->arResult['REPORTS']  = [];
        $this->arResult['YEAR']     = date('Y');

        self::registerCacheTag('kelnik:reportList_' . $this->profile->getCompanyId());

        $this->arResult['REPORTS'] = $this->getReports();
        //$this->arResult['DISABLED'] = !count($this->arResult['REPORTS']);
    }

    protected function getReports()
    {
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
            $reports = new \Kelnik\Report\Model\EO_Reports_Collection();
        }

        return $this->prepareReports(
            $this->checkList($reports)
        );
    }

    /**
     * Подготовка списка отчетов по годам
     *
     * @param array $reports
     * @return array
     */
    protected function prepareReports(array $reports)
    {
        $res = [];

        foreach ($reports as $v) {
            $year = $v['YEAR'];

            if (!isset($res[$year])) {
                $res[$year] = [
                    'NAME' => $year,
                    'IS_COMPLETE' => true,
                    'ELEMENTS' => []
                ];
            }

            if ($v['STATUS_ID'] !== StatusTable::DONE) {
                $res[$year]['IS_COMPLETE'] = false;
            }

            // У битрикса плохо с кешированием объектов,
            // переводим в массив
            //
            $res[$year]['ELEMENTS'][$v['TYPE']] = $v;
        }

        $res = array_map(function ($year) {
            ksort($year['ELEMENTS']);

            return $year;
        }, $res);

        ksort($res);

        return array_reverse($res);
    }

    /**
     * Проверка списка отчетов на отсутствие требуемых.
     * Если отчет отсутствует, то в список добавляется "виртуальный" отчет,
     * с ссылкой на создание реального отчета
     *
     * @param Collection $reports
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    protected function checkList(Collection $reports)
    {
        $types = array_keys(ReportsTable::getTypes());
        $curYear = (int)date('Y');
        $curTime = ReportsTable::getCurrentTime();
        $defStatus = StatusTable::getByPrimary(StatusTable::NEW)->fetchObject();
        $unActiveStatus = new Status();

        $reportsByYear = [];

        $reports = $reports->getAll();

        if (count($reports)) {
            foreach ($reports as $k => $report) {
                $reportsByYear[$report->getYear()][$report->getType()] = $report->getType();
                $reports[$k] = $report->getArray();
            }
        }

        if (!isset($reportsByYear[$curYear])) {
            $reportsByYear[$curYear] = $curYear;
        }

        foreach ($reportsByYear as $year => $yearTypes) {
            $typePeriod = ReportsTable::getTypePeriod($year);

            foreach ($types as $type) {
                if (isset($yearTypes[$type])) {
                    continue;
                }
                if ($curTime > $typePeriod[$type]['end']
                    || $curTime < $typePeriod[$type]['start']
                ) {
                    $reports[] = (new Report())
                                    ->setId(0)
                                    ->setYear($year)
                                    ->setType($type)
                                    ->setStatus($unActiveStatus)
                                    ->setStatusId(-1)
                                    ->getArray();

                    continue;
                }

                $reports[] = (new Report())
                                ->setId(0)
                                ->setYear($year)
                                ->setType($type)
                                ->setStatus($defStatus)
                                ->getArray();
            }
        }

        return $reports;
    }
}