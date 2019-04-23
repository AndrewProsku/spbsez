<?php

namespace Kelnik\Requests;


use Bitrix\Main\Context;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Messages\Model\NotifyTable;
use Kelnik\Requests\Model\StandardTable;
use Kelnik\Requests\Model\StatusTable;
use Kelnik\UserData\Profile\Profile;

Loc::loadMessages(__FILE__);

class Events
{
    protected static $statusId = [];

    public static function setStatus(Event $event)
    {
        try {
            $className = $event->getEntity()->getDataClass();
            $entityName = strtoupper($event->getEntity()->getName());
            $rowId = (int)ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0);

            if (!$rowId) {
                return false;
            }

            $rowData = ArrayHelper::getValue($event->getParameters(), 'fields', []);
            if (!$rowData) {
                $rowData = $className::getRowByIdCached($rowId);
            }
            $statusId = (int)ArrayHelper::getValue($rowData, 'STATUS_ID', 0);

            if (!$statusId) {
                return false;
            }

            static::$statusId[$entityName][$rowId] = $statusId;
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Отправка уведомлений при смене статуса стандартной заявки и заявки на пропуск
     * @param Event $event
     * @return bool
     */
    public static function sendNotify(Event $event)
    {
        $className = $event->getEntity()->getDataClass();
        $fields = ArrayHelper::getValue($event->getParameters(), 'fields');
        $entityName = strtoupper($event->getEntity()->getName());
        $eventType = false !== stripos($event->getEventType(), 'OnAfterUpdate') ? 'UPDATE' : 'ADD';

        $rowId = (int) ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0);
        $statusId = (int) ArrayHelper::getValue($fields, 'STATUS_ID', 0);
        $oldStatusId = (int) ArrayHelper::getValue(static::$statusId, $entityName . '.' . $rowId, 0);

        if (!$rowId || $statusId === $oldStatusId) {
            return false;
        }

        try {
            $rowData = $className::getRowById($rowId);

            if (!$rowData) {
                return false;
            }

            $siteId = Context::getCurrent()->getRequest()->isAdminSection() ? 's1' : SITE_ID;

            if ($eventType === 'ADD') {
                // Сообщение администратору
                //
                \Bitrix\Main\Mail\Event::sendImmediate([
                    'EVENT_NAME' => 'STANDARD_REQUEST',
                    'LID' => $siteId,
                    'FIELDS' => [
                        'LINK' => StandardTable::getUrl($rowId)
                    ]
                ]);
            }

            $profile = Profile::getInstance((int) $rowData['USER_ID']);

            $code = ArrayHelper::getValue($rowData, 'CODE');
            $statusName = StatusTable::getNameById($statusId);

            $mailEvents = [
                'STANDARD' => [
                    'UPDATE' => 'STANDARD_REQUEST_STATUS',
                    'ADD' => 'STANDARD_REQUEST_USER'
                ],
                'PERMIT' => [
                    'UPDATE' => 'PERMIT_PASS_REQUEST_STATUS',
                    'ADD' => 'PERMIT_PASS_REQUEST_USER'
                ]
            ];

            if (!isset($mailEvents[$entityName][$eventType])) {
                return false;
            }

            // Сообщение резиденту по E-mail
            //
            \Bitrix\Main\Mail\Event::sendImmediate([
                'EVENT_NAME' => $mailEvents[$entityName][$eventType],
                'LID' => $siteId,
                'FIELDS' => [
                    'EMAIL_TO' => $profile->getField('EMAIL'),
                    'CODE' => $code,
                    'STATUS_NAME' => $statusName
                ]
            ]);

            // Сообщение резиденту на сайте
            //
            NotifyTable::add([
                'USER_ID' => $profile->getId(),
                'IS_NEW' => NotifyTable::YES,
                'NAME' => Loc::getMessage('KELNIK_REQ_NOTIFY_HEADER_' . $entityName . '_' . $eventType, ['#CODE#' => $code]),
                'TEXT' => Loc::getMessage('KELNIK_REQ_NOTIFY_BODY_' . $entityName . '_' . $eventType, ['#CODE#' => $code, '#STATUS_NAME#' => $statusName])
            ]);
        } catch (\Exception $e) {
        }
        
        return true;
    }
}
