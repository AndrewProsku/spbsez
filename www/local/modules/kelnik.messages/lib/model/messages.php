<?php

namespace Kelnik\Messages\Model;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class MessagesTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_messages';
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
                    'title' => Loc::getMessage('KELNIK_MESSAGES_ID')
                ]
            ),
            new Main\Entity\IntegerField(
                'USER_ID',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_USER_ID')
                ]
            ),
            new Main\Entity\DatetimeField(
                'DATE_CREATED',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_DATE_CREATED')
                ]
            ),
            new Main\Entity\DatetimeField(
                'DATE_MODIFIED',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_DATE_MODIFIED')
                ]
            ),
            new Main\Entity\StringField(
                'ACTIVE',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_ACTIVE'),
                    'default_value' => self::NO
                ]
            ),
            new Main\Entity\StringField(
                'NAME',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_NAME')
                ]
            ),
            new Main\Entity\StringField('TEXT_TEXT_TYPE'),
            new Main\Entity\TextField(
                'TEXT',
                [
                    'title' => Loc::getMessage('KELNIK_MESSAGES_TEXT')
                ]
            ),


            new Main\Entity\ReferenceField(
                'USERS',
                MessageUsersTable::class,
                [
                    'this.ID' => 'ref.MESSAGE_ID'
                ]
            )
        ];
    }

    public static function add(array $data)
    {
        global $USER;

        $data['DATE_CREATED'] = $data['DATE_MODIFIED'] = new Main\Type\DateTime();
        $data['USER_ID'] = $USER->GetID();

        return parent::add($data);
    }

    public static function update($id, array $data)
    {
        $origData = self::getById($id)->fetch();

        if ($origData && $origData['ACTIVE'] === self::YES) {
            return (new Main\ORM\Data\UpdateResult())
                    ->addError(
                        new Main\Error(Loc::getMessage('KELNIK_MESSAGES_ERROR_NO_RIGHT'))
                    );
        }

        return parent::update($id, $data);
    }
}
