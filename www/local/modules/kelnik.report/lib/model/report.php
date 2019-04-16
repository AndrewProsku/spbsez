<?php

namespace Kelnik\Report\Model;


use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\UserData\Profile\Profile;

/**
 * Class Report
 * @package Kelnik\Report\Model
 *
 * @method Report setId(int $id)
 * @method Report setYear(int $year)
 * @method Report setStatusId(int $id)
 * @method Report setType(int $id)
 * @method Report setCompanyId(int $id)
 * @method Report setUserId(int $id)
 * @method Report setModifiedBy(int $id)
 * @method Report setDateModified(\Bitrix\Main\Type\DateTime $dtime)
 * @method Report setDateCreated(\Bitrix\Main\Type\DateTime $dtime)
 * @method Report setName(string $name)
 * @method Report setNameSez(string $name)
 * @method Report setIsLocked(bool $state)
 * @method Report setIsPreFilled(bool $state)
 * @method Report setNameComment(string $comment)
 * @method Report setNameSezComment(string $comment)
 *
 * @method \Bitrix\Main\ORM\Data\DeleteResult delete()
 * @method \Bitrix\Main\ORM\Data\AddResult save()
 *
 * @method int getId()
 * @method int getYear()
 * @method int getType()
 * @method Status getStatus()
 * @method int getStatusId()
 * @method string getName()
 * @method string getNameSez()
 * @method bool getIsLocked()
 * @method int getCompanyId()
 * @method int getUserId()
 * @method bool getIsPreFilled()
 * @method \Bitrix\Main\Type\DateTime getDateModified()
 * @method \Bitrix\Main\Type\DateTime getDateCreated()
 * @method Fields getFields()
 * @method FieldsGroup getGroups()
 */
class Report extends EO_Reports
{
    protected static $elementUrlTemplate = '/#ELEMENT_ID#/';

    public static function setUrlTemplate(string $urlTmpl)
    {
        self::$elementUrlTemplate = $urlTmpl;
    }

    public function getTypeName()
    {
        return ReportsTable::getTypeName($this->getType());
    }

    /**
     * Заблокировать возможность изменения записи всем, кроме автора.
     * Время блокировки в @see ReportsTable::LOCK_TIME_LEFT
     *
     * @return \Bitrix\Main\ORM\Data\AddResult|bool
     */
    public function lock()
    {
        try {
            return $this->setIsLocked(true)
                ->setModifiedBy(ReportsTable::getUserId())
                ->setUserId(ReportsTable::getUserId())
                ->setDateModified(new DateTime())
                ->save();
        } catch (\Exception $e){
            return false;
        }
    }

    /**
     * Снять блокировку с отчета
     *
     * @return \Bitrix\Main\ORM\Data\AddResult|bool
     */
    public function unLock()
    {
        try {
            return $this->setIsLocked(false)
                        ->setModifiedBy(ReportsTable::getUserId())
                        ->setUserId(ReportsTable::getUserId())
                        ->setDateModified(new DateTime())
                        ->save();
        } catch (\Exception $e){
            return false;
        }
    }

    /**
     * @return bool
     */
    protected function lockIsExpired()
    {
        return $this->getLockExpiredTime() < time();
    }

    /**
     * @return int
     */
    public function getLockExpiredTime()
    {
        return $this->getDateModified()->getTimestamp() + ReportsTable::LOCK_TIME_LEFT;
    }

    /**
     * Проверка блокировки отчета
     *
     * @return bool
     */
    public function isLocked()
    {
        return $this->getIsLocked() && !$this->lockIsExpired();
    }

    /**
     * Отчет сдан
     *
     * @return bool
     */
    public function isComplete()
    {
        return $this->getStatusId() == StatusTable::DONE;
    }

    /**
     * Отчет на проверке администратором
     *
     * @return bool
     */
    public function isUnderReview()
    {
        return $this->getStatusId() === StatusTable::CHECKING;
    }

    /**
     * Проверка последнего автора правок
     *
     * @param int $userId
     * @return bool
     */
    public function isOwner(int $userId = 0)
    {
        if (!$userId) {
            $userId = ReportsTable::getUserId();
        }

        return $this->getUserId() === Profile::getInstance($userId)->getId();
    }

