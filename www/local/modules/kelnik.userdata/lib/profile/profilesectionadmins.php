<?php


namespace Kelnik\Userdata\Profile;


use Bitrix\Main\Localization\Loc;

class ProfileSectionAdmins extends ProfileSectionAbstract
{
    public function add(array $data)
    {
        $this->lastError = '';

        if (!$this->profile->canEditResidentAdmin()) {
            $this->lastError = Loc::getMessage('KELNIK_PROFILE_HAS_NO_ACCESS');

            return false;
        }

        $data = [
            'ID' => self::getFakeId(),
            ProfileModel::OWNER_FIELD => $this->profile->getUserId()
        ];

        if (!self::saveFakeRow($data)) {
            return false;
        }

        return $data['ID'];
    }

    protected function getFakeId()
    {
        return 0 - time();
    }

    protected function saveFakeRow(array $data)
    {

    }

    protected function loadFakeRow($id)
    {

    }

    public function getList()
    {
        return $this->profile->getEditableUsers();
    }
}
