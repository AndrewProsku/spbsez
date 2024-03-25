<?php

namespace Kelnik\Messages;


use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Event;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Report\Model\ReportsTable;
use Bitrix\Main\Mail\Event as Sender;

Loc::loadMessages(__FILE__);

class Events
{
    /**
     * Отправка уведомлений о сообщении в чате
     *
     * @param Event $event
     * @return bool
     */
    public static function sendChatEmail(Event $event)
    {
        $fields = ArrayHelper::getValue($event->getParameters(), 'fields');
        $siteId = Context::getCurrent()->getRequest()->isAdminSection() ? 's1' : SITE_ID;
        $report = ReportsTable::getByPrimary($fields['REPORT_ID'])->fetchObject();
        $residentAdmin = \CUser::GetByID($report->getUserId())->fetch();
        $residentAdminEmail = $residentAdmin['EMAIL'];

        try {
            $senderEvent = 'REPORT_CHAT_MESSAGE_ADMIN';
            if ($fields['IS_ADMIN'] == 1) {
                $senderEvent = 'REPORT_CHAT_MESSAGE_RESIDENT';
            }
            Sender::send([
                'EVENT_NAME' => $senderEvent,
                'LID' => $siteId,
                'C_FIELDS' => array(
                    'TEXT' => $fields['TEXT'],
                    'REPORT_ID' => $fields['REPORT_ID'],
                    'RESIDENT_EMAIL' => $residentAdminEmail
                ),
            ]);

        } catch (\Exception $e) {
        }

        return true;
    }
}
