<?php

namespace Kelnik\Infrastructure\Model;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class PlatformTable extends DataManager
{
    public static function getTableName()
    {
        return 'kelnik_infrastructure_platform';
    }

    public static function getMap()
    {
        $res = [
            (new IntegerField('ID'))
                ->configureTitle('ID')
                ->configurePrimary(true)
                ->configureAutocomplete(true),

            (new IntegerField('SORT'))
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_SORT'))
                ->configureDefaultValue(self::SORT_DEFAULT),

            (new IntegerField('IMAGE_ID'))
                ->configureDefaultValue(0)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_IMAGE')),
            (new IntegerField('VIDEO_ID'))
                ->configureDefaultValue(0)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_VIDEO')),

            (new BooleanField('ACTIVE'))
                ->configureValues(self::NO, self::YES)
                ->configureDefaultValue(self::YES)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_ACTIVE')),

            (new StringField('NAME_RU'))
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_NAME'))
                ->configureSize(255),
            (new StringField('NAME_EN'))
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_NAME_EN'))
                ->configureSize(255),

            (new StringField('MAP_COORDS_LAT'))
                ->configureSize(50)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_MAP_COORDS_LAT')),
            (new StringField('MAP_COORDS_LNG'))
                ->configureSize(50)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_MAP_COORDS_LNG')),

            (new StringField('MAP_COORDS_CENTER_LAT'))
                ->configureSize(50)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_MAP_COORDS_CENTER_LAT')),
            (new StringField('MAP_COORDS_CENTER_LNG'))
                ->configureSize(50)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_MAP_COORDS_CENTER_LNG')),

            (new StringField('PLANOPLAN'))
                ->configureSize(255)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_PLANOPLAN')),
        ];

        foreach (self::getFields() as $field) {
            foreach (self::getLangs() as $fieldLang) {
                $res[] = (new StringField($field . '_' . $fieldLang . '_TEXT_TYPE'))
                            ->configureDefaultValue('html');

                $res[] = (new TextField($field . '_' . $fieldLang))
                            ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_' . $field . '_' . $fieldLang));
            }
        }

        foreach (self::getLangs() as $fieldLang) {
            $res[] = (new Reference(
                'IMAGES_' . $fieldLang,
                '\Kelnik\Infrastructure\Model\Images' . ucfirst(strtolower($fieldLang)) . 'Table',
                Join::on('this.ID', 'ref.ENTITY_ID')
            ))->configureJoinType('LEFT');
        }

        return $res;
    }

    public static function getLangs()
    {
        return ['RU', 'EN'];
    }

    public static function getFields()
    {
        return [
            'TEXT',
            'TEXT_FEATURES',
            'TEXT_TRAITS',
            'TEXT_AREA',
            'TEXT_MAP',
            'TEXT_INFRA',
            'TEXT_CUSTOMS',
            'TEXT_GALLERY',
            'TEXT_ADVANTAGES1',
            'TEXT_ADVANTAGES2',
            'TEXT_ADVANTAGES3'
        ];
    }

    public static function clearComponentCache(Event $event)
    {
        if (!Context::getCurrent()->getRequest()->isAdminSection()) {
            return;
        }

        Application::getInstance()->getTaggedCache()->clearByTag('kelnik:infrastructureList');
        Application::getInstance()->getTaggedCache()->clearByTag(
            'kelnik:infrastructureRow_' . ArrayHelper::getValue($event->getParameter('id'), 'ID', 0)
        );

        parent::clearComponentCache($event);
    }
}
