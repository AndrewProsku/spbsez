<?php

namespace Kelnik\Messages\Model;

use Bitrix\Main\Application;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Error;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\UpdateResult;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
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
            (new IntegerField('ID'))
                ->configureAutocomplete(true)
                ->configurePrimary(true)
                ->configureTitle(Loc::getMessage('KELNIK_MESSAGES_ID')),

            (new IntegerField('USER_ID'))
                ->configureTitle(Loc::getMessage('KELNIK_MESSAGES_USER_ID'))
                ->configureDefaultValue(0),

            (new DatetimeField('DATE_CREATED'))
                ->configureTitle(Loc::getMessage('KELNIK_MESSAGES_DATE_CREATED')),

            (new DatetimeField('DATE_MODIFIED'))
                ->configureTitle(Loc::getMessage('KELNIK_MESSAGES_DATE_MODIFIED')),

            (new StringField('ACTIVE'))
                ->configureTitle(Loc::getMessage('KELNIK_MESSAGES_ACTIVE'))
                ->configureDefaultValue(self::NO),

            (new StringField('NAME'))
                ->configureTitle(Loc::getMessage('KELNIK_MESSAGES_NAME')),

            new StringField('TEXT_TEXT_TYPE'),

            (new TextField('TEXT'))
                ->configureTitle(Loc::getMessage('KELNIK_MESSAGES_TEXT')),

            // Список компаний (определенный список пользователей)
            // для агрегации пользователей
            //
            (new Reference(
                'COMPANIES',
                MessageCompaniesTable::class,
                Join::on('this.ID', 'ref.MESSAGE_ID')
            ))->configureJoinType('LEFT'),

            (new Reference(
                'FILES',
                MessageFilesTable::class,
                Join::on('this.ID', 'ref.ENTITY_ID')
            ))->configureJoinType('LEFT'),

            // Список пользователей для получения данного сообщения
            //
            (new Reference(
                'USERS',
                MessageUsersTable::class,
                Join::on('this.ID', 'ref.MESSAGE_ID')
            ))->configureJoinType('LEFT'),

            new ExpressionField(
                'USER_CNT',
                'COUNT(DISTINCT %s)',
                'USERS.USER_ID'
            )
        ];
    }

    public static function add(array $data)
    {
        global $USER;

        $data['DATE_CREATED'] = $data['DATE_MODIFIED'] = new DateTime();
        $data['USER_ID'] = (int)$USER->GetID();

        return parent::add($data);
    }

    public static function update($id, array $data)
    {
        $origData = self::getById($id)->fetch();
        $data['DATE_MODIFIED'] = new DateTime();

        if ($origData && $origData['ACTIVE'] === self::YES) {
            return (new UpdateResult())
                    ->addError(
                        new Error(Loc::getMessage('KELNIK_MESSAGES_ERROR_NO_RIGHT'))
                    );
        }

        return parent::update($id, $data);
    }

    public static function clearComponentCache(Event $event)
    {
        try {
            $rowData = self::getRowById((int) ArrayHelper::getValue($event->getParameters(), 'id', 0));
            $userId = (int)ArrayHelper::getValue($rowData, 'USER_ID', 0);

            Application::getInstance()->getTaggedCache()->clearByTag('kelnik:messagesList_' . $userId);
            Application::getInstance()->getTaggedCache()->clearByTag('bitrix:menuPersonal_' . $userId);
        } catch (\Exception $e) {
        }

        parent::clearComponentCache($event);
    }
}
