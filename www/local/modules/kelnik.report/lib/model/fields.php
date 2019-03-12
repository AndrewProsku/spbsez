<?php

namespace Kelnik\Report\Model;


/**
 * Class Fields
 * @package Kelnik\Report\Model
 *
 * @method \Bitrix\Main\ORM\Data\AddResult|\Bitrix\Main\ORM\Data\UpdateResult save()
 *
 * @method array getAll()
 */
class Fields extends EO_ReportFields_Collection
{
    public function getArray()
    {
        $rows = $this->getAll();
        $res  = [];

        if (!$rows) {
            return $res;
        }

        foreach ($rows as $row) {
            $res[] = $row->collectValues();
        }

        return $res;
    }
}