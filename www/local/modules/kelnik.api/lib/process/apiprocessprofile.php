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
                'filter' => [
                    '=USER_ID' => (int)$USER->GetID()
                ]
            ]);
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
}
