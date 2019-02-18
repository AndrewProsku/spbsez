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

            // Список компаний (определенный список пользователей)
            // для агрегации пользователей
            //
            (new Main\ORM\Fields\Relations\Reference(
                'COMPANIES',
                MessageCompaniesTable::class,
                Main\ORM\Query\Join::on('this.ID', 'ref.MESSAGE_ID')
            ))->configureJoinType('LEFT'),

            (new Main\ORM\Fields\Relations\Reference(
                'FILES',
                MessageFilesTable::class,
                Main\ORM\Query\Join::on('this.ID', 'ref.ENTITY_ID')
            ))->configureJoinType('LEFT'),

            // Список пользователей для получения данного сообщения
            //
            (new Main\ORM\Fields\Relations\Reference(
                'USERS',
                MessageUsersTable::class,
                Main\ORM\Query\Join::on('this.ID', 'ref.MESSAGE_ID')
            ))->configureJoinType('LEFT'),

            new Main\Entity\ExpressionField(
                'USER_CNT',
                'COUNT(DISTINCT %s)',
                'USERS.USER_ID'
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
        $data['DATE_MODIFIED'] = new Main\Type\DateTime();

        if ($origData && $origData['ACTIVE'] === self::YES) {
            return (new Main\ORM\Data\UpdateResult())
                    ->addError(
                        new Main\Error(Loc::getMessage('KELNIK_MESSAGES_ERROR_NO_RIGHT'))
                    );
        }

        return parent::update($id, $data);
    }
}
