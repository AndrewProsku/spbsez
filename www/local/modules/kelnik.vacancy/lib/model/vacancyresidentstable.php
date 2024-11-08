<?php

namespace Kelnik\Vacancy\Model;

use Bitrix\Main;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;
use Kelnik\Refbook\Model\ResidentTable;

Loc::loadMessages(__FILE__);

class VacancyResidentsTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_vacancy_residents';
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
                    'title' => Loc::getMessage('KELNIK_VACANCY_ID')
                ]
            ),
            new Main\Entity\IntegerField(
                'SORT',
                [
                    'default_value' => 500,
                    'title' => Loc::getMessage('KELNIK_VACANCY_SORT'),
                ]
            ),
            new Main\Entity\StringField(
                'ACTIVE',
                [
                    'values' => [self::NO, self::YES],
                    'default_value' => self::YES,
                    'title' => Loc::getMessage('KELNIK_VACANCY_ACTIVE'),
                ]
            ),
            new Main\Entity\StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_NAME'),
                ]
            ),
            new Main\Entity\IntegerField(
                'RESIDENT_ID',
                [
                    'title' => Loc::getMessage('KELNIK_RESIDENT_ID'),
                    'default_value' => 0,
                ]
            ),
            new Main\Entity\IntegerField(
                'PRICE_MIN',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_PRICE_MIN'),
                ]
            ),
            new Main\Entity\IntegerField(
                'PRICE_MAX',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_PRICE_MAX'),
                ]
            ),
            new Main\Entity\StringField(
                'PRICE_TEXT',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_PRICE_TEXT'),
                ]
            ),
            new Main\Entity\StringField(
                'EXPERIENCE',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_EXPERIENCE'),
                ]
            ),
            new Main\Entity\StringField(
                'EMPLOYMENT',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_EMPLOYMENT'),
                ]
            ),
            new Main\Entity\StringField(
                'CONTACTS',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_CONTACTS'),
                ]
            ),
            new Main\Entity\StringField('DESCR_TEXT_TYPE'),
            new Main\Entity\StringField('DUTIES_TEXT_TYPE'),
            new Main\Entity\StringField('REQUIREMENTS_TEXT_TYPE'),
            new Main\Entity\StringField('CONDITIONS_TEXT_TYPE'),

            new Main\Entity\TextField(
                'DESCR',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_DESCR'),
                ]
            ),
            new Main\Entity\TextField(
                'DUTIES',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_DUTIES'),
                ]
            ),
            new Main\Entity\TextField(
                'REQUIREMENTS',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_REQUIREMENTS'),
                ]
            ),
            new Main\Entity\TextField(
                'CONDITIONS',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_CONDITIONS'),
                ]
            ),
            new Main\Entity\ReferenceField(
                'RESIDENT',
                ResidentTable::class,
                [
                    '=this.RESIDENT_ID' => 'ref.ID'
                ]
            ),
        ];
    }

    public static function clearComponentCache(Event $event)
    {
        if (Main\Context::getCurrent()->getRequest()->isAdminSection()) {
            Main\Application::getInstance()->getTaggedCache()->clearByTag('kelnik:vacancyList');
        }
        parent::clearComponentCache($event); // TODO: Change the autogenerated stub
    }
}
