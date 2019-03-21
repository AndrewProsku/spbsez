<?php

namespace Kelnik\Infrastructure\Model;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\ExpressionField;
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
            (new IntegerField('IMAGE_BG_ID'))
                ->configureDefaultValue(0)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_IMAGE_BG')),
            (new IntegerField('AREA_BG_ID_RU'))
                ->configureDefaultValue(0)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_AREA_BG_RU')),
            (new IntegerField('AREA_BG_ID_EN'))
                ->configureDefaultValue(0)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_AREA_BG_EN')),

            (new BooleanField('ACTIVE'))
                ->configureValues(self::NO, self::YES)
                ->configureDefaultValue(self::YES)
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_ACTIVE')),

            (new StringField('ALIAS'))
                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_ALIAS'))
                ->configureRequired(true)
                ->configureUnique(true)
                ->configureFormat('!^([a-z0-9_\-.]+)$!si'),
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
                if (false !== strpos($field, 'HEADER')) {
                    $res[] = (new StringField($field . '_' . $fieldLang))
                                ->configureSize(255)
                                ->configureTitle(Loc::getMessage('KELNIK_INFRASTRUCTURE_' . $field . '_' . $fieldLang));
                    continue;
                }
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
            'HEADER_GALLERY',
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

    /**
     * Создает поля с нужным текстом.
     *
     * например, $langName = 'ru';
     * входной массив:
     * [
     *  'NAME_RU' => 'ru text',
     *  'NAME_EN' => 'en text'
     * ]
     *
     * на выходе:
     * [
     *  'NAME' => 'ru text'
     * ]
     *
     * @param array $fields
     * @param $langName
     * @return array
     */
    public static function replaceFieldsByLang(array $fields, $langName)
    {
        if (!$fields || !$langName) {
            return $fields;
        }

        $langName = mb_strtoupper($langName);
        $langFields = array_merge(
            self::getFields(),
            [
                'NAME', 'AREA_BG_ID'
            ]
        );

        foreach ($langFields as $fieldName) {
            if (!isset($fields[$fieldName . '_' . $langName])) {
                continue;
            }
            $fields[$fieldName] = $fields[$fieldName . '_' . $langName];
            foreach (self::getLangs() as $fieldLang) {
                unset($fields[$fieldName . '_' . $fieldLang], $fields[$fieldName . '_' . $fieldLang . '_TEXT_TYPE']);
            }
        }

        return $fields;
    }

    public static function getAdminAssocList(): array
    {
        $filter = [
            'select' => [
                'ID',
                new ExpressionField(
                    'NAME_',
                    'CONCAT("[", %s, "] ", IF(%s IS NULL, \'null\', %s))',
                    [
                        'ID', 'NAME_RU', 'NAME_RU'
                    ]
                )
            ]
        ];

        $fields = self::getMap();

        if (isset($fields['SORT'])) {
            $filter['order']['SORT'] = 'ASC';
        }

        if (isset($fields['NAME_RU'])) {
            $filter['order']['NAME_RU'] = 'ASC';
        }

        return self::getAssoc($filter, 'ID', 'NAME_');
    }
}
