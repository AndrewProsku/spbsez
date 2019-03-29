<?php

namespace Kelnik\Api\Process;


use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Report\Model\Report;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\UserData\Profile\Profile;

/**
 * Class ApiProcessReport
 *
 * Профиль
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

        if (!$this->report || !$this->report->hasAccess()) {
            $this->errors[] = Loc::getMessage('KELNIK_API_REPORT_NOT_FOUND');

            return false;
        }

        return $this->{$methodName}($request);
    }

    protected function processGet(array $request)
    {
        $this->data['NAME'] = $this->report->getName();
        $this->data['NAME_SEZ'] = $this->report->getNameSez();

        $this->report->fill();
        $this->data['forms'] = $this->report->getFields()->getArray();

        return true;
    }
}
