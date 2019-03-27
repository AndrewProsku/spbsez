<?php

namespace Kelnik\Report\Model;


use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
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
 * @method Report setModifiedBy(int $id)
 * @method Report setDateModified(\Bitrix\Main\Type\DateTime $dtime)
 * @method Report setDateCreated(\Bitrix\Main\Type\DateTime $dtime)
 * @method Report setName(string $name)
 * @method Report setNameSez(string $name)
 * @method Report setIsLocked(bool $state)
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
 * @method \Bitrix\Main\Type\DateTime getDateModified()
 * @method \Bitrix\Main\Type\DateTime getDateCreated()
 * @method Fields getFields()
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
     * @return \Bitrix\Main\ORM\Data\AddResult
     * @throws \Bitrix\Main\ObjectException
     */
    public function lock()
    {
        return $this->setIsLocked(true)
                    ->setModifiedBy(ReportsTable::getUserId())
                    ->setDateModified(new DateTime())
                    ->save();
    }

    /**
     * Снять блокировку с отчета
     *
     * @return \Bitrix\Main\ORM\Data\AddResult
     * @throws \Bitrix\Main\ObjectException
     */
    public function unLock()
    {
        return $this->setIsLocked(false)
                    ->setModifiedBy(ReportsTable::getUserId())
                    ->setDateModified(new DateTime())
                    ->save();
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
    public function isLastModifier(int $userId = 0)
    {
        if (!$userId) {
            $userId = ReportsTable::getUserId();
        }

        return $this->getModifiedBy() === Profile::getInstance($userId)->getId();
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
     * Содание ссылки на отчет.
     * Если отчет не существует, то создаем временную ссылку с указаним типа.
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

    public function getForms(): array
    {
        $res = [];
        $fields = $this->getFields()->getArray();

        $forms = ReportFieldsTable::getForms();

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
                $formData = $this->processBlock($formData, $fields, $block);
            }

            $res[$formKey] = $formData;
        }

        return $res;
    }

    protected function processBlock(array $form, array $fieldValues, array $block)
    {
        $newBlock = [];

        if (isset($block['type'])) {
            $newBlock['type'] = $block['type'];
        }

        // fields
        //
        if (!empty($block['fields'])) {

            foreach ($block['fields'] as $field) {
                $isArray = is_array($field);

                $id = $isArray ? $field['id'] : $field;
                $val = self::getFieldValue($fieldValues, $id);
                $valField = 'value';

                if ($isArray && !empty($field['suffix'])) {
                    $id = $id . '-' . $field['suffix'];
                }

                if ($isArray && $field['type'] == 'boolean') {
                    $val = $val == ArrayHelper::getValue($field, 'trueValue', false);
                    $valField = 'checked';
                }

                $newBlock['fields'][] = [
                    'id' => $id,
                    $valField => $val
                ];
            }

            $form['blocks'][] = $newBlock;

            return $form;
        }

        // stages
        //
        if (!empty($block['stages'])) {

        }

        return $form;
    }

    protected static function getFieldValue(array $fields, $fieldName)
    {
        $key = array_search($fieldName, array_column($fields, 'FIELD_NAME', 'ID'));

        return $key
                ? ArrayHelper::getValue($fields, $key . '.VALUE', '')
                : '';
    }
}
