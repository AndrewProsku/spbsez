<?php

namespace Kelnik\Vacancy\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ResponseTable extends DataManager
{
    public const REQUEST_TIME_LEFT = 120;

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_vacancy_response';
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
                'VACANCY_ID',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_VACANCY')
                ]
            ),
            new Main\Entity\IntegerField(
                'FILE_ID',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_FILE'),
                ]
            ),
            new Main\Entity\DatetimeField(
                'DATE_CREATED',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_DATE_CREATED')
                ]
            ),
            new Main\Entity\StringField('USER_HASH'),
            new Main\Entity\StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_NAME'),
                ]
            ),
            new Main\Entity\StringField(
                'PHONE',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_PHONE'),
                ]
            ),
            new Main\Entity\StringField(
                'EMAIL',
                [
                    'title' => Loc::getMessage('KELNIK_VACANCY_EMAIL'),
                ]
            ),

            new Main\Entity\ReferenceField(
                'VACANCY',
                VacancyTable::class,
                [
                    '=this.VACANCY_ID' => 'ref.ID'
                ]
            )
        ];
    }

    public static function add(array $data)
    {
        if (empty($data['DATE_CREATED'])) {
            $data['DATE_CREATED'] = new Main\Type\DateTime();
        }

        return parent::add($data);
    }

    public static function userCanAddRow($userHash)
    {
        if (!$userHash) {
            return false;
        }

        return !(bool) self::getRow([
            'select' => ['ID'],
            'filter' => [
                '=USER_HASH' => $userHash,
                '>=DATE_CREATED' => Main\Type\DateTime::createFromTimestamp(time() - self::REQUEST_TIME_LEFT)
            ]
        ]);
    }
}
