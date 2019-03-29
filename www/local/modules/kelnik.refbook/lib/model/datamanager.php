<?php

namespace Kelnik\Refbook\Model;


use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Refbook\Types;

class DataManager extends \Kelnik\Helpers\Database\DataManager
{
    public static function clearComponentCache(\Bitrix\Main\Entity\Event $event)
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
