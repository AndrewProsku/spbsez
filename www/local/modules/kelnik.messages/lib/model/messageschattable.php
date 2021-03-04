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
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class MessagesChatTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_messages_chat';
    }

    public static function getObjectClass()
    {
        return MessagesChat::class;
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('KELNIK_MESSAGES_CHAT_ID'),
                ]
            ),
            new IntegerField(
                'USER_ID',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_CHAT_USER_ID'),
                ]
            ),
            new IntegerField(
                'REPORT_ID',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_CHAT_REPORT_ID'),
                ]
            ),
            new IntegerField(
                'PARENT_ID',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_CHAT_PARENT_ID'),
                ]
            ),
            new IntegerField(
                'FIELD_ID',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_CHAT_FIELD_ID'),
                ]
            ),
            new DatetimeField(
                'DATE_CREATED',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_CHAT_DATE_CREATED'),
                ]
            ),
            new DatetimeField(
                'DATE_MODIFIED',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_CHAT_DATE_MODIFIED'),
                ]
            ),
            new IntegerField(
                'IS_NEW',
                [
                    'default_value' => 1,
                    'title' => Loc::getMessage('KELNIK_MESSAGES_CHAT_IS_NEW'),
                ]
            ),
            new IntegerField(
                'IS_ADMIN',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_CHAT_IS_ADMIN'),
                ]
            ),         
            new TextField(
                'TEXT',
                [
                    //'validation' => [__CLASS__, 'validateText'],
                    'title' => Loc::getMessage('KELNIK_MESSAGES_CHAT_TEXT'),
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

    public static function add(array $data)
    {
        $data['DATE_CREATED'] = $data['DATE_MODIFIED'] = new DateTime();

        return parent::add($data);
    }

    public static function update($primary, array $data)
    {
        $data['DATE_MODIFIED'] = new DateTime();

        return parent::update($primary, $data);
    }

    public static function clearComponentCache(Event $event)
    {
        try {
            $rowData = ArrayHelper::getValue(
                $event->getParameters(),
                'fields',
                self::getRowById((int) ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0))
            );
            $userId = (int)ArrayHelper::getValue($rowData, 'USER_ID', 0);

            Application::getInstance()->getTaggedCache()->clearByTag('kelnik:messagesList_' . $userId);
            Application::getInstance()->getTaggedCache()->clearByTag('bitrix:menuPersonal_' . $userId);
        } catch (\Exception $e) {
        }

        parent::clearComponentCache($event);
    }

    public static function getChatMessages($filter = [])
    {
        try {
            $res = self::getList([
                'select' => [
                    '*'
                ],
                'filter' => $filter
            ])->fetchObject();
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }

    public static function getChatMessageById(int $id)
    {
        if (!$id) {
            return false;
        }

        try {
            $res = self::getList([
                'select' => [
                    '*'
                ],
                'filter' => [
                    '=ID' => $id
                ],
                'limit' => 1
            ])->fetchObject();
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }

    public static function getChatMessagesByReport(int $reportId)
    {     
        if (!$reportId) {
            return false;
        }

        try {
            $res = self::getList([
                'select' => [
                    '*'
                ],
                'filter' => [
                    '=REPORT_ID' => $reportId
                ]
            ])->fetchAll();
        } catch (\Exception $e) {
            return false;
        }

        $res = ArrayHelper::index($res, function ($element) {
            return $element['ID'];
        });

        return $res;
    }
}
