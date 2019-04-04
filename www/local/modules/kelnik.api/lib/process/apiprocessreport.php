<?php

namespace Kelnik\Api\Process;


use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Report\Model\Report;
use Kelnik\Report\Model\ReportFieldsGroupTable;
use Kelnik\Report\Model\ReportFieldsTable;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Report\Model\StatusTable;
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

    protected static $notUpdateMethods = [
        'get', 'confirm'
    ];

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
        if (!in_array($this->action, self::$notUpdateMethods)) {
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

        if ($field == ReportFieldsTable::FIELD_CONSTRUCTION_FILE) {
            $val = $this->saveFile($field, $groupId, Context::getCurrent()->getRequest()->getFileList()->toArray());

            if (!$val) {
                $this->errors[] = Loc::getMessage('KELNIK_API_REPORT_FILE_UPLOAD_ERROR');

                return false;
            }
        }

        $data = [
            'NAME' => $field,
            'GROUP_ID' => $groupId,
            'VALUE' => $val
        ];

        if (isset($request['clearComment'])) {
            $data['COMMENT'] = null;
        }

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

                $this->addGroupFields($formNum, $type, $res->getId());

                return true;
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    protected function addGroupFields($formNum, $groupType, $groupId)
    {
        $blocks = ArrayHelper::getValue(ReportFieldsTable::getFormConfig(), $formNum . '.blocks', []);

        if (!$blocks) {
            return;
        }

        $values = [];

        $sqlHelper = Application::getConnection()->getSqlHelper();

        foreach ($blocks as $block) {
            if (empty($block['multiple']['name']) || $block['multiple']['name'] !== $groupType) {
                continue;
            }

            foreach ($block['multiple']['fields'] as $field) {
                $values[] = '(' .
                            $sqlHelper->convertToDbInteger($this->id). ', ' .
                            $sqlHelper->convertToDbInteger($groupId) . ', ' .
                            $sqlHelper->convertToDbString($field['id']) .
                            ')';
            }
        }

        if (!$values) {
            return;
        }

        try {
            Application::getConnection()->query(
                'INSERT INTO `' . ReportFieldsTable::getTableName() . '` (`REPORT_ID`, `GROUP_ID`, `NAME`) '.
                'VALUES ' . implode(', ', $values)
            );
        } catch (\Exception $e) {
        }
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

    protected function processDelFile(array $request)
    {
        $groupId = ArrayHelper::getValue($request, 'parent', 0);

        if (!$groupId) {
            $this->errors[] = Loc::getMessage('KELNIK_API_REPORT_FILE_DELETE_ERROR');

            return false;
        }

        try {
            $row = ReportFieldsTable::getRow([
                'select' => ['*'],
                'filter' => [
                    '=REPORT_ID' => $this->id,
                    '=GROUP_ID'  => $groupId,
                    '=NAME'      => ReportFieldsTable::FIELD_CONSTRUCTION_FILE
                ]
            ]);
        } catch (\Exception $e) {
            $row = [];
        }

        if (empty($row['ID'])) {
            $this->errors[] = Loc::getMessage('KELNIK_API_REPORT_FILE_DELETE_ERROR');

            return false;
        }

        try {
            \CFile::Delete((int) $row['VALUE']);
            ReportFieldsTable::delete($row['ID']);
        } catch (\Exception $e) {
        }

        return true;
    }

    // Отчет заполнен, проверяем так ли это и переводим в статус - проверка
    // пользователя перекидываем обратно на список отчетов
    protected function processConfirm(array $request)
    {
        if (!$this->report->isFilled()) {
            $this->errors[] = Loc::getMessage('KELNIK_API_REPORT_FILL_ERROR');

            return false;
        }

        $this->report->setStatusId(StatusTable::CHECKING);
        $this->report->setDateModified(new DateTime());
        $this->report->setIsLocked(false);
        $this->report->save();

        $this->data['backUrl'] = \SezLang::getDirBySite(SITE_ID) . \Kelnik\Report\Model\ReportsTable::BASE_URL;

        return true;
    }

    /**
     * Сохраняем файл из формы №3
     *
     * @param string $fieldName
     * @param int $parentId
     * @param array $files
     * @return bool|int|string
     */
    protected function saveFile(string $fieldName, int $parentId, array $files)
    {
        if (!$fieldName || !$parentId || !isset($files[$fieldName]['name'][$parentId])) {
            return 0;
        }

        $fileData = [
            'name' => $files[$fieldName]['name'][$parentId],
            'tmp_name' => $files[$fieldName]['tmp_name'][$parentId],
            'type' => $files[$fieldName]['type'][$parentId],
            'size' => $files[$fieldName]['size'[$parentId]],
            'error' => $files[$fieldName]['error'][$parentId],
            'MODULE_ID' => 'kelnik.report'
        ];

        if (!is_uploaded_file($fileData['tmp_name'])) {
            return 0;
        }

        return \CFile::SaveFile(
            $fileData,
            $fileData['MODULE_ID'],
            true
        );
    }
}
