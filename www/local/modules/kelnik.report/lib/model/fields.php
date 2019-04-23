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
            $row = $row->collectValues();
            $res[$row['ID']] = $row;
        }

        return $res;
    }

    public function getAssocArray()
    {
        $res = $this->getArray();

        if (!$res) {
            return [];
        }

        $tmp = [];
        foreach ($res as $v) {
            $tmp[implode('.', [$v['NAME'], $v['GROUP_ID'], $v['FORM_NUM']])] = $v['ID'];
        }

        return $tmp;
    }
}
