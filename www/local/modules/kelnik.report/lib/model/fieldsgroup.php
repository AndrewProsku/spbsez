<?php

namespace Kelnik\Report\Model;


/**
 * Class FieldsGroup
 * @package Kelnik\Report\Model
 *
 * @method \Bitrix\Main\ORM\Data\AddResult|\Bitrix\Main\ORM\Data\UpdateResult save()
 *
 * @method array getAll()
 */
class FieldsGroup extends EO_ReportFieldsGroup_Collection
{
    /**
     * Список групп
     *
     * @return array
     */
    public function getArray()
    {
        $rows = $this->getAll();
        $res  = [];

        if (!$rows) {
            return $res;
        }

        foreach ($rows as $row) {
            $row = $row->collectValues();
            $res[$row['TYPE']][$row['FORM_NUM']][$row['ID']] = $row['ID'];
        }

        return $res;
    }
}
