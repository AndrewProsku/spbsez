<?php
namespace Kelnik\Api\Process;

use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Userdata\Data;
use Kelnik\Userdata\Model\ContactTable;

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
}
