<?php

namespace Kelnik\Api\Process;


use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Report\Model\Report;
use Kelnik\Report\Model\ReportFieldsGroupTable;
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
     * Изменяемые поля самого отчета
     */
    protected const REPORT_FIELDS = ['resident-name', 'oez-name'];

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

        if (!$this->id || !method_exists($this, $methodName)) {
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

        // После каждого изменения полей отчета
        // обновляем время изменения самого отчета
        // и блокировку
        //
        if ($this->action !== 'get') {
            $this->report->setIsLocked(true);
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
        $groupId = 0;
        $field = trim(ArrayHelper::getValue($request, 'field'));
        $val   = trim(ArrayHelper::getValue($request, 'val'));

        // Меняем поля самого отчета
        //
        if (in_array($field, self::REPORT_FIELDS)) {
            $methodName = $field == 'resident-name'
                            ? 'setName'
                            : 'setNameSez';

            $this->report->{$methodName}($val);

            return true;
        }

        // Изменяем поля формы отчета
        //
        $fields = $this->report->getFields()->getAssocArray();

        if (false !== strpos($field, '[')) {
            preg_match('!(?P<field>[a-z0-9\-]+)\[(?P<parent>\d+)\]!si', $field, $matches);
            $groupId = (int) ArrayHelper::getValue($matches, 'parent', 0);
            $field = ArrayHelper::getValue($matches, 'field');
        }

        $data = [
            'NAME' => $field,
            'GROUP_ID' => $groupId,
            'VALUE' => $val
        ];

        $field = $field . '.' . $groupId;

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

    protected function processAddGroup(array $request)
    {
        $type = ArrayHelper::getValue($request, 'type');
        $formNum = ArrayHelper::getValue($request, 'form');

        $typeFormsAllowed = ArrayHelper::getValue(ReportFieldsGroupTable::getGroupFields(), $type, []);

        if (!$type || !$formNum || !in_array($formNum, $typeFormsAllowed)) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
            
            return false;
        }

        try {
            $res = ReportFieldsGroupTable::add([
                'REPORT_ID' => $this->id,
                'FORM_NUM' => $formNum,
                'TYPE' => $type
            ]);

            if ($res->isSuccess()) {
                $this->data['ID'] = $res->getId();

                return true;
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    protected function processDelGroup(array $request)
    {
        $typeId = ArrayHelper::getValue($request, 'typeId');

        if (!$typeId) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');

            return false;
        }

        try {
            $sqlHelper = Application::getConnection()->getSqlHelper();
            Application::getConnection()->query(
                "DELETE g.*, f.* " .
                "FROM `" . ReportFieldsGroupTable::getTableName() . "` g " .
                "LEFT JOIN `" . ReportFieldsTable::getTableName() . "` f  ON (f.`GROUP_ID` = g.`ID`)" .
                "WHERE g.`ID` = " . $sqlHelper->convertToDbInteger($typeId) .
                " AND g.`REPORT_ID` = " . $sqlHelper->convertToDbInteger($this->id)
            );
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return true;
    }
}
