<?php

namespace Kelnik\Report\Model;


use Bitrix\Main\Type\DateTime;
use Kelnik\Userdata\Profile\Profile;

/**
 * Class Report
 * @package Kelnik\Report\Model
 *
 * @method object setId(int $id)
 * @method object setYear(int $year)
 * @method object setStatusId(int $id)
 * @method object setType(int $id)
 * @method object setCompanyId(int $id)
 * @method object setModifiedBy(int $id)
 * @method object setDateModified(\Bitrix\Main\Type\DateTime $dtime)
 * @method object setDateCreated(\Bitrix\Main\Type\DateTime $dtime)
 * @method object setName(string $name)
 * @method object setNameSez(string $name)
 * @method object setIsLocked(bool $state)
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
 */
class Report extends EO_Reports
{
    protected static $elementUrlTemplate = '/#ELEMENT_ID#/';

    public static function setUrlTemplate(string $urlTmpl)
    {
        self::$elementUrlTemplate = $urlTmpl;
    }

    public function isComplete()
    {
        return $this->getStatusId() == StatusTable::DONE;
    }

    public function getTypeName()
    {
        return ReportsTable::getTypeName($this->getType());
    }

    /**
     * Заблокировать возможность изменения записи всем, кроме автора
     *
     * @throws \Bitrix\Main\ObjectException
     */
    public function lock()
    {
        global $USER;

        $this->setIsLocked(true)
            ->setModifiedBy($USER->GetID())
            ->setDateModified(new DateTime())
            ->save();
    }

    /**
     * Снять блокировку с отчета
     *
     * @throws \Bitrix\Main\ObjectException
     */
    public function unLock()
    {
        global $USER;

        $this->setIsLocked(false)
            ->setModifiedBy($USER->GetID())
            ->setDateModified(new DateTime())
            ->save();
    }

    /**
     * Проверка блокировки отчета
     *
     * @return bool
     */
    public function isLocked()
    {
        return $this->getIsLocked() && !$this->lockExpired();
    }

    /**
     * @return bool
     */
    protected function lockExpired()
    {
        return $this->getDateModified()->getTimestamp() < (time() - ReportsTable::LOCK_TIME_LEFT);
    }

    /**
     * Проверяем возможность редактировая отчета
     *
     * @param int $userId
     * @return bool
     */
    public function canEdit(int $userId)
    {
        global $USER;

        if ($this->isComplete()) {
            return false;
        }

        $profile = Profile::getInstance($userId ? $userId : $USER->GetID());

        if (!$profile->canReport()
            || $profile->getCompanyId() !== $this->getCompanyId()
        ) {
            return false;
        }

        return !$this->isLocked() || $this->lockExpired();
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
            unset($res['STATUS']);
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
}
