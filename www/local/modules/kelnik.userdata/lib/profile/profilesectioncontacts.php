<?php


namespace Kelnik\Userdata\Profile;


use Kelnik\Userdata\Model\ContactTable;

class ProfileSectionContacts extends ProfileSectionAbstract
{
    /**
     * @param array $data
     * @return bool|int
     */
    public function add(array $data)
    {
        try {
            $data['USER_ID'] = $this->profile->getId();
            $res = ContactTable::add($data);
        } catch (\Exception $e) {
            return false;
        }

        return $res->isSuccess() ? (int) $res->getId() : false;
    }

    public function delete($id)
    {
        if (!$id) {
            return false;
        }

        try {
            $res = ContactTable::getRow([
                'select' => ['ID'],
                'filter' => [
                    '=ID' => $id,
                    '=USER_ID' => $this->profile->getId()
                ]
            ]);
            if (empty($res['ID'])) {
                return false;
            }
            ContactTable::delete($id);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Обновление записи
     *
     * @param array $data
     * @return array|bool
     */
    public function updateRows(array $data)
    {
        $userPersons = $this->getList();

        $res = [];

        foreach ($data as $personId => $fields) {
            if (!isset($userPersons[$personId])) {
                continue;
            }
            $data = [];
            foreach ($fields as $k => $v) {
                if (!in_array($k, ['FIO', 'PHONE', 'EMAIL', 'POSITION'])) {
                    continue;
                }
                $data[$k] = $v;
            }

            try {
                ContactTable::update($personId, $data);
            } catch (\Exception $e) {
                return false;
            }

            $res['id'][] = $personId;
        }

        return $res;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        return ContactTable::getListByUser($this->profile->getId());
    }
}
