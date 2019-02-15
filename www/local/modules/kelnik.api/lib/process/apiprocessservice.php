<?php
namespace Kelnik\Api\Process;


use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Kelnik\Api\Api;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Requests\Model\ServiceTable;
use Kelnik\Requests\Model\SiteMsgTable;

/**
 * Class ApiProcessService
 *
 * Профиль
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessService extends ApiProcessAbstract
{
    public function execute(array $request): bool
    {
        $contextRequest = Context::getCurrent()->getRequest();
        $userHash = Api::getUserHash();

        if (!$contextRequest->isPost() || !ServiceTable::userCanAddRow($userHash)) {
            $this->errors[] = Loc::getMessage('KELNIK_API_REQUEST_ERROR');

            return false;
        }

        $fields = [
            'NAME' => 'fio',
            'PHONE' => 'phone',
            'EMAIL' => 'email',
            'COMPANY' => 'company',
            'POSITION' => 'position',
            'BODY' => 'text',
            'TYPE_ID' => 'type'
        ];

        $data = [];

        foreach ($fields as $k => $v) {
            $data[$k] = $k == 'TYPE_ID'
                        ? (int) ArrayHelper::getValue($request, $v)
                        : htmlentities(trim(ArrayHelper::getValue($request, $v)), ENT_QUOTES, 'UTF-8');

            if (empty($data[$k])) {
                $this->errors[] = Loc::getMessage('KELNIK_API_SERVICE_ERROR_' . $k);

                return false;
            }
        }

        try {
            $data['USER_HASH'] = $userHash;
            ServiceTable::add($data);
        } catch (\Exception $e) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');

            return false;
        }

        return true;
    }
}
