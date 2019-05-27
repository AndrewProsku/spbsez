<?php

namespace Kelnik\Info\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;

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

            $primaryId = ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0);
            $typeId    = ArrayHelper::getValue($event->getParameters(), 'fields.TYPE_ID', false);

            if ($primaryId && false === $typeId) {
                $typeId = ArrayHelper::getValue(
                    self::getRow([
                        'select' => ['TYPE_ID'],
                        'filter' => [
                            '=ID' => $primaryId
                        ]
                    ]),
                    'TYPE_ID'
                );
            }

            Main\Application::getInstance()->getTaggedCache()->clearByTag('kelnik:infoDocsList_' . (int) $typeId);
        } catch (\Exception $e) {
        }
    }
}
