<?php

namespace Kelnik\Requests\Model;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ServiceTypeTable extends DataManager
{
    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_request_service_types';
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
            ),
            new StringField(
                'NAME_EN',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_NAME_EN')
                ]
            ),
            new StringField(
                'NAME_EN',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_NAME_EN')
                ]
            ),
            new StringField(
                'EMAIL',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_EMAIL')
                ]
            )
        ];
    }
}
