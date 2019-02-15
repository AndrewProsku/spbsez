<?php
namespace Kelnik\Api\Process;


use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Kelnik\Api\Api;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Requests\Model\SiteMsgTable;

/**
 * Class ApiProcessMessage
 *
 * Профиль
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessMessage extends ApiProcessAbstract
{
    public function execute(array $request): bool
    {
        $contextRequest = Context::getCurrent()->getRequest();
        $userHash = Api::getUserHash();

        if (!$contextRequest->isPost() || !SiteMsgTable::userCanAddRow($userHash)) {
            $this->errors[] = Loc::getMessage('KELNIK_API_REQUEST_ERROR');

            return false;
        }

        $fields = [
            'NAME' => 'fio',
            'PHONE' => 'phone',
            'EMAIL' => 'email',
            'BODY' => 'text'
        ];

        $data = [];

        foreach ($fields as $k => $v) {
            $data[$k] = htmlentities(trim(ArrayHelper::getValue($request, $v)), ENT_QUOTES, 'UTF-8');
            if (empty($data[$k])) {
                $this->errors[] = Loc::getMessage('KELNIK_API_MESSAGE_ERROR_' . $k);

                return false;
            }
        }

        try {
            $data['USER_HASH'] = $userHash;
            SiteMsgTable::add($data);
        } catch (\Exception $e) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');

            return false;
        }

        return true;
    }
}
