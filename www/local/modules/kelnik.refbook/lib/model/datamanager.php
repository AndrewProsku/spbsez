<?php

namespace Kelnik\Refbook\Model;


use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\ORM\Event;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Refbook\Types;

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
        if (!Context::getCurrent()->getRequest()->isAdminSection()) {
            return;
        }

        try {
            Application::getInstance()->getTaggedCache()->clearByTag(
                'kelnik:refBookList_' . ArrayHelper::getValue(array_flip(Types::getClassesByTypeList()), get_called_class(), 0)
            );
        } catch (\Exception $e) {
        }
    }
}
