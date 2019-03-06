<?php

namespace Kelnik\Report\Model;


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

    public function getQuarterName()
    {
        return ReportsTable::prepareType($this->getType());
    }

    public function getLink()
    {
        $res = str_replace('#ELEMENT_ID#', $this->getId(), self::$elementUrlTemplate);

        if ($this->getId() < 0) {
            $res .= '?t=' . $this->getType();
        }

        return $res;
    }
}
