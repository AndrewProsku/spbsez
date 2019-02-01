<?php
namespace Kelnik\Api\Process;

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UserTable;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Userdata\Data;
use Kelnik\Userdata\Model\ContactTable;
use Kelnik\Userdata\Model\DocsTable;

/**
 * Class ApiProcessLogin
 *
 * Профиль
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessProfile extends ApiProcessAbstract
{
    public function execute(array $request): bool
    {
        global $USER;

        $action = trim(ArrayHelper::getValue($request, 'action'));

        if (!$action) {
            $this->data['profile'] = Data::getUserInfo($USER->GetID());
            $this->data['contacts'] = ContactTable::getAssoc([
                'select' => [
                    'ID', 'FIO', 'POSITION', 'PHONE', 'EMAIL'
                ],
                'filter' => [
                    '=USER_ID' => (int)$USER->GetID()
                ]
            ]);

            foreach ($this->data['contacts'] as &$v) {
                $v = array_change_key_case($v, CASE_LOWER);
            }
            unset($v);
            return true;
        }

        $methodName = 'process' . ucfirst($action);

        if (method_exists($this, $methodName)) {
            return $this->{$methodName}($request);
        }

        return true;
    }

    protected function processAddContact(array $request)
    {
        global $USER;

        try {
            $res = ContactTable::add([
                'USER_ID' => (int) $USER->GetID()
            ]);
        }catch (\Exception $e) {
            $res = false;
        }

        if (!$res->isSuccess()) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
            return false;
        }

        $this->data['id'] = $res->getId();

        return true;
    }

    protected function processDelContact(array $request)
    {
        global $USER;

        $id = (int)ArrayHelper::getValue($request, 'id', 0);

        if (!$id) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
            return false;
        }

        try {
            $res = ContactTable::getRow([
                'select' => ['ID'],
                'filter' => [
                    '=ID' => $id,
                    '=USER_ID' => (int)$USER->GetID()
                ]
            ]);
            if (empty($res['ID'])) {
                $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
                return false;
            }
            ContactTable::delete($id);
        }catch (\Exception $e) {}

        $this->data['id'] = $id;

        return true;
    }

    protected function processUpdate(array $request)
    {
        global $USER;

        $person = ArrayHelper::getValue($request, 'person');
        $profile = ArrayHelper::getValue($request, 'profile');

        if ($person) {

            $userPersons = ContactTable::getListByUser($USER->GetID());
            foreach ($person as $personId => $fields) {
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
                    $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
                    return false;
                }

                $this->data['id'][] = $personId;
            }

            return true;

        } elseif ($profile) {

            $data = [];
            $allowFields = Data::getUserFields();

            foreach ($profile as $k => $v) {
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
                $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
                return false;
            }

            $cUser = new \CUser();
            $cUser->Update($USER->GetID(), $data);

            if ($cUser->LAST_ERROR) {
                $this->errors[] = $cUser->LAST_ERROR;
                return false;
            }

            $this->data = $data;
        }

        return false;
    }

    protected function processAddDoc(array $request)
    {
        global $USER;

        $doc = Context::getCurrent()->getRequest()->getFile('doc');

        if (!is_uploaded_file($doc['tmp_name'])) {
            $this->errors[] = Loc::getMessage('KELNIK_PROFILE_DOC_FILE_UPLOAD_ERROR');
            return false;
        }

        $allowExt = DocsTable::getAllowExt();

        if (!in_array($doc['type'], array_keys($allowExt))) {
            $this->errors[] = Loc::getMessage('KELNIK_PROFILE_DOC_FILE_EXT_ERROR');
            return false;
        }

        try {
            $doc['MODULE_ID'] = 'kelnik.userdata';
            $fileId = \CFile::SaveFile($doc, $doc['MODULE_ID'], true);
        } catch (\Exception $e) {
        }

        if (!$fileId) {
            $this->errors[] = Loc::getMessage('KELNIK_PROFILE_DOC_FILE_UPLOAD_ERROR');
            return false;
        }

        try {
            $res = DocsTable::add([
                'USER_ID' => $USER->GetID(),
                'FILE_ID' => $fileId
            ]);
        } catch (\Exception $e) {
        }

        if (!$res->isSuccess()) {
            $this->errors[] = Loc::getMessage('KELNIK_PROFILE_DOC_FILE_UPLOAD_ERROR');
            return false;
        }

        $dateTime = new DateTime();
        $fileData = \CFile::GetFileArray($fileId);

        $res = [
            'id' => $res->getId(),
            'userName' => $USER->GetFirstName(),
        ];

        $res['filePath'] = $fileData['SRC'];
        $res['fileName'] = $fileData['ORIGINAL_NAME'];
        $res['fileSizeFormat'] = \CFile::FormatSize($fileData['FILE_SIZE']);
        $res['fileExt'] = strtolower(pathinfo($fileData['ORIGINAL_NAME'], PATHINFO_EXTENSION));
        $res['dateModified'] = $dateTime->format('Y-m-d');
        $res['dateModifiedHuman'] = $dateTime->format('d.m.Y');

        $this->data['docs'][] = $res;

        return true;
    }

    protected function processDelDoc(array $request)
    {
        global $USER;

        $id = (int)ArrayHelper::getValue($request, 'id', 0);

        if (!$id) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
            return false;
        }

        try {
            $res = DocsTable::getRow([
                'select' => ['ID'],
                'filter' => [
                    '=ID' => $id,
                    '=USER_ID' => (int)$USER->GetID()
                ]
            ]);
            if (empty($res['ID'])) {
                $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
                return false;
            }
            DocsTable::delete($id);
        }catch (\Exception $e) {}

        $this->data['id'] = $id;

        return true;
    }
}
