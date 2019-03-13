<?php

namespace Kelnik\Info\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;

Loc::loadMessages(__FILE__);

class DocsTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_info_docs';
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
                    'title' => Loc::getMessage('KELNIK_DOCS_ID')
                ]
            ),
            new Main\ORM\Fields\IntegerField(
                'TYPE_ID',
                [
                    'default_value' => 0,
                    'title' => Loc::getMessage('KELNIK_DOCS_TYPE')
                ]
            ),
            new Main\Entity\IntegerField(
                'FILE_ID',
                [
                    'default_value' => 0,
                    'title' => Loc::getMessage('KELNIK_DOCS_FILE'),
                ]
            ),
            new Main\Entity\IntegerField(
                'SORT',
                [
                    'default_value' => 500,
                    'title' => Loc::getMessage('KELNIK_DOCS_SORT'),
                ]
            ),
            new Main\Entity\DateField(
                'DATE_SHOW',
                [
                    'default_value' => new Main\Type\Date(),
                    'title' => Loc::getMessage('KELNIK_DOCS_DATE')
                ]
            ),
            new Main\Entity\StringField(
                'ACTIVE',
                [
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES,
                    'title' => Loc::getMessage('KELNIK_DOCS_ACTIVE'),
                ]
            ),
            new Main\Entity\StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_DOCS_NAME'),
                ]
            ),

            new Main\Entity\ReferenceField(
                'TYPE',
                TypesTable::class,
                [
                    '=this.TYPE_ID' => 'ref.ID'
                ]
            )
        ];
    }

    public static function clearComponentCache(Main\ORM\Event $event)
    {
        if (!Main\Context::getCurrent()->getRequest()->isAdminSection()) {
            return;
        }

        try {
            Main\Application::getInstance()->getTaggedCache()->clearByTag(
                'kelnik:infoDocsList_' . ArrayHelper::getValue($event->getParameter('id'), 'ID', 0)
            );
        } catch (\Exception $e) {
        }
    }
}
