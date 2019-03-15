<?php

namespace Kelnik\Requests\Model;

use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class PermitPassTable extends DataManager
{
    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_request_permit_pass';
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
                'PERMIT_ID',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_PERMIT_ID'),
                    'default_value' => 0
                ]
            ),
            new StringField(
                'FIO',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_FIO')
                ]
            ),
            new StringField(
                'ORG_NAME',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_ORG_NAME')
                ]
            ),
            new StringField(
                'CAR_VENDOR',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_CAR_VENDOR')
                ]
            ),
            new StringField(
                'CAR_NUMBER',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_CAR_NUMBER')
                ]
            ),
            new StringField(
                'PERSON',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_PERSON')
                ]
            ),

            (new Reference(
                'PERMIT',
                PermitTable::class,
                Join::on('this.PERMIT_ID', 'ref.ID')
            ))->configureJoinType('INNER')
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
                'p' . (int) $typeId,
                (int) $USER->GetID(),
                randString(5)
            ]
        );
    }
}
