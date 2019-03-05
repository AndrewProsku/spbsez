<?php

namespace Kelnik\Report\Model;


class Report extends EO_Reports
{
    public function getIsArchive()
    {
        return false;
    }
}
