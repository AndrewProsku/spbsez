<?php


namespace Kelnik\Userdata\Profile;


use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;

/**
 * Класс обработки учетных записей администраторов ОЭЗ.
 *
 * Т.к. изначально одобрили прототип с созданием учетных записей администратора
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
        ProfileModel::CAN_REQUEST,
    ];

    /**
     * Создание учетной записи
     *
     * @param array $data
     * @return bool|int
     */
    public function add(array $data)
    {
        $this->lastError = '';

        if (!$this->checkPermissions()) {
            return false;
        }

        if (!$data) {
            $data = [
                'ID' => self::getFakeId(),
                ProfileModel::OWNER_FIELD => $this->profile->getId(),
                'STATUS_NAME' => Loc::getMessage('KELNIK_PROFILE_STATUS_ADMIN'),
                'NAME' => Loc::getMessage('KELNIK_PROFILE_STATUS_DEFAULT_NAME'),
                'FULL_NAME' => Loc::getMessage('KELNIK_PROFILE_STATUS_DEFAULT_NAME')
            ];

            self::addFakeRow($data);

            return $data['ID'];
        }

        $dbData = $this->prepareToDb($data);

        if (!$dbData) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_EMPTY_ROW');

            return false;
        }

        $el = new \CUser();

        $dbData['ACTIVE'] = 'Y';
        $dbData['GROUP_ID'] = [ProfileModel::GROUP_RESIDENT];
        $dbData[ProfileModel::OWNER_FIELD] = $this->profile->getId();
        $dbData['PASSWORD'] = randString(8);

        $res = $el->Add($dbData);

        if (!$res) {
            $this->lastError = $el->LAST_ERROR;

            return false;
        }

        \CUser::SendUserInfo($res, SITE_ID, Loc::getMessage('KELNIK_PROFILE_ADMIN_ADD_MSG'), true);

        return $res;
    }

    /**
     * Обновление учетной записи
     *
     * @param $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $this->lastError = '';

        if (!$this->checkId($id) || !$this->checkPermissions()) {
            return false;
        }

        if ($id < 0) {
            return $this->updateFakeRow($id, $data);
        }

        if (!$this->checkOwner($id)) {
            return false;
        }

        $dbData = $this->prepareToDb($data);

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

    /**
     * Удаление учетной записи
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $this->lastError = '';

        if (!$this->checkId($id) || !$this->checkPermissions()) {
            return false;
        }

        if ($id < 0) {
            return $this->deleteFakeRow($id);
        }

        if (!$this->checkOwner($id)) {
            return false;
        }

        if (!\CUser::Delete($id)) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_DELETE_ERROR');

            return false;
        }

        return true;
    }

    /**
     * Поиск учетной записи
     *
     * @param $id
     * @return array
     */
    public function getById($id)
    {
        if (!$id || !$this->checkPermissions()) {
            return [];
        }

        if ($id < 0) {
            return $this->getFakeRow($id);
        }

        $res = \CUser::GetByID($id)->Fetch();

        if ($res) {
            $allowFields = $this->allowFields;
            $allowFields[] = 'ID';

            foreach ($res as $k => $v) {
                if (in_array($k, $allowFields)) {
                    continue;
                }
                unset($res[$k]);
            }
            $res['FULL_NAME'] = ProfileModel::getFullName($res);
            $res['STATUS_NAME'] = Loc::getMessage('KELNIK_PROFILE_STATUS_ADMIN');
        }

        return $res;
    }

    protected function getFakeId()
    {
        return 0 - time();
    }

    /**
     * Добавление временной учетной записи
     *
     * @param array $data
     */
    protected function addFakeRow(array $data)
    {
        $_SESSION['accounts'][$this->profile->getId()][$data['ID']] = base64_encode(serialize($data));
    }

    /**
     * Обновление данных временной учетной записи
     *
     * @param $id
     * @param array $data
     * @return bool|int
     */
    protected function updateFakeRow($id, array $data)
    {
        $row = $this->getFakeRow($id);

        if (!$row) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_ROW_NOT_EXISTS');

            return false;
        }

        if (!$this->checkOwner($id, $row)) {
            return false;
        }

        $data = self::parseFullName($data);

        foreach ($data as $k => $v) {
            if (!in_array($k, $this->allowFields)) {
                unset($data[$k]);
                continue;
            }
            $data[$k] = trim($v);
        }
        $data['ID'] = $id;

        if (isset($data['EMAIL']) && !filter_var($data['EMAIL'], FILTER_VALIDATE_EMAIL)) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_EMAIL_INCORRECT');

            return false;
        }

        $row = array_merge($row, $data);
        $row['FULL_NAME'] = ProfileModel::getFullName($row);

        if (!empty($row['EMAIL'])) {
            return $this->convertFakeToReal($row);
        }

        $_SESSION['accounts'][$this->profile->getId()][$id] = base64_encode(serialize($row));

        return true;
    }

    /**
     * Поиск временной учетной записи
     *
     * @param $id
     * @return array
     */
    protected function getFakeRow($id): array
    {
        $res = ArrayHelper::getValue($_SESSION, 'accounts.' . $this->profile->getId() . '.' . $id);

        return $res
                ? unserialize(base64_decode($res))
                : [];
    }

    /**
     * Список временных учетных записей
     *
     * @return array
     */
    protected function getFakeRows(): array
    {
        $tmp = ArrayHelper::getValue($_SESSION, 'accounts.' . $this->profile->getId());

        if (!$tmp) {
            return [];
        }

        $rows = [];
        foreach ($tmp as $v) {
            $v = unserialize(base64_decode($v));
            $v['FULL_NAME'] = ProfileModel::getFullName($v);
            $rows[$v['ID']] = $v;
        }


        return $rows;
    }

    /**
     * Удаление временной учетной записи
     *
     * @param $id
     * @return bool
     */
    protected function deleteFakeRow($id): bool
    {
        if (!isset($_SESSION['accounts'][$this->profile->getId()][$id])) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_ROW_NOT_EXISTS');

            return false;
        }

        unset($_SESSION['accounts'][$this->profile->getId()][$id]);

        return true;
    }

    /**
     * Объедененный список учетных записей
     *
     * @return array
     */
    public function getList(): array
    {
        $res = array_merge(
                $this->profile->getEditableUsers(),
                $this->getFakeRows()
        );

        foreach ($res as &$v) {
            $v['STATUS_NAME'] = Loc::getMessage('KELNIK_PROFILE_STATUS_ADMIN');
        }
        unset($v);

        return $res;
    }

    /**
     * Проверка прав доступа к разделу
     *
     * @return bool
     */
    protected function checkPermissions()
    {
        $this->lastError = '';

        if (!$this->profile->canEditResident()) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_HAS_NO_ACCESS');

            return false;
        }

        return true;
    }

    /**
     * Проверка
     * @param $id
     * @return bool
     */
    protected function checkId($id)
    {
        if (!$id) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_ROW_NOT_EXISTS');

            return false;
        }

        return true;
    }

    /**
     * Проверка создателя учетной записи
     *
     * @param $id
     * @param bool $row
     * @return bool
     */
    protected function checkOwner($id, $row = false)
    {
        if (false === $row) {
            $row = \CUser::GetByID($id)->Fetch();
        }

        if (!$row || (int)$row[ProfileModel::OWNER_FIELD] !== $this->profile->getId()) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_ADMIN_ROW_NOT_EXISTS');

            return false;
        }

        return true;
    }

    /**
     * Преобразование переданных данных для сохранения в БД
     *
     * @param array $data
     * @return array
     */
    protected function prepareToDb(array $data)
    {
        $dbData = [];

        if (!$data) {
            return $dbData;
        }

        $data = self::parseFullName($data);

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

    protected function convertFakeToReal(array $data)
    {
        $this->lastError = '';

        if (empty($data['EMAIL'])) {
            $this->lastError = Loc::getMessage('');

            return false;
        }

        $oldId = ArrayHelper::getValue($data, 'ID');
        $data = $this->prepareToDb($data);
        unset($data['ID']);

        $res = $this->add($data);

        if ($res) {
            $this->deleteFakeRow($oldId);

            return $res;
        }

        return false;
    }

    protected static function parseFullName(array $data)
    {
        if (isset($data['FULL_NAME'])) {
            list($data['LAST_NAME'], $data['NAME'], $data['SECOND_NAME']) = explode(' ', $data['FULL_NAME']);
            unset($data['FULL_NAME']);
        }

        return $data;
    }
}
