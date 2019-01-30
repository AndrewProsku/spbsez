<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 22.01.19
 * Time: 17:08
 */

namespace Kelnik\Userdata;


use Kelnik\Helpers\ArrayHelper;

class Admin
{
    public const GROUP_SUPER_ADMIN = 1;
    public const GROUP_ADMIN = 7;
    public const GROUP_MODERATOR = 8;
    public const GROUP_RESIDENT_SUPER_ADMIN = 9;
    public const GROUP_RESIDENT_ADMIN = 10;

    private const CAN_REPORT = 'UF_CAN_REPORT';
    private const CAN_REQUEST = 'UF_CAN_REQUEST';
    private const CAN_MSG = 'UF_CAN_OEZ_MSG';

    private $userId;
    private $user;
    private $userGroups = [];

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->user = \CUser::GetByID($userId)->Fetch();

        if (empty($this->user['ID'])) {
            throw new \Exception('Can\'t load user by ID #' . $userId);
        }

        $tmp = \CUser::GetUserGroupList($this->userId);

        $timeFormat = 'd.m.Y H:i:s';

        while($row = $tmp->Fetch()) {
            if ($row['GROUP_ID'] == 2) {
                continue;
            }

            $dateFrom = ArrayHelper::getValue($row, 'DATE_ACTIVE_FROM');
            $dateTo = ArrayHelper::getValue($row, 'DATE_ACTIVE_TO');

            if ($dateFrom
                && $dateFrom = \DateTime::createFromFormat($timeFormat, $dateFrom)->getTimestamp()
                && $dateFrom < time()
            ) {
                continue;
            }

            if ($dateTo
                && $dateTo = \DateTime::createFromFormat($timeFormat, $dateTo)->getTimestamp()
                    && $dateTo > time()
            ) {
                continue;
            }

            $this->userGroups[] = $row['GROUP_ID'];
        }
    }

    /**
     * Список контролируемых учетных записей
     *
     * @return array
     */
    public function getEditableUsers(): array
    {
        $res = [];

        if (!$this->userGroups || !$groups = $this->getEditableGroups()) {
            return $res;
        }

        $tmp = \CUser::GetList(
            ($by = 'ID'),
            ($order = 'DESC'),
            [
                'GROUPS_ID' => $groups,
                '=UF_ADMIN_ID' => $this->userId
            ],
            [
                'SELECT' => [
                    'NAME', 'LAST_NAME', 'SECOND_NAME',
                    'LOGIN', 'EMAIL', 'PERSONAL_PHONE'
                ]
            ]
        );

        while ($row = $tmp->Fetch()) {
            $row['FULL_NAME'] = Data::getUserFullName($row);
            $res[$row['ID']] = $row;
        }

        return $res;
    }

    private function getEditableGroups()
    {
        if ($this->canEditResidentAdmin()) {
            return [self::GROUP_RESIDENT_ADMIN];
        }

        return [];
    }

    public function canEditResidentAdmin()
    {
        return array_intersect(
            $this->userGroups,
            [
                self::GROUP_RESIDENT_SUPER_ADMIN
            ]
        );
    }

    public function canEditResident()
    {
        return array_intersect(
            $this->userGroups,
            [
                self::GROUP_RESIDENT_SUPER_ADMIN,
                self::GROUP_RESIDENT_ADMIN
            ]
        );
    }

    /**
     * Проверка возможности отправлять отчет
     *
     * @return bool
     */
    public function canReport()
    {
        return (bool) ArrayHelper::getValue($this->user, self::CAN_REPORT);
    }

    /**
     * Проверка возможности делать запрос
     *
     * @return bool
     */
    public function canRequest()
    {
        return (bool) ArrayHelper::getValue($this->user, self::CAN_REQUEST);
    }

    /**
     * Проверка возможности получать сообщения от ОЭЗ
     *
     * @return bool
     */
    public function canMessages()
    {
        return (bool) ArrayHelper::getValue($this->user, self::CAN_MSG);
    }

    /**
     * Проверка меню раздела личного кабиента
     *
     * @param \CUser $user
     * @param array $menu
     * @return array
     */
    public static function checkMenu(\CUser $user, array $menu)
    {
        if (!$user->IsAuthorized() || !$menu) {
            return $menu;
        }

        try {
            $admin = new self($user->GetID());

            foreach ($menu as &$v) {
                if (empty($v[3]['check']) || !method_exists($admin, $v[3]['check'])) {
                    continue;
                }

                $v[4] = $admin->{$v[3]['check']}() ? '' : 'false';
                $v[3] = [];
            }
        } catch (\Exception $e) {
        }

        return $menu;
    }
}
