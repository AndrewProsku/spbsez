<?php
namespace Kelnik\Api\Process;

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\UserData\Profile\Profile;
use Kelnik\UserData\Profile\ProfileSectionAdmins;
use Kelnik\UserData\Profile\ProfileSectionContacts;
use Kelnik\UserData\Profile\ProfileSectionDocs;

/**
 * Class ApiProcessProfile
 *
 * Профиль
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessProfile extends ApiProcessAbstract
{
    /**
     * @var Profile
     */
    protected $profile;

    /**
     * @var ProfileSectionContacts
     */
    protected $contacts;

    /**
     * @var ProfileSectionDocs
     */
    protected $docs;

    /**
     * @var ProfileSectionAdmins
     */
    protected $admins;

    public function execute(array $request): bool
    {
        global $USER;

        $action = trim(ArrayHelper::getValue($request, 'action'));

        try {
            $this->profile = Profile::getInstance((int)$USER->GetID());
            $this->initSection($action);
        } catch (\Exception $e) {
            return false;
        }

        if (!$action) {
            $this->data['profile'] = $this->profile->getInfo();
            $this->data['contacts'] = array_values($this->contacts->getList());

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

    protected function initSection($action)
    {
        $sections = [
            'contact' => 'contacts',
            'doc' => 'docs',
            'admin' => 'admins'
        ];

        $action = strtolower($action);

        foreach ($sections as $section => $model) {

            if (!$action || false === strpos($action, $section)) {
                continue;
            }

            $nameSpace = '\Kelnik\UserData\Profile\ProfileSection' . ucfirst($model);

            if (!class_exists($nameSpace)) {
                return;
            }

            $this->{$model} = new $nameSpace($this->profile);

            return;
        }

        $this->contacts = new ProfileSectionContacts($this->profile);
    }

    protected function processAddContact(array $request)
    {
        if (!$res = $this->contacts->add([])) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');

            return false;
        }

        $this->data['id'] = $res;

        return true;
    }

    protected function processDelContact(array $request)
    {
        $id = (int)ArrayHelper::getValue($request, 'id', 0);

        if (false === $this->contacts->delete($id)) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');

            return false;
        }

        $this->data['id'] = $id;

        return true;
    }

    protected function processUpdate(array $request)
    {
        $person  = ArrayHelper::getValue($request, 'person');
        $profile = ArrayHelper::getValue($request, 'profile');

        if ($person && $this->profile->isResidentAdmin()) {

            $res = $this->contacts->updateRows($person);

            if (false === $res) {
                $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');

                return false;
            }

            $this->data = $res;

            return true;

        } elseif ($profile) {

            $res = $this->profile->update($profile);

            if (!$res) {
                $this->errors[] = false === $res
                                    ? Loc::getMessage('KELNIK_API_INTERNAL_ERROR')
                                    : $res;
                return false;
            }

            $this->data = $res;
        }

        return false;
    }

    protected function processAddDoc(array $request)
    {
        /* @var array $doc */
        $doc = Context::getCurrent()->getRequest()->getFile('doc');

        if (!is_uploaded_file($doc['tmp_name'])) {
            $this->errors[] = Loc::getMessage('KELNIK_API_DOC_FILE_UPLOAD_ERROR');

            return false;
        }

        $res = $this->docs->add($doc);

        if (!$res || !is_array($res)) {
            $this->errors[] = $this->docs->getLastError();

            return false;
        }

        $dateTime = new DateTime();
        $fileData = \CFile::GetFileArray($res['fileId']);

        $res = [
            'id' => $res['rowId'],
            'userName' => implode(
                ' ',
                [
                    $this->profile->getField('LAST_NAME'),
                    $this->profile->getField('NAME'),
                    $this->profile->getField('SECOND_NAME')
                ]
            )
        ];

        $res['filePath'] = $fileData['SRC'];
        $res['fileName'] = $fileData['ORIGINAL_NAME'];
        $res['fileSizeFormat'] = \CFile::FormatSize($fileData['FILE_SIZE']);
        $res['fileExt'] = strtolower(pathinfo($fileData['ORIGINAL_NAME'], PATHINFO_EXTENSION));
        $res['dateModified'] = $dateTime->format('Y-m-d');
        $res['dateModifiedHuman'] = $dateTime->format('d.m.Y');
        $res['canDelete'] = true;

        $this->data['docs'][] = $res;

        return true;
    }

    protected function processDelDoc(array $request)
    {
        $id = (int)ArrayHelper::getValue($request, 'id', 0);

        $res = $this->docs->delete($id);

        if (!$res) {
            $this->errors[] = $this->docs->getLastError();

            return false;
        }

        $this->data['id'] = $id;

        return true;
    }

    protected function processAddAdmin(array $request)
    {
        $res = $this->admins->add([]);

        if (!$res) {
            $this->errors[] = $this->admins->getLastError();

            return false;
        }

        $this->data = $this->admins->getById($res);
        unset($this->data[Profile::OWNER_FIELD]);

        return true;
    }

    protected function processUpdateAdmin(array $request)
    {
        $res = $this->admins->update(
            (int) ArrayHelper::getValue($request, 'id'),
            $request
        );

        if (!$res) {
            $this->errors[] = $this->admins->getLastError();

            return false;
        }

        if (is_numeric($res)) {
            $this->data = $this->admins->getById($res);
            unset($this->data[Profile::OWNER_FIELD]);
        }

        return true;
    }

    protected function processDelAdmin(array $request)
    {
        $res = $this->admins->delete((int) ArrayHelper::getValue($request, 'id'));

        if (!$res) {
            $this->errors[] = $this->admins->getLastError();

            return false;
        }

        return true;
    }
}
