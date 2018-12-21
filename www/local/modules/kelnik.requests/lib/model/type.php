<?php

namespace Kelnik\Requests\Model;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

/**
 * Модель заявок на обратный звонок.
 */
class TypeTable extends DataManager
{
    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_request_types';
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
                    'default_value' => self::YES,
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
}
