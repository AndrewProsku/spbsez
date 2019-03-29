<?php

namespace Kelnik\Requests;


use Bitrix\Main\Context;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Messages\Model\NotifyTable;
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
            $rowId = (int)ArrayHelper::getValue($event->getParameters(), 'id.ID', 0);
            $rowData = $className::getRowById($rowId);
            $statusId = (int)ArrayHelper::getValue($rowData, 'STATUS_ID', 0);

            if (!$rowId || !$statusId) {
                return false;
            }

            static::$statusId[$entityName][$rowId] = $statusId;
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Отправка уведомлений при смен статуса стандартной заявки и заявки на пропуск
     * @param Event $event
     * @return bool
     */
    public static function sendNotify(Event $event)
    {
        $className = $event->getEntity()->getDataClass();
        $fields = ArrayHelper::getValue($event->getParameters(), 'fields');
        $entityName = strtoupper($event->getEntity()->getName());

        $rowId = (int) ArrayHelper::getValue($event->getParameters(), 'id.ID', 0);
        $statusId = (int) ArrayHelper::getValue($fields, 'STATUS_ID', 0);
        $oldStatusId = (int) ArrayHelper::getValue(static::$statusId, $entityName . '.' . $rowId, 0);

        if (!$rowId || !$statusId || $statusId === $oldStatusId) {
            return false;
        }

        try {
            $rowData = $className::getRowById($rowId);
            if (!$rowData) {
                return false;
            }

            $profile = Profile::getInstance((int) $rowData['USER_ID']);

            $code = ArrayHelper::getValue($rowData, 'CODE');
            $statusName = StatusTable::getNameById($statusId);

            $mailEvents = [
                'STANDARD' => 'STANDARD_REQUEST_STATUS',
                'PERMIT' => 'PERMIT_PASS_REQUEST_STATUS'
            ];

            if (!isset($mailEvents[$entityName])) {
                return false;
            }

            \Bitrix\Main\Mail\Event::sendImmediate([
                'EVENT_NAME' => $mailEvents[$entityName],
                'LID' => Context::getCurrent()->getRequest()->isAdminSection() ? 's1' : SITE_ID,
                'FIELDS' => [
                    'EMAIL_TO' => $profile->getField('EMAIL'),
                    'CODE' => $code,
                    'STATUS_NAME' => $statusName
                ]
            ]);

            NotifyTable::add([
                'USER_ID' => $profile->getId(),
                'IS_NEW' => NotifyTable::YES,
                'NAME' => Loc::getMessage('KELNIK_REQ_NOTIFY_HEADER_' . $entityName, ['#CODE#' => $code]),
                'TEXT' => Loc::getMessage('KELNIK_REQ_NOTIFY_BODY_' . $entityName, ['#CODE#' => $code, '#STATUS_NAME#' => $statusName])
            ]);
        } catch (\Exception $e) {
        }
        
        return true;
    }
}
