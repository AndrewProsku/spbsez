<?php

namespace Kelnik\Info\Model;


use Bitrix\Main\ORM\Event;

class DataManager extends \Kelnik\Helpers\Database\DataManager
{

    public static function onAfterAdd(Event $event)
    {
        self::clearComponentCache($event);
        parent::onAfterAdd($event);
    }

    public static function onAfterUpdate(Event $event)
    {
        self::clearComponentCache($event);
        parent::onAfterUpdate($event);
    }

    public static function onBeforeDelete(Event $event)
    {
        self::clearComponentCache($event);
        parent::onBeforeDelete($event);
    }

    public static function clearComponentCache(Event $event)
    {
    }
}
