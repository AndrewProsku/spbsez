<?php

namespace Kelnik\Messages\Model;


use Bitrix\Main\Application;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\Validator\Length;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class NotifyTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_messages_notify';
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('KELNIK_MESSAGES_NOTIFY_ID'),
                ]
            ),
            new IntegerField(
                'USER_ID',
                [
                    'title' => Loc::getMessage('NKELNIK_MESSAGES_NOTIFY_USER_ID'),
                ]
            ),
            new DatetimeField(
                'DATE_CREATED',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_NOTIFY_DATE_CREATED'),
                ]
            ),
            new DatetimeField(
                'DATE_MODIFIED',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_NOTIFY_DATE_MODIFIED'),
                ]
            ),
            new StringField(
                'IS_NEW',
                [
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES,
                    'title' => Loc::getMessage('KELNIK_MESSAGES_NOTIFY_IS_NEW'),
                ]
            ),
            new StringField(
                'NAME',
                [
                    'validation' => [__CLASS__, 'validateText'],
                    'title' => Loc::getMessage('KELNIK_MESSAGES_NOTIFY_NAME'),
                ]
            ),
            new TextField(
                'TEXT',
                [
                    'validation' => [__CLASS__, 'validateText'],
                    'title' => Loc::getMessage('KELNIK_MESSAGES_NOTIFY_TEXT'),
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

    public static function onAfterAdd(Event $event)
    {
        try {
            $userId = (int)ArrayHelper::getValue($event->getParameters(), 'fields.USER_ID', 0);

            Application::getInstance()->getTaggedCache()->clearByTag('kelnik:messagesList_' . $userId);
            Application::getInstance()->getTaggedCache()->clearByTag('bitrix:menuPersonal_' . $userId);
        } catch (\Exception $e) {
        }

        parent::onAfterAdd($event);
    }
}
