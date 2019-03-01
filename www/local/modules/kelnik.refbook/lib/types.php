<?php

namespace Kelnik\Refbook;

use Bitrix\Main\SiteTable;

class Types
{
    const TYPE_PARTNER = 1;
    const TYPE_RESIDENT = 2;
    const TYPE_REVIEW = 3;
    const TYPE_TEAM = 4;
    const TYPE_DOCS = 5;
    const TYPE_PRESENTATION = 6;

    public static function getSites()
    {
        try {
            $tmp = SiteTable::getList([
                'select' => ['LID', 'NAME'],
                'order'  => [
                    'SORT' => 'ASC'
                ]
            ])->FetchAll();
        } catch (\Exception $e) {
            $tmp = [];
        }

        if (!$tmp) {
            return [];
        }

        $res = [];

        foreach ($tmp as $v) {
            $res[$v['LID']] = '[' . $v['LID'] . '] ' . $v['NAME'];
        }

        return $res;
    }
}
