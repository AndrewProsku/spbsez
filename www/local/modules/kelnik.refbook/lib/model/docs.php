<?php

namespace Kelnik\Refbook\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class DocsTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_refbook_docs';
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
            new Main\Entity\IntegerField(
                'FILE_ID',
                [
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
            new Main\Entity\StringField(
                'ACTIVE',
                [
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES,
                    'title' => Loc::getMessage('KELNIK_DOCS_ACTIVE'),
                ]
            ),
            new Main\Entity\StringField(
                'SITE_ID',
                [
                    'title' => Loc::getMessage('KELNIK_DOCS_SITE'),
                ]
            ),
            new Main\Entity\StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_DOCS_NAME'),
                ]
            )
        ];
    }
}
