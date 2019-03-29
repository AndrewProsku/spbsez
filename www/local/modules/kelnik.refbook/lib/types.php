<?php

namespace Kelnik\Refbook;

use Bitrix\Main\SiteTable;
use Kelnik\Refbook\Model\DocsTable;
use Kelnik\Refbook\Model\PartnerTable;
use Kelnik\Refbook\Model\PresTable;
use Kelnik\Refbook\Model\ResidentTable;
use Kelnik\Refbook\Model\ReviewTable;
use Kelnik\Refbook\Model\TeamTable;

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

    public static function getClassesByTypeList(): array
    {
        return [
            self::TYPE_PARTNER => PartnerTable::class,
            self::TYPE_RESIDENT => ResidentTable::class,
            self::TYPE_REVIEW => ReviewTable::class,
            self::TYPE_TEAM => TeamTable::class,
            self::TYPE_DOCS => DocsTable::class,
            self::TYPE_PRESENTATION => PresTable::class
        ];
    }
}
