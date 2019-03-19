<?php

namespace Kelnik\Requests\Model;

use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;
use Kelnik\Requests\Model\AdminInterface\StandardEditHelper;

Loc::loadMessages(__FILE__);

/**
 * Модель заявок на обратный звонок.
 */
class StandardTable extends DataManager
{
    const REQUEST_TIME_LEFT = 60; // 1min
    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_request';
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
            new TextField(
                'BODY',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_BODY')
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
        $data['CODE'] = self::getNewCode((int) $data['TYPE_ID'], isset($data['USER_ID']) ? $data['USER_ID'] : false);

        if (!isset($data['STATUS_ID'])) {
            $data['STATUS_ID'] = StatusTable::REQUEST_STATUS_NEW;
        }

        return parent::add($data);
    }

    public static function OnAfterAdd(Event $event)
    {
        try {
            \Bitrix\Main\Mail\Event::sendImmediate([
                'EVENT_NAME' => 'STANDARD_REQUEST',
                'LID' => SITE_ID,
                'FIELDS' => [
                    'LINK' => getSiteBaseUrl() . StandardEditHelper::getUrl([
                        'ID' => ArrayHelper::getValue($event->getParameters(), 'id', 0)
                    ])
                ]
            ]);
        } catch (\Exception $e) {
        }

        parent::onAfterAdd($event);
    }

    public static function update($primary, array $data)
    {
        $data['DATE_MODIFIED'] = new DateTime();

        return parent::update($primary, $data);
    }

    public static function getNewCode($typeId, $userId)
    {
        global $USER;

        $userId = false === $userId
            ? (int) $USER->GetID()
            : (int) $userId;

        return implode(
            '-',
            [
                's' . (int) $typeId,
                $userId,
                randString(5)
            ]
        );
    }
}
