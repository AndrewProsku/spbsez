<?php

namespace Kelnik\Api\Process;


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Report\Model\Report;
use Kelnik\Report\Model\ReportFieldsTable;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\UserData\Profile\Profile;

/**
 * Class ApiProcessReport
 *
 * Отчеты
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessReport extends ApiProcessAbstract
{
    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @var string
     */
    protected $action = 'get';

    /**
     * @var Profile
     */
    protected $profile;

    /**
     * @var Report
     */
    protected $report;

    public function execute(array $request): bool
    {
        global $USER;

        $this->id = ArrayHelper::getValue($request, 'id', 0);
        $this->action = ArrayHelper::getValue($request, 'a', 'get');

        $methodName = 'process' . ucfirst($this->action);

        if (!method_exists($this, $methodName)) {
            $this->errors[] = Loc::getMessage('KELNIK_API_REQUEST_ERROR');

            return false;
        }

        $this->profile = Profile::getInstance((int)$USER->GetID());
        $this->report  = ReportsTable::getReport($this->profile->getCompanyId(), $this->id);

        $this->report->fill();

        if (!$this->report || !$this->report->hasAccess()) {
            $this->errors[] = Loc::getMessage('KELNIK_API_REPORT_NOT_FOUND');

            return false;
        }

        // при попытке изменения проверяем блокировку
        //
        if ($this->action !== 'get' && $this->report->isLocked() && !$this->report->isLastModifier()) {
            $this->errors[] = Loc::getMessage(
                'KELNIK_API_REPORT_LOCKED',
                [
                    '#TIME#' => FormatDate('idiff', time(), $this->report->getLockExpiredTime())
                ]
            );

            return false;
        }

        $res = $this->{$methodName}($request);

        if ($this->action !== 'get') {
            $this->report->setDateModified(new DateTime());
            $this->report->save();
        }

        return $res;
    }

    protected function processGet(array $request)
    {
        $this->data['NAME'] = $this->report->getName();
        $this->data['NAME_SEZ'] = $this->report->getNameSez();
        $this->data['forms'] = $this->report->getForms();

        return true;
    }

    protected function processUpdate(array $request)
    {
        $field = trim(ArrayHelper::getValue($request, 'field'));
        $val   = trim(ArrayHelper::getValue($request, 'val'));

        $fields = $this->report->getFields()->getAssocArray();

        $data = [
            'FIELD_NAME' => $field,
            'VALUE' => $val
        ];

        try {
            if (isset($fields[$field])) {
                ReportFieldsTable::update($fields[$field], $data);

                return true;
            }

            $data['REPORT_ID'] =  $this->report->getId();

            ReportFieldsTable::add($data);
        } catch (\Exception $e) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');

            return false;
        }

        return true;
    }
}
