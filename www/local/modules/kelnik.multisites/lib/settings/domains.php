<?php

namespace Kelnik\Multisites\Settings;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

/**
 * Модель сайтов для модуля мультисайтовости.
 */
class DomainsTable extends DataManager
{
    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_multisites_domains';
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new IntegerField('ENTITY_ID'),
            new StringField('VALUE')
        ];
    }
}
