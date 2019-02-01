<?php


namespace Kelnik\Userdata\Profile;


use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;

/**
 * Класс обработки учетных записей администраторов ОЭЗ.
 *
 * Т.к. изначально одобрили прототип с созданием учетных записей администратор
 * без заполнения обязательных полей, то такие записи не сохраняем в БД,
 * а храним на время сессии. При заполнении E-mail такие записи переводим для хранения в БД.
 *
 * Class ProfileSectionAdmins
 * @package Kelnik\Userdata\Profile
 */
class ProfileSectionAdmins extends ProfileSectionAbstract
{
    protected $allowFields = [
        'LOGIN',
        'EMAIL',
        'NAME',
        'SECOND_NAME',
        'LAST_NAME',
        'PERSONAL_PHONE',
        ProfileModel::CAN_MSG,
        ProfileModel::CAN_REPORT,
        ProfileModel::CAN_REQUEST
    ];

    public function add(array $data)
    {
        if (!$this->checkPermissions()) {
            return false;
        }

        if (!$data) {
            $data = [
                'ID' => self::getFakeId(),
                ProfileModel::OWNER_FIELD => $this->profile->getUserId(),
                'STATUS_NAME' => Loc::getMessage('KELNIK_PROFILE_STATUS_ADMIN'),
                'NAME' => Loc::getMessage('KELNIK_PROFILE_STATUS_DEFAULT_NAME')
            ];

            if (!self::addFakeRow($data)) {
                return false;
            }
        }

        $dbData = $this->prepareUserData($data);

        if (!$dbData) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_EMPTY_ROW');
            return false;
        }

        $el = new \CUser();

        $dbData['ACTIVE'] = 'Y';
        $dbData['GROUP_ID'] = [ProfileModel::GROUP_RESIDENT_ADMIN];
        $dbData[ProfileModel::OWNER_FIELD] = $this->profile->getUserId();

        $res = $el->Add($dbData);

        if (!$res) {
            $this->lastError = $el->LAST_ERROR;

            return false;
        }

        \CUser::SendUserInfo($res, SITE_ID, Loc::getMessage('KELNIK_PROFILE_ADMIN_ADD_MSG'), true);

        return $res;
    }

    public function update($id, array $data)
    {
        if (!$id) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_ROW_NOT_EXISTS');

            return false;
        }

        if (!$this->checkPermissions()) {
            return false;
        }

        if ($id < 0) {
            return $this->updateFakeRow($id, $data);
        }

        $row = \CUser::GetByID($id)->Fetch();

        if (!$row || (int)$row[ProfileModel::OWNER_FIELD] !== $this->profile->getUserId()) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_ROW_NOT_EXISTS');

            return false;
        }

        $dbData = $this->prepareUserData($data);

        if (!$dbData) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_EMPTY_ROW');

            return false;
        }

        $el = new \CUser();
        $res = $el->Update($id, $dbData);

        if (!$res) {
            $this->lastError = $el->LAST_ERROR;

            return false;
        }

        return true;
    }

    protected function getFakeId()
    {
        return 0 - time();
    }

    protected function addFakeRow(array $data): bool
    {
        $_SESSION['accounts'][$this->profile->getUserId()][$data['ID']] = base64_encode(serialize($data));

        return true;
    }

    protected function updateFakeRow($id, array $data): bool
    {
        if (!$this->profile->canEditResidentAdmin()) {
            return false;
        }

        $row = $this->getFakeRow($id);

        if (!$row || $row[ProfileModel::OWNER_FIELD] !== $this->profile->getUserId()) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_ROW_NOT_EXISTS');

            return false;
        }

        $data['ID'] = $id;

        foreach ($data as $k => $v) {
            if (!in_array($k, $this->allowFields)) {
                unset($data[$k]);
            }
        }

        $_SESSION['accounts'][$this->profile->getUserId()][$id] = base64_encode(serialize($data));

        return true;
    }

    protected function getFakeRow($id): array
    {
        $res = ArrayHelper::getValue($_SESSION, 'accounts.' . $this->profile->getUserId() . '.' . $id);

        return $res
                ? unserialize(base64_decode($res))
                : [];
    }

    protected function getFakeRows(): array
    {
        $tmp = ArrayHelper::getValue($_SESSION, 'accounts.' . $this->profile->getUserId());

        if (!$tmp) {
            return [];
        }

        $rows = [];
        foreach ($tmp as $v) {
            $v = unserialize(base64_decode($v));
            $v['FULL_NAME'] = ProfileModel::getUserFullName($v);
            $rows[$v['ID']] = $v;
        }


        return $rows;
    }

    protected function deleteFakeRow($id): bool
    {
        unset($_SESSION['accounts'][$this->profile->getUserId()][$id]);

        return true;
    }

    public function getList(): array
    {
        return array_merge(
                $this->profile->getEditableUsers(),
                $this->getFakeRows()
        );
    }

    protected function checkPermissions()
    {
        $this->lastError = '';

        if (!$this->profile->canEditResidentAdmin()) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_HAS_NO_ACCESS');

            return false;
        }

        return true;
    }

    protected function prepareUserData(array $data)
    {
        $dbData = [];

        if (!$data) {
            return $dbData;
        }

        if (isset($data['FULL_NAME'])) {
            list($dbData['LAST_NAME'], $dbData['NAME'], $dbData['SECOND_NAME']) = explode(' ', $data['FULL_NAME']);
        }

        foreach ($data as $k => $v) {
            if (!in_array($k, $this->allowFields)) {
                continue;
            }

            $dbData[$k] = is_numeric($v) ? (int)$v : trim($v);

            if ($k === 'EMAIL') {
                $dbData['LOGIN'] = $v;
            }
        }

        return $dbData;
    }
}
