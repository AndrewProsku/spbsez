<?php

namespace Kelnik\Info\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ProcTable extends DataManager
{
    public const ITEMS_PER_PAGE = 10;

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_info_proc';
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return [
            new Main\Entity\IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('KELNIK_PROC_ID')
                ]
            ),
            new Main\Entity\DateField(
                'DATE_SHOW',
                [
                    'default_value' => new Main\Type\Date(),
                    'title' => Loc::getMessage('KELNIK_PROC_DATE')
                ]
            ),
            new Main\Entity\IntegerField(
                'SORT',
                [
                    'default_value' => 500,
                    'title' => Loc::getMessage('KELNIK_PROC_SORT'),
                ]
            ),
            new Main\Entity\StringField(
                'ACTIVE',
                [
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES,
                    'title' => Loc::getMessage('KELNIK_PROC_ACTIVE'),
                ]
            ),
            new Main\Entity\StringField(
                'LINK',
                [
                    'title' => Loc::getMessage('KELNIK_PROC_LINK')
                ]
            ),
            new Main\Entity\TextField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_PROC_NAME'),
                ]
            ),
        ];
    }

    public static function getYearsList()
    {
        return self::getAssoc([
            'select' => [
                new Main\Entity\ExpressionField(
                    'NAME',
                    'DISTINCT YEAR(%s)',
                    'DATE_SHOW'
                )
            ],
            'order' => [
                'DATE_SHOW' => 'DESC'
            ]
        ], 'NAME', 'NAME');
    }

    public static function clearComponentCache(Main\Entity\Event $event)
    {
        if (!Main\Context::getCurrent()->getRequest()->isAdminSection()) {
            return;
        }

        try {
            Main\Application::getInstance()->getTaggedCache()->clearByTag('kelnik:infoProcList');
        } catch (\Exception $e) {
        }
    }
}
