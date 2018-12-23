<?php

namespace Kelnik\Userdata\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ContactTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_userdata_contact';
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
                    'title' => Loc::getMessage('KELNIK_USERDATA_ID')
                ]
            ),
            new Main\Entity\IntegerField(
                'USER_ID',
                [
                    'title' => Loc::getMessage('KELNIK_USERDATA_USER_ID'),
                ]
            ),
            new Main\Entity\DatetimeField(
                'DATE_CREATED',
                [
                    'title' => Loc::getMessage('KELNIK_USERDATA_DATE_CREATED')
                ]
            ),
            new Main\Entity\DatetimeField(
                'DATE_MODIFIED',
                [
                    'title' => Loc::getMessage('KELNIK_USERDATA_DATE_MODIFIED')
                ]
            ),
            new Main\Entity\StringField(
                'FIO',
                [
                    'title' => Loc::getMessage('KELNIK_USERDATA_FIO'),
                ]
            ),
            new Main\Entity\StringField(
                'POSITION',
                [
                    'title' => Loc::getMessage('KELNIK_USERDATA_POSITION'),
                ]
            ),
            new Main\Entity\StringField(
                'PHONE',
                [
                    'title' => Loc::getMessage('KELNIK_USERDATA_PHONE'),
                ]
            ),
            new Main\Entity\StringField(
                'EMAIL',
                [
                    'title' => Loc::getMessage('KELNIK_USERDATA_EMAIL'),
                ]
            ),
        ];
    }

    public static function add(array $data)
    {
        $data['DATE_CREATED'] = $data['DATE_MODIFIED'] = new Main\Type\DateTime();

        return parent::add($data);
    }

    public static function update($primary, array $data)
    {
        $data['DATE_MODIFIED'] = new Main\Type\DateTime();
        return parent::update($primary, $data);
    }

    public static function getListByUser($userId)
    {
        $userId = (int) $userId;

        if (!$userId) {
            return [];
        }

        try {
            $res = self::getAssoc([
                'filter' => [
                    '=USER_ID' => $userId
                ],
                'order'  => [
                    'ID' => 'ASC'
                ]
            ], 'ID');
        } catch (\Exception $e) {
            $res = [];
        }

        return $res;
    }
}
