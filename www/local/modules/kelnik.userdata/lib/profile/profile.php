<?php

namespace Kelnik\Userdata\Profile;

use Kelnik\Helpers\ArrayHelper;
use Kelnik\Messages\MessageService;

class Profile
{
    public const GROUP_SUPER_ADMIN = 1;
    public const GROUP_ADMIN = 7;
    public const GROUP_MODERATOR = 8;
    public const GROUP_RESIDENT_ADMIN = 9;
    public const GROUP_RESIDENT = 10;

    public const OWNER_FIELD = 'UF_ADMIN_ID';
    public const COMPANY_NAME_FIELD = 'WORK_COMPANY';

    public const CAN_REPORT = 'UF_CAN_REPORT';
    public const CAN_REQUEST = 'UF_CAN_REQUEST';
    public const CAN_MSG = 'UF_CAN_OEZ_MSG';

    private $userId;
    private $user;
    private $userGroups = [];
    private $lastError = '';
    private $companyName = false;

    private static $instance = [];

    public static function getInstance(int $userId)
    {
        if (!empty(self::$instance[$userId])) {
            return self::$instance[$userId];
        }

        return self::$instance[$userId] = new self($userId);
    }

    private function __construct(int $userId)
    {
        $this->userId = (int) $userId;
        $this->user = \CUser::GetByID($this->userId)->Fetch();

        if (empty($this->user['ID'])) {
            throw new \Exception('Can\'t load user by ID #' . $this->userId);
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

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public function getInfo()
    {
        foreach (self::getUserFields() as $field) {
            $res[$field] = ArrayHelper::getValue($this->user, $field);
        }

        $res['FULL_NAME'] = self::getFullName($this->user);

        return $res;
    }

    public function getField($fieldName)
    {
        return ArrayHelper::getValue($this->user, $fieldName, null);
    }

    public function getId()
    {
        return $this->userId;
    }

    public function getCompanyId()
    {
        if ($this->isResidentAdmin()) {
            return $this->getId();
        }

        return (int) $this->getField('UF_ADMIN_ID');
    }

    public function getCompanyName()
    {
        if (false !== $this->companyName) {
            return $this->companyName;
        }

        $this->companyName = $this->getField(self::COMPANY_NAME_FIELD);

        if ($this->isResident()) {
            $adminInfo = \CUser::GetByID($this->getCompanyId())->Fetch();

            $this->companyName = ArrayHelper::getValue($adminInfo, self::COMPANY_NAME_FIELD, '');
        }

        return $this->companyName;
    }

    public function getSezDefaultName()
    {
        return 'ОЭЗ ТВТ в г. Санкт-Петербурге';
    }

    public function update(array $data)
    {
        $allowFields = self::getUserFields();
        $this->lastError = '';

        foreach ($data as $k => $v) {
            $v = trim($v);

            if ($k == 'FULL_NAME') {
                list($data['LAST_NAME'], $data['NAME'], $data['SECOND_NAME']) = explode(' ', $v);
                if (empty($data['NAME'])) {
                    $data['NAME'] = $data['LAST_NAME'];
                    unset($data['LAST_NAME']);
                }
                continue;
            }

            if (!in_array($k, $allowFields)) {
                continue;
            }

            $data[$k] = $v;
        }

        if (!$data) {
            return false;
        }

        $cUser = new \CUser();
        $cUser->Update($this->userId, $data);

        if ($cUser->LAST_ERROR) {
            $this->lastError = $cUser->LAST_ERROR;

            return false;
        }

        return $data;
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
                    self::CAN_MSG,
                    self::CAN_REQUEST,
                    self::CAN_REPORT,
                    self::OWNER_FIELD
                ],
                'FIELDS' => [
                    'ID', 'NAME', 'LAST_NAME', 'SECOND_NAME',
                    'LOGIN', 'EMAIL', 'PERSONAL_PHONE'
                ]
            ]
        );

        while ($row = $tmp->Fetch()) {
            $row['FULL_NAME'] = self::getFullName($row);
            $res[$row['ID']] = $row;
        }

