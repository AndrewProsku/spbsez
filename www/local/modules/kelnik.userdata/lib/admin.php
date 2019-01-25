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
    public const GROUP_RESIDENT_ADMIN = 9;
    public const GROUP_RESIDENT = 10;

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
                'GROUPS_ID' => $groups
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
        if ($this->hasEditAdmin()) {
            return [
                self::GROUP_MODERATOR,
                self::GROUP_RESIDENT_ADMIN,
                self::GROUP_RESIDENT
            ];
        }

        if ($this->hasEditModerator()) {
            return [
                self::GROUP_RESIDENT_ADMIN,
                self::GROUP_RESIDENT
            ];
        }

        if ($this->hasEditResidentAdmin()) {
            return [self::GROUP_RESIDENT];
        }

        return [];
    }

    public function hasEditAdmin()
    {
        return in_array(self::GROUP_SUPER_ADMIN, $this->userGroups);
    }

    public function hasEditModerator()
    {
        return array_intersect(
            $this->userGroups,
            [
                self::GROUP_SUPER_ADMIN,
                self::GROUP_ADMIN
            ]
        );
    }

    public function hasEditResidentAdmin()
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
}
