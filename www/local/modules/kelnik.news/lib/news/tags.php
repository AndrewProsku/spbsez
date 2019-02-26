<?php

namespace Kelnik\News\News;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

/**
 * Модель новостей.
 */
class TagsTable extends DataManager
{
    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_news_tags';
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
                'SORT',
                [
                    'default_value' => 500,
                    'title' => Loc::getMessage('KELNIK_NEWS_TAGS_SORT'),
                ]
            ),
            new StringField(
                'ACTIVE',
                [
                    'values' => [self::NO, self::YES],
                    'title' => Loc::getMessage('KELNIK_NEWS_TAGS_ACTIVE')
                ]
            ),
            new StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_NEWS_TAGS_TITLE')
                ]
            ),

            new ReferenceField(
                'NEWS_TAG',
                TagToNewsTable::class,
                [
                    '=this.ID' => 'ref.VALUE'
                ]
            )
        ];
    }

    public static function prepareTags(array $tags, $folder = '/')
    {
        if (!$tags) {
            return $tags;
        }

        foreach ($tags as &$v) {
            if (isset($v['NEWS_IDS'])) {
                $v['NEWS_IDS'] = explode(',', $v['NEWS_IDS']);
            }

            $v['LINK'] = $folder . '?tag=' . (isset($v['ALIAS']) ? $v['ALIAS'] : $v['ID']);
        }
        unset($v);

        return $tags;
    }
}
