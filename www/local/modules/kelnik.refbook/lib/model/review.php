<?php

namespace Kelnik\Refbook\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ReviewTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_refbook_review';
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
                    'title' => Loc::getMessage('KELNIK_REVIEW_ID')
                ]
            ),
            new Main\Entity\IntegerField(
                'IMAGE_ID',
                [
                    'title' => Loc::getMessage('KELNIK_REVIEW_IMAGE'),
                ]
            ),
            new Main\Entity\IntegerField(
                'IMAGE_BG_ID',
                [
                    'title' => Loc::getMessage('KELNIK_REVIEW_IMAGE_BG'),
                ]
            ),
            new Main\Entity\IntegerField(
                'SORT',
                [
                    'default_value' => 500,
                    'title' => Loc::getMessage('KELNIK_REVIEW_SORT'),
                ]
            ),
            new Main\Entity\StringField(
                'ACTIVE',
                [
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES,
                    'title' => Loc::getMessage('KELNIK_REVIEW_ACTIVE'),
                ]
            ),
            new Main\Entity\StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_REVIEW_NAME'),
                ]
            ),
            new Main\Entity\StringField(
                'ALIAS',
                [
                    'title' => Loc::getMessage('KELNIK_REVIEW_ALIAS'),
                ]
            ),
            new Main\Entity\StringField(
                'COMMENT',
                [
                    'title' => Loc::getMessage('KELNIK_REVIEW_COMMENT'),
                ]
            ),
            new Main\Entity\StringField('BODY_TEXT_TYPE'),
            new Main\Entity\StringField('PREVIEW_TEXT_TYPE'),
            new Main\Entity\TextField(
                'BODY',
                [
                    'title' => Loc::getMessage('KELNIK_REVIEW_TEXT'),
                ]
            ),
            new Main\Entity\TextField(
                'PREVIEW',
                [
                    'title' => Loc::getMessage('KELNIK_REVIEW_PREVIEW'),
                ]
            )
        ];
    }
}
