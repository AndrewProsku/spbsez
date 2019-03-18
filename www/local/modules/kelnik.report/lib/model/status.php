<?php

namespace Kelnik\Report\Model;


class Status extends EO_Status
{
    public function getButtonName()
    {
        return StatusTable::getButtonNameById($this->getId());
    }

    public function getCssClass()
    {
        return StatusTable::getCssClassById($this->getId());
    }
}
