<?php

namespace Kelnik\Questions\Model;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class QuestionsTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_questions';
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => 'ID'
                ]
            ),
            new IntegerField(
                'SORT',
                [
                    'title' => Loc::getMessage('KELNIK_QUESTIONS_SORT'),
                    'default_value' => 500
                ]
            ),
            new StringField(
                'ACTIVE',
                [
                    'values' => ['N', 'Y'],
                    'title'  => Loc::getMessage('KELNIK_QUESTIONS_ACTIVE')
                ]
            ),

            new StringField(
                'NAME',
                [
                    'title'  => Loc::getMessage('KELNIK_QUESTIONS_NAME')
                ]
            ),

            new StringField(
                'URL',
                [
                    'title'  => Loc::getMessage('KELNIK_QUESTIONS_URL')
                ]
            ),

            new TextField(
                'TEXT',
                [
                    'title' => Loc::getMessage('KELNIK_QUESTIONS_TEXT')
                ]
            ),
            new StringField(
                'TEXT_TEXT_TYPE'
            ),

            new StringField(
                'LANG',
                [
                    'values' => ['ru', 'en'],
                    'title' => Loc::getMessage('KELNIK_QUESTIONS_LANG')
                ]
            ),
        ];
    }
}
