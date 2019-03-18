<?php

namespace Kelnik\Requests\Model;

use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class PermitTable extends DataManager
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
                    'title' => Loc::getMessage('KELNIK_REQ_TYPE'),
                    'default_value' => 0
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
                    'title' => Loc::getMessage('KELNIK_REQ_USER'),
                    'default_value' => self::getUserId()
                ]
            ),
            new DatetimeField(
                'DATE_CREATED',
                [
                    'default_value' => new DateTime(),
                    'title' => Loc::getMessage('KELNIK_REQ_DATE_CREATED')
                ]
            ),
            new DatetimeField(
                'DATE_MODIFIED',
                [
                    'default_value' => new DateTime(),
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
            new StringField(
                'TARGET',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_TARGET')
                ]
            ),
            new StringField(
                'EXECUTIVE_COMPANY',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_EXECUTIVE_COMPANY')
                ]
            ),
            new StringField(
                'EXECUTIVE_VISIT',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_EXECUTIVE_VISIT')
                ]
            ),
            new StringField(
                'PHONE',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_PHONE')
                ]
            ),

            (new Reference(
                'TYPE',
                TypeTable::class,
                Join::on('this.TYPE_ID', 'ref.ID')
            ))->configureJoinType('LEFT'),

            (new Reference(
                'STATUS',
                StatusTable::class,
                Join::on('this.STATUS_ID', 'ref.ID')
            ))->configureJoinType('LEFT'),

            (new OneToMany('PASS', PermitPassTable::class, 'PERMIT'))
        ];
    }

    public static function add(array $data)
    {
        $data['DATE_CREATED'] = $data['DATE_MODIFIED'] = new DateTime();
        $data['CODE'] = self::getNewCode((int) $data['TYPE_ID'], isset($data['USER_ID']) ? $data['USER_ID'] : false);

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

    public static function getNewCode($typeId, $userId = false)
    {
        global $USER;

        $userId = false === $userId
                    ? (int) $USER->GetID()
                    : (int) $userId;

        return implode(
            '-',
            [
                'p' . (int) $typeId,
                $userId,
                randString(5)
            ]
        );
    }

    protected static function getUserId()
    {
        global $USER;

        return (int) $USER->GetID();
    }
}
