<?php

namespace Kelnik\Infrastructure\Model;


use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Entity\Event;
use Kelnik\Helpers\ArrayHelper;

class DataManager extends \Kelnik\Helpers\Database\DataManager
{
    public static function clearComponentCache(Event $event)
    {
        if (!Context::getCurrent()->getRequest()->isAdminSection()) {
            return;
        }

        $id = ArrayHelper::getValue($event->getParameter('id'), 'ID', 0);
        $entity = $event->getEntity();

        if ($entity->getName() != 'Platform') {
            $row = $entity->getDataClass()::getRow([
                'select' => [
                    'PLATFORM_ID'
                ],
                'filter' => [
                    '=ID' => $id
                ]
            ]);

            $id = ArrayHelper::getValue($row, 'PLATFORM_ID', 0);
        }

        Application::getInstance()->getTaggedCache()->clearByTag('kelnik:infrastructureList');
        Application::getInstance()->getTaggedCache()->clearByTag(
            'kelnik:infrastructureRow_' . $id
        );

        parent::clearComponentCache($event);
    }
}
