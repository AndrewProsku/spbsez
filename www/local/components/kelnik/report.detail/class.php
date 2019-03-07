<?php

namespace Kelnik\Report\Component;

use Bex\Bbc;
use Bitrix\Main\Application;
use Bitrix\Main\Context;
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
        $arParams['CREATE_ELEMENT_TYPE'] = (int) Context::getCurrent()->getRequest()->getQuery('t');

        try {
            Loader::includeModule('kelnik.report');

            if ($arParams['ELEMENT_ID']
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

        if ($this->arParams['ELEMENT_ID']) {
            return true;
        }

        $res = $this->processReport($this->arParams['CREATE_ELEMENT_TYPE']);

        if (false !== $res) {
            LocalRedirect($res);
        }

        $this->return404(true);
    }

    protected function executeMain()
    {
        self::registerCacheTag('kelnik:report_' . $this->profile->getCompanyId() . '_' . $this->arParams['ELEMENT_ID']);

        $element = [];

//        if (!$element && $this->arParams['SET_404'] === 'Y') {
//            $this->return404();
//        }
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
    protected function processReport(int $elementType)
    {
        Report::setUrlTemplate(
            $this->arParams['SEF_FOLDER'] .
            ArrayHelper::getValue($this->getParent()->arParams, 'SEF_URL_TEMPLATES.detail', '')
        );

        $isAnnual = false;
        $year = (int) date('Y');

        if (in_array($elementType, [ReportsTable::TYPE_ANNUAL, ReportsTable::TYPE_SEMI_ANNUAL])) {
            $isAnnual = true;
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

        // Если это квартальный отчет, то проверяем сдан ли отчет за прошлый год
        //
        if (!$isAnnual) {
            // TODO: check prev year reports
        }

        try {
            $report = new Report();
            $report->setYear($year)
                    ->setType($elementType)
                    ->setStatusId(StatusTable::NEW)
                    ->setCompanyId($this->profile->getCompanyId())
                    ->setModifiedBy($this->profile->getId());

            $res = $report->save();

            if ($res->isSuccess()) {
                return $res->getObject()->getLink();
            }
        } catch (\Exception $e) {
        }

        return false;
    }
}