        return $res;
    }

    private function getEditableGroups()
    {
        if ($this->canEditResident()) {
            return [self::GROUP_RESIDENT];
        }

        return [];
    }

    public function isSuperAdmin()
    {
        return array_intersect(
            $this->userGroups,
            [
                self::GROUP_SUPER_ADMIN,
                self::GROUP_ADMIN,
                self::GROUP_MODERATOR
            ]
        );
    }

    public function isResidentAdmin()
    {
        return in_array(self::GROUP_RESIDENT_ADMIN, $this->userGroups);
    }

    public function isResident()
    {
        return in_array(self::GROUP_RESIDENT, $this->userGroups);
    }

    /**
     * Проверка возможности редактирования/создания записей резидентов
     *
     * @return bool
     */
    public function canEditResident()
    {
        return $this->isResidentAdmin();
    }

    /**
     * Проверка возможности отправлять отчет
     *
     * @return bool
     */
    public function canReport()
    {
        return $this->isResidentAdmin()
                || ($this->isResident() && ArrayHelper::getValue($this->user, self::CAN_REPORT));
    }

    /**
     * Проверка возможности делать запрос
     *
     * @return bool
     */
    public function canRequest()
    {
        return $this->isResidentAdmin()
                || ($this->isResident() && ArrayHelper::getValue($this->user, self::CAN_REQUEST));
    }

    /**
     * Проверка возможности получать сообщения от ОЭЗ
     *
     * @return bool
     */
    public function canMessages()
    {
        return $this->isResidentAdmin()
                || ($this->isResident() && ArrayHelper::getValue($this->user, self::CAN_MSG));
    }

    /**
     * Проверка наличия ЛК у учетной записи
     *
     * @return bool
     */
    public function hasAccess()
    {
        return $this->isResidentAdmin() || $this->isResident();
    }

    /**
     * Проверка меню раздела личного кабинета
     *
     * @param array $menu
     * @return array
     */
    public function checkMenu(array $menu)
    {
        if (!$menu) {
            return $menu;
        }

        foreach ($menu as &$v) {
            if (empty($v[3]['check']) || !method_exists($this, $v[3]['check'])) {
                continue;
            }

            $v[4] = $this->{$v[3]['check']}() ? '' : 'false';

            if (!empty($v[3]['isMessages'])) {
                $v[3]['cnt'] = MessageService::getInstance($this)->calcCount()->getCountNew();
                unset($v[3]['check']);
                continue;
            }

            $v[3] = [];
        }

        return $menu;
    }

    public static function getFullName(array $data)
    {
        return implode(
            ' ',
            [
                ArrayHelper::getValue($data, 'LAST_NAME'),
                ArrayHelper::getValue($data, 'NAME'),
                ArrayHelper::getValue($data, 'SECOND_NAME')
            ]
        );
    }

    public function getLastError()
    {
        return $this->lastError;
    }

    public static function getUserFields()
    {
        return [
            'NAME',
            'LAST_NAME',
            'SECOND_NAME',
            'EMAIL',
            'PERSONAL_PHONE',
            'WORK_COMPANY',
            'WORK_PHONE',
            'WORK_FAX',
            'UF_INN',
            'UF_EMAIL',
            'UF_OWNER_FIO',
            'UF_ADDR_LEGAL',
            'UF_ADDR_POST'
        ];
    }

    public static function getAdminCompanyList(): array
    {
        $res = [];

        $tmp = \CUser::GetList(
            ($by = 'ID'),
            ($order = 'DESC'),
            [
                'GROUPS_ID' => Profile::GROUP_RESIDENT_ADMIN
            ],
            [
                'SELECT' => [],
                'FIELDS' => [
                    'ID', self::COMPANY_NAME_FIELD
                ]
            ]
        );

        if (!$tmp->AffectedRowsCount()) {
            return $res;
        }

        while ($row = $tmp->Fetch()) {
            $res[$row['ID']] = '[' . $row['ID'] . '] ' . $row[self::COMPANY_NAME_FIELD];
        }

        return $res;
    }
}
