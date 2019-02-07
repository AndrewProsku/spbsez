<?php

namespace Kelnik\Requests\Model;


use Bitrix\Main\Entity\Validator\Length;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class NotifyTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_request_notify';
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('KELNIK_REQ_NOTIFY_ID'),
                ]
            ),
            new IntegerField(
                'USER_ID',
                [
                    'title' => Loc::getMessage('NKELNIK_REQ_NOTIFY__USER_ID'),
                ]
            ),
            new DatetimeField(
                'DATE_CREATED',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_NOTIFY_DATE_CREATED'),
                ]
            ),
            new DatetimeField(
                'DATE_MODIFIED',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_NOTIFY_DATE_MODIFIED'),
                ]
            ),
            new StringField(
                'IS_NEW',
                [
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES,
                    'title' => Loc::getMessage('KELNIK_REQ_NOTIFY_IS_NEW'),
                ]
            ),
            new TextField(
                'TEXT',
                [
                    'validation' => [__CLASS__, 'validateText'],
                    'title' => Loc::getMessage('KELNIK_REQ_NOTIFY_TEXT'),
                ]
            )
        ];
    }

    public static function validateText()
    {
        return [
            new Length(null, 255),
        ];
    }
}