    /**
     * Отчет полностью заполнен и готов к отправке
     */
    public function isFilled()
    {
        $forms = $this->getForms();

        foreach ($forms as $form) {
            foreach ($form['blocks'] as $block) {
                if (isset($block['fields'])) {
                    if (!self::checkFilledFields($block['fields'])) {
                        return false;
                    }
                    continue;
                }

                // groups, innovations, stages
                foreach (['groups', 'innovations', 'stages'] as $type) {
                    if (!isset($block[$type])) {
                        continue;
                    }

                    foreach ($block[$type] as $typeElement) {
                        if ($type == 'stages') {
                            $stageType = ArrayHelper::getValue(current($typeElement['fields']), 'value');
                            if (ArrayHelper::getValue(ReportFieldsTable::getStages(), $stageType . '.extra', false) === false) {
                                continue;
                            }
                        }

                        if (!self::checkFilledFields($typeElement['fields'])) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Помечаем отчет как предзаполненный
     *
     * @return \Bitrix\Main\ORM\Data\AddResult|bool
     */
    public function markAsPreFilled()
    {
        try {
            return $this->setIsPreFilled(true)
                ->setModifiedBy(ReportsTable::getUserId())
                ->setDateModified(new DateTime())
                ->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateFieldComments(array $list)
    {
        if (!$list) {
            return false;
        }

        try {
            $sqlHelper = Application::getConnection()->getSqlHelper();

            $tmp = $list;
            $list = [];

            foreach ($tmp as $k => $v) {
                $list[(int) $k] = $sqlHelper->convertToDbString(trim($v));
            }
            unset($tmp);
            ksort($list);


            Application::getConnection()->query($sql =
                'UPDATE `' . ReportFieldsTable::getTableName() . '` ' .
                'SET `COMMENT` = ELT(FIELD(`ID`, ' . implode(', ', array_keys($list)) . '), ' . implode(', ', array_values($list)) . ') ' .
                'WHERE `ID` IN (' . implode(', ', array_keys($list)) . ') AND `REPORT_ID` = ' . $this->getId()
            );
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    protected static function checkFilledFields(array $fields)
    {
        if (!$fields) {
            return false;
        }

        foreach ($fields as $field) {
            if (isset($field['checked'])) {
                continue;
            }

            if (!strlen($field['value'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Проверяем возможность редактирования отчета
     *
     * @param int $userId
     * @return bool
     */
    public function canEdit(int $userId = 0)
    {
        if ($this->isComplete() || $this->isUnderReview()) {
            return false;
        }

        return $this->hasAccess($userId);
    }

    /**
     * Проверка доступа к отчету
     *
     * @param int $userId
     * @return bool
     */
    public function hasAccess(int $userId = 0)
    {
        if (!$userId) {
            $userId = ReportsTable::getUserId();
        }

        $profile = Profile::getInstance($userId);

        if (!$profile->canReport()
            || $profile->getCompanyId() !== $this->getCompanyId()
        ) {
            return false;
        }

        return true;
    }

    /**
     * Создание ссылки на отчет.
     * Если отчет не существует, то создаем временную ссылку с указанием типа.
     *
     * @return string
     */
    public function getLink()
    {
        return str_replace(
            '#ELEMENT_ID#',
            $this->getId()
                ? $this->getId()
                : ReportsTable::NEW_ROW_PREFIX . $this->getType(),
            self::$elementUrlTemplate
        );
    }

    public function getArray()
    {
        $res = $this->collectValues();

        $res['LINK'] = $this->getLink();
        $res['TYPE_NAME'] = $this->getTypeName();

        if ($this->getStatus()) {
            $res['STATUS_NAME'] = $this->getStatus()->getName();
            $res['STATUS_BUTTON_NAME'] = $this->getStatus()->getButtonName();
            $res['STATUS_CSS_CLASS'] = $this->getStatus()->getCssClass();
            unset($res['STATUS'], $res['FIELDS']);
        }

        foreach (['DATE_CREATED', 'DATE_MODIFIED'] as $field) {
            if (!$res[$field] instanceof DateTime) {
                continue;
            }
            $res[$field] = $res[$field]->getTimestamp();
        }

        $res['TYPE_NAME'] = $this->getTypeName();

        return $res;
    }

    public function copyDataFromPrevReport()
    {
        $lastReportId = $this->getLastReportId();

        if (!$lastReportId) {
            $this->markAsPreFilled();

            return false;
        }

        $src = $this->getReportData($lastReportId);

        if ($src['fields'] || $src['groups']) {
            $dst = $this->getReportData($this->getId());
            $sqlHelper = Application::getConnection()->getSqlHelper();

            $values = [];

            foreach ($src['fields'] as $fieldName => $group) {
                foreach ($group as $groupId => $field) {

                    $groupId = (int) $groupId;

                    if ($groupId) {
                        $groupId = $this->getDestinationGroupId($src['groups'], $dst['groups'], $groupId);
                    }

                    $rowId = ArrayHelper::getValue($dst, 'fields.' . $fieldName . '.' . $groupId . '.ID');
                    $rowId = $rowId ? $sqlHelper->convertToDbInteger($rowId) : 'NULL';

                    $val = ArrayHelper::getValue(
                        $dst,
                        'fields.' . $fieldName . '.' . $groupId . '.VALUE',
                        $field['VALUE']
                    );

                    if (in_array(
                            $field['NAME'],
                            [
                                ReportFieldsTable::FIELD_CONSTRUCTION_FILE,
                                ReportFieldsTable::FIELD_CONSTRUCTION_DATE
                            ]
                        )
                    ) {
                        $val = 'NULL';
                    }

                    $values[] = '(' .
                                $rowId . ', ' .
                                $sqlHelper->convertToDbInteger($this->getId()) . ', ' .
                                $sqlHelper->convertToDbInteger($groupId) . ', ' .
                                $sqlHelper->convertToDbInteger($field['FORM_NUM']) . ', ' .
                                $sqlHelper->convertToDbString(
                                    (int) $rowId ? ReportFieldsTable::NO : ReportFieldsTable::YES
                                ) . ', ' .
                                $sqlHelper->convertToDbString($field['NAME']) . ', ' .
                                $sqlHelper->convertToDbString($val) .
                                ')';
                }
            }

            if ($values) {
                try {
                    Application::getConnection()->query(
                        'REPLACE INTO `' . ReportFieldsTable::getTableName() . '` (`ID`, `REPORT_ID`, `GROUP_ID`, `FORM_NUM`, `IS_PRE_FILLED`, `NAME`, `VALUE`)' .
                        'VALUES ' . implode(', ', $values)
                    );
                } catch (\Exception $e) {
                }
            }
        }

        $this->markAsPreFilled();

        return true;
    }

    protected function getDestinationGroupId(array $src, array &$dst, int $groupId)
    {
        $group = ArrayHelper::getValue($src, $groupId);

        $filter = function($el) use ($group) {
            return $el['TYPE'] == $group['TYPE'] && $el['FORM_NUM'] = $group['FORM_NUM'];
        };

        $srcArr = array_values(array_filter($src, $filter));
        $dstArr = array_values(array_filter($dst, $filter));

        $groupIdIndex = array_search($groupId, array_column($srcArr, 'ID'));

        if (isset($dstArr[$groupIdIndex])) {
            return (int) ArrayHelper::getValue($dstArr, $groupIdIndex . '.ID');
        }

        try {
            $res = ReportFieldsGroupTable::add([
                'REPORT_ID' => $this->getId(),
                'FORM_NUM' => $group['FORM_NUM'],
                'TYPE' => $group['TYPE']
            ]);
        } catch (\Exception $e) {
            return 0;
        }

        $dst[$res->getId()] = [
            'ID' => $res->getId(),
            'TYPE' => $group['TYPE'],
            'FORM_NUM' => $group['FORM_NUM']
        ];

        return $res->getId();
    }

    protected function getReportData($reportId)
    {
        if (!$reportId) {
            return [];
        }

        $res = [
            'fields' => [],
            'groups' => []
        ];

        try {
            $fields = ReportFieldsTable::getList([
                'select' => ['*', 'GROUP_INFO_' => 'GROUP'],
                'filter' => [
                    '=REPORT_ID' => $reportId
                ],
                'order' => [
                    'GROUP_ID' => 'ASC',
                    'ID' => 'ASC'
                ]
            ])->fetchAll();
        } catch (\Exception $e) {
            return $res;
        }

        if (!$fields) {
            return $res;
        }

        foreach ($fields as $field) {
            if ($field['GROUP_ID']) {
                $res['groups'][$field['GROUP_ID']] = [
                    'ID' => $field['GROUP_ID'],
                    'TYPE' => $field['GROUP_INFO_TYPE'],
                    'FORM_NUM' => $field['GROUP_INFO_FORM_NUM']
                ];
            }

            $res['fields'][$field['NAME']][$field['GROUP_ID']] = [
                'ID' => $field['ID'],
                'NAME' => $field['NAME'],
                'FORM_NUM' => $field['FORM_NUM'],
                'VALUE' => $field['VALUE']
            ];
        }

        return $res;
    }

    protected function getLastReportId()
    {
        try {
            return (int) ArrayHelper::getValue(
                ReportsTable::getRow([
                    'select' => ['ID'],
                    'filter' => [
                        '=COMPANY_ID' => $this->getCompanyId(),
                        '=STATUS_ID' => StatusTable::DONE,
                        '=IS_PRE_FILLED' => ReportsTable::YES
                    ],
                    'order' => [
                        'YEAR' => 'DESC'
                    ]
                ]),
                'ID'
            );
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Получение массива данных отчета (для фронта)
     *
     * @return array
     */
    public function getForms(): array
    {
        $res = [];
        $fields = self::prepareFiles($this->getFields()->getArray());
        $groups = $this->getGroups()->getArray();

        $forms = ReportFieldsTable::getFormConfig();

        foreach ($forms as $formKey => $formConfig) {
            $formData = [
                'blocks' => []
            ];

            if (isset($formConfig['type'])) {
                $formData['type'] = $formConfig['type'];
            }

            if (empty($formConfig['blocks'])) {
                continue;
            }

            foreach ($formConfig['blocks'] as $block) {
                $formData['blocks'] = array_merge(
                    $formData['blocks'],
                    $this->processBlock($formKey, $fields, $groups, $block)
                );
            }

            $res[$formKey] = $formData;
        }

        return $res;
    }

    /**
     * Сборка данных для блока формы
     *
     * @param int $formNum
     * @param array $values
     * @param array $groups
     * @param array $block
     * @return array
     */
    protected function processBlock(int $formNum, array $values, array $groups, array $block)
    {
        $res = [];
        $newBlock = [];

        if (isset($block['type'])) {
            $newBlock['type'] = $block['type'];
        }

        // Простые поля (fields)
        //
        if (!empty($block['fields'])
            && (!isset($block['type']) || $block['type'] != 'results')
        ) {

            foreach ($block['fields'] as $field) {
                $isArray = is_array($field);

                $id = $isArray ? $field['id'] : $field;
                $val = self::getFieldValue($values, $id);
                $error = self::getFieldValue($values, $id, 0, 'COMMENT');
                $valField = 'value';

                if ($isArray && !empty($field['suffix'])) {
                    $id = $id . '-' . $field['suffix'];
                }

                if ($isArray && $field['type'] == 'boolean') {
                    $val = $val == ArrayHelper::getValue($field, 'trueValue', false);
                    $valField = 'checked';
                    if (!$val) {
                        $error = false;
                    }
                }

                $newBlock['fields'][] = [
                    'id' => $id,
                    $valField => $val,
                    'error' => $error,
                    'isPreFilled' => (bool) self::getFieldValue($values, $id, 0, 'IS_PRE_FILLED')
                ];
            }

            $res[] = $newBlock;

            return $res;
        }

        // Группы полей (multiple)
        //
        if (!empty($block['multiple'])) {

            $ids = ArrayHelper::getValue($groups, $block['multiple']['name'] . '.' . $formNum, []);
            $rows = [];

            foreach ($ids as $id) {
                $fields = [];
                foreach ($block['multiple']['fields'] as $field) {
                    $fields[] = [
                        'id'    => $field['id'] . '[' . $id . ']',
                        'value' => self::getFieldValue($values, $field['id'], $id),
                        'error' => self::getFieldValue($values, $field['id'], 0, 'COMMENT')
                    ];
                }

                $rows[$id] = [
                    $block['multiple']['id'] => $id,
                    'fields' => $fields
                ];
            }

            $newBlock = [
                'type' => $block['type'],
                $block['multiple']['name'] => array_values($rows)
            ];

            $res[] = $newBlock;

            return $res;
        }

        // Results
        //
        $ids = ArrayHelper::getValue($groups, ReportFieldsGroupTable::TYPE_RESULTS . '.' . $formNum, []);
        foreach ($ids as $id) {
            $newBlock = [
                'type' => 'results',
                'ID' => $id,
                'fields' => []
            ];

            foreach ($block['fields'] as $field) {
                $newBlock['fields'][] = [
                    'id' => $field['id'] . '[' . $id . ']',
                    'value' => self::getFieldValue($values, $field['id'], $id),
                    'error' => self::getFieldValue($values, $field['id'], 0, 'COMMENT')
                ];
            }

            $res[] = $newBlock;
        }

        return $res;
    }

    protected static function getFieldValue(array $fields, $fieldName, $groupId = 0, $fieldReturn = 'VALUE')
    {
        if ($groupId) {
            foreach ($fields as $field) {
                if ($field['NAME'] != $fieldName || $field['GROUP_ID'] != $groupId) {
                    continue;
                }

                return (string) $field[$fieldReturn];
            }

            return '';
        }

        $key = array_search($fieldName, array_column($fields, 'NAME', 'ID'));

        return $key
                ? ArrayHelper::getValue($fields, $key . '.' . $fieldReturn, '')
                : '';
    }

    protected static function prepareFiles(array $fields)
    {
        $rows = array_filter($fields, function ($el) {
            return $el['NAME'] == ReportFieldsTable::FIELD_CONSTRUCTION_FILE;
        });

        if (!$rows) {
            return $fields;
        }

        $rows = BitrixHelper::prepareFileFields($rows, ['VALUE' => 'full']);

        foreach ($rows as $v) {
            if (!isset($fields[$v['ID']])) {
                continue;
            }

            $fields[$v['ID']]['VALUE_ORIG'] = $fields[$v['ID']]['VALUE'];
            $fields[$v['ID']]['VALUE'] = ArrayHelper::getValue($v, 'VALUE.ORIGINAL_NAME', '');
        }

        return $fields;
    }
}
