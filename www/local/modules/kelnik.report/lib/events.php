<?php

namespace Kelnik\Report;


use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Event;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Messages\Model\NotifyTable;
use Kelnik\Report\Model\AdminInterface\ReportsListHelper;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Report\Model\StatusTable;
use Kelnik\UserData\Profile\Profile;

Loc::loadMessages(__FILE__);

class Events
{
    protected static $statusId = 0;

    public static function setStatus(Event $event)
    {
        try {
            $rowId = (int)ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0);

            if (!$rowId) {
                return false;
            }

            $rowData  = ReportsTable::getRowByIdCached($rowId);
            $statusId = (int)ArrayHelper::getValue($rowData, 'STATUS_ID', 0);

            if (!$statusId) {
                return false;
            }

            static::$statusId = $statusId;
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Отправка уведомлений при смене статуса отчета
     *
     * @param Event $event
     * @return bool
     */
    public static function sendNotify(Event $event)
    {
        $fields = ArrayHelper::getValue($event->getParameters(), 'fields');

        $rowId = (int) ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0);
        $statusId = (int) ArrayHelper::getValue($fields, 'STATUS_ID', 0);
        $oldStatusId = (int) static::$statusId;

        if (!$rowId || $statusId === $oldStatusId) {
            return false;
        }

        try {
            $rowData = ReportsTable::getRowByIdCached($rowId);

            if (!$rowData) {
                return false;
            }

            $statusName = StatusTable::getNameById($statusId);
            $typeName = ArrayHelper::getValue(ReportsTable::getTypes(), $rowData['TYPE']) . ' ' . $rowData['YEAR'];
            $profile = Profile::getInstance((int)$rowData['USER_ID']);
            $siteId = Context::getCurrent()->getRequest()->isAdminSection() ? 's1' : SITE_ID;
            $reportLink = '/' . ReportsTable::BASE_URL . $rowId . '/';

            $locParams = [
                '#STATUS_NAME#' => $statusName,
                '#LINK#' => $reportLink,
                '#TYPE_NAME#' => $typeName
            ];

            if ($statusId === StatusTable::CHECKING) {
                // Сообщение администратору
                //
                \Bitrix\Main\Mail\Event::sendImmediate([
                    'EVENT_NAME' => 'REPORT_STATUS_ADMIN',
                    'LID' => $siteId,
                    'FIELDS' => [
                        'REPORT_ADMIN_LINK' => ReportsListHelper::getUrl(['ID' => $rowId]),
                        'REPORT_TYPE_NAME' => $typeName
                    ]
                ]);

                // Сообщение резиденту по E-mail
                //
                \Bitrix\Main\Mail\Event::sendImmediate([
                    'EVENT_NAME' => 'REPORT_STATUS',
                    'LID' => $siteId,
                    'FIELDS' => [
                        'EMAIL_TO' => $profile->getField('EMAIL'),
                        'REPORT_STATUS_NAME' => $statusName,
                        'REPORT_TYPE_NAME' => $typeName,
                        'REPORT_RESIDENT_LINK' => getSiteBaseUrl() . $reportLink
                    ]
                ]);

                // Сообщение резиденту на сайте
                //
                NotifyTable::add([
                    'USER_ID' => $profile->getId(),
                    'IS_NEW' => NotifyTable::YES,
                    'NAME' => Loc::getMessage('KELNIK_REPORT_NOTIFY_HEADER_CHECKING', $locParams),
                    'TEXT' => Loc::getMessage('KELNIK_REPORT_NOTIFY_BODY_CHECKING', $locParams)
                ]);

                return true;
            }

            if (!in_array($statusId, [StatusTable::DONE, StatusTable::DECLINED])) {
                return false;
            }

            // Сообщение резиденту по E-mail
            //
            \Bitrix\Main\Mail\Event::sendImmediate([
                'EVENT_NAME' => 'REPORT_STATUS',
                'LID' => $siteId,
                'FIELDS' => [
                    'EMAIL_TO' => $profile->getField('EMAIL'),
                    'REPORT_STATUS_NAME' => $statusName,
                    'REPORT_TYPE_NAME' => $typeName,
                    'REPORT_RESIDENT_LINK' => getSiteBaseUrl() . $reportLink
                ]
            ]);

            // Сообщение резиденту на сайте
            //
            NotifyTable::add([
                'USER_ID' => $profile->getId(),
                'IS_NEW' => NotifyTable::YES,
                'NAME' => Loc::getMessage('KELNIK_REPORT_NOTIFY_HEADER', $locParams),
                'TEXT' => Loc::getMessage('KELNIK_REPORT_NOTIFY_BODY', $locParams)
            ]);
        } catch (\Exception $e) {
        }
        
        return true;
    }
}
