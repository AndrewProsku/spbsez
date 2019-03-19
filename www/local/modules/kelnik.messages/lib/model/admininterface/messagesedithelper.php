<?php
namespace Kelnik\Messages\Model\AdminInterface;

use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;
use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Messages\MessageService;
use Kelnik\Messages\Model\MessageCompaniesTable;
use Kelnik\Messages\Model\MessagesTable;
use Kelnik\Messages\Model\MessageUsersTable;
use Kelnik\Userdata\Profile\Profile;

class MessagesEditHelper extends AdminEditHelper
{
    protected static $model = MessagesTable::class;

    public function hasWriteRights()
    {
        $origData = $this->loadElement();
        if (!empty($origData['ACTIVE']) && $origData['ACTIVE'] === MessagesTable::YES) {
            return false;
        }

        return parent::hasWriteRights();
    }

    public function hasWriteRightsElement($element = [])
    {
        if (empty($element['ID']) || empty($element['ACTIVE'])) {
            return parent::hasWriteRightsElement($element);
        }

        return $element['ACTIVE'] !== MessagesTable::YES;
    }

    public function saveElement($id = null)
    {
        $res = parent::saveElement($id);

        $data = $res->getData();

        if ($res->isSuccess() && $data['ACTIVE'] === MessagesTable::YES) {
            self::updateUsers($id);
        }

        return $res;
    }

    public function deleteElement($id)
    {
        $res = parent::deleteElement($id);

        if ($res->isSuccess()) {
            self::updateUsers($id);
        }

        return $res;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Bitrix\Main\ArgumentTypeException
     */
    protected static function updateUsers($id): bool
    {
        $id = (int) $id;

        if (!$id) {
            return false;
        }

        try {
            Application::getConnection()->query('DELETE FROM `' . MessageUsersTable::getTableName() . '` WHERE `MESSAGE_ID` = ' . $id);
            $companies = MessageCompaniesTable::getList([
                'filter' => [
                    '=MESSAGE_ID' => $id
                ]
            ]);
            Application::getInstance()->getTaggedCache()->clearByTag('kelnik:messagesList');
        } catch (\Exception $e) {
            return false;
        }

        $companies = ArrayHelper::getColumn($companies, 'USER_ID');

        if (!$companies) {
            return false;
        }

        $tmp = \CUser::GetList(
            ($by = 'ID'),
            ($order = 'DESC'),
            [
                'GROUPS_ID' => Profile::GROUP_RESIDENT,
                Profile::OWNER_FIELD => $companies
            ],
            [
                'SELECT' => [
                    Profile::OWNER_FIELD
                ],
                'FIELDS' => [
                    'ID'
                ]
            ]
        );

        if (!$tmp->AffectedRowsCount()) {
            return false;
        }

        $users = [];
        $sqlHelper = Application::getConnection()->getSqlHelper();

        foreach ($companies as $row) {
            $users[] = '(' . $sqlHelper->convertToDbInteger($id) . ', ' .
                        $sqlHelper->convertToDbInteger($row) . ', ' .
                        $sqlHelper->convertToDbDateTime(new DateTime()) .
                        ')';
        }

        while($row = $tmp->Fetch()) {
            $users[] = '(' . $sqlHelper->convertToDbInteger($id) . ', ' .
                        $sqlHelper->convertToDbInteger($row['ID']) . ', ' .
                        $sqlHelper->convertToDbDateTime(new DateTime()) .
                        ')';
        }

        try {
            Application::getConnection()->query(
                'INSERT INTO `' . MessageUsersTable::getTableName() . '` (`MESSAGE_ID`, `USER_ID`, `DATE_MODIFIED`) ' .
                'VALUES ' . implode($users, ', ')
            );
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
