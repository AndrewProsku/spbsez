<?php

namespace Kelnik\Report\Model;


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
 *
 * @method \Bitrix\Main\ORM\Data\DeleteResult delete()
 * @method \Bitrix\Main\ORM\Data\AddResult save()
 *
 * @method int getId()
 * @method int getYear()
 * @method int getType()
 * @method Status getStatus()
 * @method int getStatusId()
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

    public function getLink()
    {
        $res = str_replace('#ELEMENT_ID#', $this->getId(), self::$elementUrlTemplate);

        if (!$this->getId()) {
            $res .= '?t=' . $this->getType();
        }

        return $res;
    }
}
