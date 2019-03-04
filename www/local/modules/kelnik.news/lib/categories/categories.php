<?php

namespace Kelnik\News\Categories;

use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

/**
 * Модель категорий новостей.
 */
class CategoriesTable extends DataManager
{
    public const NEWS_RU = 1;
    public const NEWS_EN = 3;
    public const ARTICLES_RU = 2;
    public const ARTICLES_EN = 4;

    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_news_categories';
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
            new DatetimeField(
                'DATE_MODIFY',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_CAT_DATE_MODIFY'),
                    'default_value' => new DateTime()
                ]
            ),
            new IntegerField(
                'CREATED_BY',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_CAT_CREATED_BY'),
                    'default_value' => static::getUserId()
                ]
            ),
            new IntegerField(
                'MODIFIED_BY',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_CAT_MODIFIED_BY'),
                    'default_value' => static::getUserId()
                ]
            ),
            new StringField(
                'ACTIVE',
                [
                    'values' => [self::NO, self::YES],
                    'title' => Loc::getMessage('KELNIK_NEWS_CAT_ACTIVE')
                ]
            ),
            new StringField(
                'CODE',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_CAT_ALIAS')
                ]
            ),
            new StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_CAT_TITLE')
                ]
            )
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function update($primary, array $data)
    {
        $data['DATE_MODIFY'] = new DateTime();
        $date['MODIFIED_BY'] = self::getUserId();

        return parent::update($primary, $data);
    }

    /**
     * Возвращает идентификатор пользователя.
     *
     * @return int|null
     */
    public static function getUserId()
    {
        global $USER;

        return $USER->GetID();
    }

    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getComponentList(): array
    {
        return array_merge(
            [
                0 => ''
            ],
            self::getAdminAssocList()
        );
    }
}
