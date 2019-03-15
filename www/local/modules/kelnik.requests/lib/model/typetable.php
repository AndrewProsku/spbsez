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
    public const SUB_TYPE_SERVICE = 'service';
    public const SUB_TYPE_STANDARD = 'standard';
    public const SUB_TYPE_PERMIT = 'permit';

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

    public static function getFormTypes()
    {
        return [
            self::SUB_TYPE_PERMIT => Loc::getMessage('KELNIK_REQ_SUB_TYPE_PERMIT'),
            self::SUB_TYPE_SERVICE => Loc::getMessage('KELNIK_REQ_SUB_TYPE_SERVICE'),
            self::SUB_TYPE_STANDARD => Loc::getMessage('KELNIK_REQ_SUB_TYPE_STANDARD')
        ];
    }
}
