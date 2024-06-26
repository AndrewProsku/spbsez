<?php

namespace Kelnik\Report\Component;

use Bex\Bbc;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Report\Model\Report;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Report\Model\StatusTable;
use Kelnik\UserData\Profile\Profile;
use Bitrix\Main\Context;
use Kelnik\Report\Model\Export;

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

        $arParams['PREV_REQUIRED'] = false;

        return parent::onPrepareComponentParams($arParams);
    }

    protected function executeProlog()
    {
        global $USER;
        global $APPLICATION;

        if (!$this->arParams['ELEMENT_ID'] && !$this->arParams['CREATE_ELEMENT_TYPE']) {
            $this->show404();
        }

        $this->profile = Profile::getInstance((int)$USER->GetID());

        Report::setUrlTemplate(
            $this->arParams['SEF_FOLDER'] .
            ArrayHelper::getValue($this->getParent()->arParams, 'SEF_URL_TEMPLATES.detail', '')
        );

        $request = Context::getCurrent()->getRequest();
        if ($request->isPost() && $request->getPost('export_report')) {
            $APPLICATION->RestartBuffer();         
            list($year, $type) = explode('_', $request->getPost('period'));
            $resident = [$request->getPost('resident')];
            $export = new Export(
                $year,
                $type,
                $resident,
                [],
                'tables_single.xlsx',
                true
            );
            $export->getFile();
            exit;
        }

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
        } elseif ($res === true) {
            $this->arParams['PREV_REQUIRED'] = true;

            return true;
        }

        die('xxx');

        $this->show404();
    }

    protected function executeMain()
    {
        $this->setResultCacheKeys(['PREV_REQUIRED', 'IS_LOCKED', 'TIME_LEFT', 'ORDER']);
        $this->abortCache();
//        self::registerCacheTag('kelnik:report_' . $this->profile->getCompanyId() . '_' . $this->arParams['ELEMENT_ID']);

        if (!$this->arParams['ELEMENT_ID'] && $this->arParams['PREV_REQUIRED']) {
            $this->arResult['PREV_REQUIRED'] = true;

            return true;
        }

        $report = ReportsTable::getReport($this->profile->getCompanyId(), $this->arParams['ELEMENT_ID']);

        if ((!$report || !$report->hasAccess()) && $this->arParams['SET_404'] === 'Y') {
            $this->show404();
        }

        // Не заполнен предыдущий отчетный период
        //
        if (ReportsTable::prevRequired($this->profile->getCompanyId(), $report->getType(), $report->getYear())) {
            $this->arResult['PREV_REQUIRED'] = true;

            return true;
        }

        if ($report->isLocked() && !$report->isOwner()) {
            $this->arResult['IS_LOCKED'] = true;
            $this->arResult['TIME_LEFT'] = FormatDate('idiff', time(), $report->getLockExpiredTime());

            return true;
        }

        $this->arResult['EDITABLE'] = $report->canEdit();

        if ($this->arResult['EDITABLE']) {
            if (!$report->getIsPreFilled()) {
                $report->copyDataFromPrevReport();
            }
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

        $year = (int) date('Y', ReportsTable::getCurrentTime());

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
        $curTime    = ReportsTable::getCurrentTime();

        if (!$typePeriod
            || $curTime <= $typePeriod['start']
            || $curTime >= $typePeriod['end']
        ) {
            return false;
        }

        if (ReportsTable::prevRequired($this->profile->getCompanyId(), $elementType, $year)) {
            return true;
        }

        // Создаем отчет
        //
        try {
            $report = new Report();
            $report->setYear($year)
                    ->setType($elementType)
                    ->setStatusId(StatusTable::NEW)
                    ->setCompanyId($this->profile->getCompanyId())
                    ->setUserId($this->profile->getId())
                    ->setName($this->profile->getCompanyName())
                    ->setNameSez($this->profile->getSezDefaultName())
                    ->setModifiedBy($this->profile->getId())
                    ->setDateModified(new DateTime());

            $res = $report->save();

            if ($res->isSuccess()) {
                return $res->getObject()->getLink();
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    protected function show404()
    {
        Tools::process404(
            'Not Found',
            true,
            true,
            true
        );
    }
}
