<?php

namespace Kelnik\Requests;

use Bitrix\Main\Entity\Event;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Messages\Model\NotifyTable;
use Kelnik\Requests\Model\StatusTable;
use Kelnik\Userdata\Profile\Profile;

Loc::loadMessages(__FILE__);

class Events
{
    protected static $statusId = [];

    public static function setStatus(Event $event)
    {
        $entityName = strtoupper($event->getEntity()->getName());
        $rowId = (int) ArrayHelper::getValue($event->getParameters(), 'fields.ID', 0);
        $statusId = (int) ArrayHelper::getValue($event->getParameters(), 'fields.STATUS_ID', 0);

        if (!$rowId || !$statusId) {
            return;
        }

        static::$statusId[$entityName][$rowId] = $statusId;
    }

    public static function sendNotify(Event $event)
    {
        $className = $event->getEntity()->getDataClass();
        $fields = ArrayHelper::getValue($event->getParameters(), 'fields');
        $entityName = strtoupper($event->getEntity()->getName());

        $rowId = (int) ArrayHelper::getValue($fields, 'ID', 0);
        $statusId = (int) ArrayHelper::getValue($fields, 'STATUS_ID', 0);
        $oldStatusId = (int) ArrayHelper::getValue(static::$statusId, $entityName . '.' . $rowId, 0);

        if (!$rowId || !$statusId || $statusId === $oldStatusId) {
            return false;
        }

        try {
            $profile = Profile::getInstance((int) $fields['USER_ID']);
            $rowData = $className::getRowById($rowId);

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
                'LID' => SITE_ID,
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