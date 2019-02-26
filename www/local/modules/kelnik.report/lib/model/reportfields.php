<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class StatusTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_report';
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('KELNIK_REPORT_ID')
                ]
            )
        ];
    }
}
