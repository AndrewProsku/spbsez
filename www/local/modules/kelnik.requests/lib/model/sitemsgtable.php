<?php

namespace Kelnik\Requests\Model;

use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;
use Kelnik\Requests\Model\AdminInterface\SiteMsgEditHelper;

Loc::loadMessages(__FILE__);

/**
 * Модель заявок на обратный звонок.
 */
class SiteMsgTable extends DataManager
{
    const REQUEST_TIME_LEFT = 60; // 1 min
    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_request_site';
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
            new StringField('USER_HASH'),
            new StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_FIO')
                ]
            ),
            new StringField(
                'EMAIL',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_EMAIL')
                ]
            ),
            new StringField(
                'PHONE',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_PHONE')
                ]
            ),
            new TextField(
                'BODY',
                [
                    'title' => Loc::getMessage('KELNIK_REQ_BODY')
                ]
            )
        ];
    }

    public static function add(array $data)
    {
        $data['DATE_CREATED'] = $data['DATE_MODIFIED'] = new DateTime();

        return parent::add($data);
    }

    public static function onAfterAdd(Event $event)
    {
        try {
            \Bitrix\Main\Mail\Event::sendImmediate([
                'EVENT_NAME' => 'MESSAGE_FORM',
                'LID' => SITE_ID,
                'FIELDS' => [
                    'LINK' => getSiteBaseUrl() . SiteMsgEditHelper::getUrl([
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

    public static function userCanAddRow($userHash)
    {
        if (!$userHash) {
            return false;
        }

        return !(bool) self::getRow([
            'select' => ['ID'],
            'filter' => [
                '=USER_HASH' => $userHash,
                '>=DATE_CREATED' => DateTime::createFromTimestamp(time() - self::REQUEST_TIME_LEFT)
            ]
        ]);
    }
}
