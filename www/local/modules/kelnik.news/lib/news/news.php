<?php

namespace Kelnik\News\News;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;
use Kelnik\News\Categories\CategoriesTable;

Loc::loadMessages(__FILE__);

/**
 * Модель новостей.
 */
class NewsTable extends DataManager
{
    const ITEMS_ON_PAGE = 20;

    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_news';
    }

    /**
     * {@inheritdoc}
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                ]
            ),
            new IntegerField(
                'CAT_ID',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_CAT_NAME')
                ]
            ),
            new IntegerField(
                'CREATED_BY',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_CREATED_BY'),
                    'default_value' => static::getUserId()
                ]
            ),
            new IntegerField(
                'MODIFIED_BY',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_MODIFIED_BY'),
                    'default_value' => static::getUserId()
                ]
            ),
            new IntegerField(
                'IMAGE',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_IMAGE')
                ]
            ),
            new IntegerField(
                'IMAGE_PREVIEW',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_IMAGE_PREVIEW')
                ]
            ),
            new DatetimeField(
                'DATE_CREATE',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_DATE_CREATE'),
                    'default_value' => new DateTime()
                ]
            ),
            new DatetimeField(
                'DATE_SHOW',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_DATE_SHOW'),
                    'default_value' => new DateTime()
                ]
            ),
//            new DatetimeField(
//                'DATE_ACTION_START',
//                [
//                    'title' => Loc::getMessage('KELNIK_NEWS_DATE_ACTION_START'),
//                    'default_value' => null
//                ]
//            ),
//            new DatetimeField(
//                'DATE_ACTION_FINISH',
//                [
//                    'title' => Loc::getMessage('KELNIK_NEWS_DATE_ACTION_FINISH'),
//                    'default_value' => null
//                ]
//            ),
            new DatetimeField(
                'DATE_MODIFY',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_DATE_MODIFY'),
                    'default_value' => new DateTime()
                ]
            ),
            new StringField(
                'ACTIVE',
                [
                    'values' => [self::NO, self::YES],
                    'title' => Loc::getMessage('KELNIK_NEWS_ACTIVE')
                ]
            ),
            new StringField(
                'CODE',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_ALIAS')
                ]
            ),
            new StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_TITLE')
                ]
            ),
            new TextField(
                'TEXT_PREVIEW',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_PREVIEW')
                ]
            ),
            new StringField('TEXT_PREVIEW_TEXT_TYPE'),
            new TextField(
                'TEXT',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_TEXT')
                ]
            ),
            new StringField('TEXT_TEXT_TYPE'),
            new StringField(
                'SHOW_SLIDER',
                [
                    'values' => [self::NO, self::YES],
                    'title' => Loc::getMessage('KELNIK_NEWS_SHOW_SLIDER')
                ]
            ),

            new ReferenceField(
                'CAT',
                CategoriesTable::class,
                [
                    '=this.CAT_ID' => 'ref.ID'
                ]
            ),
            new ReferenceField(
                'IMAGES',
                ImageToNewsTable::class,
                [
                    '=this.ID' => 'ref.ENTITY_ID',
                ]
            ),
            new ReferenceField(
                'TAGS',
                TagToNewsTable::class,
                [
                    '=this.ID' => 'ref.ENTITY_ID'
                ]
            ),
            new ExpressionField(
                'TAGS_LIST',
                '(SELECT GROUP_CONCAT(DISTINCT CONCAT("[", `t`.`ID`, "] ", `t`.`NAME`) SEPARATOR ", ") '
                . 'FROM `' . TagToNewsTable::getTableName() . '` `ttn` '
                . 'INNER JOIN `' . TagsTable::getTableName() . '` `t` ON `t`.`ID` = `ttn`.`VALUE` '
                . 'WHERE `ttn`.`ENTITY_ID` = %s '
                . 'ORDER BY `t`.`NAME`) ',
                [
                    'ID'
                ]
            ),
        ];
    }

    /**
     * @param mixed $primary
     * @param array $data
     * @return \Bitrix\Main\Entity\UpdateResult
     * @throws \Bitrix\Main\ObjectException
     */
    public static function update($primary, array $data)
    {
        $data['DATE_MODIFY'] = new DateTime();
        $date['MODIFIED_BY'] = self::getUserId();

        return parent::update($primary, $data);
    }

    /**
     * @return null
     */
    public static function getUserId()
    {
        global $USER;

        return (int)$USER->GetID();
    }

    /**
     * @return string
     */
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function clearComponentCache(Event $event)
    {
        if (!Context::getCurrent()->getRequest()->isAdminSection()) {
            return;
        }

        try {

            $catId     = ArrayHelper::getValue($event->getParameters(), 'fields.CAT_ID', false);
            $primaryId = ArrayHelper::getValue($event->getParameters(), 'primary.ID', 0);

            if ($primaryId && false === $catId) {
                $catId = ArrayHelper::getValue(
                    self::getRow([
                        'select' => ['CAT_ID'],
                        'filter' => [
                            '=ID' => $primaryId
                        ]
                    ]),
                    'CAT_ID'
                );
            }

            Application::getInstance()->getTaggedCache()->clearByTag('kelnik:newsList_' . (int)$catId);
            Application::getInstance()->getTaggedCache()->clearByTag('kelnik:newsRow_' . $primaryId);
        } catch (\Exception $e) {
        }
    }
}
