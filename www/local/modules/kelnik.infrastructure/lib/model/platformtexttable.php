<?php

namespace Kelnik\Infrastructure\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class PlatformTextTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_infrastructure_platform_text';
    }

    public static function getMap()
    {
        return [
            (new IntegerField('ID'))
                ->configureTitle('ID')
                ->configurePrimary(true)
                ->configureAutocomplete(true),

            (new IntegerField('PLATFORM_ID'))
                ->configureDefaultValue(0),

            (new StringField('TYPE')),

            (new TextField('TEXT_RU')),
            (new TextField('TEXT_EN')),

            (new StringField('TEXT_RU_TEXT_TYPE'))
                ->configureDefaultValue('html'),
            (new StringField('TEXT_EN_TEXT_TYPE'))
                ->configureDefaultValue('html'),

            (new Reference(
                'PLATFORM',
                PlatformTable::class,
                Join::on('this.PLATFORM_ID', 'ref.ID')
            ))->configureJoinType('INNER')
        ];
    }
}
