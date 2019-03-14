<?php

namespace Kelnik\Requests\Model;

use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class PermittTable extends DataManager
{
    const REQUEST_TIME_LEFT = 60; // 1 min
    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_request_permit';
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
                'TYPE_ID',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_TYPE')
                ]
            ),
            new IntegerField(
                'STATUS_ID',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_STATUS'),
                    'default_value' => StatusTable::REQUEST_STATUS_NEW
                ]
            ),
            new IntegerField(
                'USER_ID',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_USER')
                ]
            ),
            new DatetimeField(
                'DATE_CREATED',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_DATE_CREATED')
                ]
            ),
            new DatetimeField(
                'DATE_MODIFIED',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_DATE_MODIFIED')
                ]
            ),
            new DatetimeField(
                'DATE_START',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_DATE_START')
                ]
            ),
            new DatetimeField(
                'DATE_FINISH',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_DATE_FINISH')
                ]
            ),
            new StringField(
                'CODE',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_CODE')
                ]
            ),
            new StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_NAME')
                ]
            ),

            new ReferenceField(
                'TYPE',
                TypeTable::class,
                [
                    '=this.TYPE_ID' => 'ref.ID'
                ]
            ),
            new ReferenceField(
                'STATUS',
                StatusTable::class,
                [
                    '=this.STATUS_ID' => 'ref.ID'
                ]
            )
        ];
    }

    public static function add(array $data)
    {
        $data['DATE_CREATED'] = $data['DATE_MODIFIED'] = new DateTime();
        $data['CODE'] = self::getNewCode((int) $data['TYPE_ID']);
        if (!isset($data['STATUS_ID'])) {
            $data['STATUS_ID'] = StatusTable::REQUEST_STATUS_NEW;
        }

        return parent::add($data);
    }

    public static function update($primary, array $data)
    {
        $data['DATE_MODIFIED'] = new DateTime();
        return parent::update($primary, $data);
    }

    protected static function getNewCode($typeId)
    {
        global $USER;

        return implode(
            '-',
            [
                'p',
                date('ymd-Hi'),
                (int) $USER->GetID(),
                (int) $typeId
            ]
        );
    }
}
