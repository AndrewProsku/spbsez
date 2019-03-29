<?php

namespace Kelnik\Requests\Model;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

/**
 * Модель заявок на обратный звонок.
 */
class StatusTable extends DataManager
{
    const REQUEST_STATUS_NEW = 1;

    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_request_statuses';
    }

    /**
     * {@inheritdoc}
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                ]
            ),
            new IntegerField(
                'SORT',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_SORT'),
                    'default_value' => self::SORT_DEFAULT
                ]
            ),
            new StringField(
                'ACTIVE',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_ACTIVE'),
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES
                ]
            ),
            new StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_NAME')
                ]
            )
        ];
    }

    public static function getNameById($id)
    {
        return $id
                ? ArrayHelper::getValue(self::getRowById($id), 'NAME')
                : 0;
    }
}
