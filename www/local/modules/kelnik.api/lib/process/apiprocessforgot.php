<?php
namespace Kelnik\Api\Process;

use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;

/**
 * Class ApiProcessLogin
 *
 * Обработка запроса восстановления пароля
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessForgot extends ApiProcessAbstract
{
    public function execute(array $request): bool
    {
        global $USER;

        if ($USER->IsAuthorized()) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
            return false;
        }

        $data['email'] = trim(ArrayHelper::getValue($request, 'email', false));

        if (!$data['email'] || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = Loc::getMessage('KELNIK_API_LOGIN_OR_PWD_EMPTY');
            return false;
        }

        $userData = \CUser::GetList(
            ($by = 'LAST_NAME'),
            ($order = 'asc'),
            ['=EMAIL' => $data['email']]
        )->GetNext();

        if (empty($userData['LOGIN'])) {
            $this->errors[] = Loc::getMessage('KELNIK_API_FORGOT_EMAIL_NOT_FOUND');
            return false;
        }

        $res = \CUser::SendPassword(
            $userData['LOGIN'],
            $userData['EMAIL'],
            SITE_ID
        );

        if ($res['TYPE'] == 'ERROR') {
            $this->errors[] = $res['MESSAGE'];
            return false;
        }

        $this->data['email'] = $data['email'];
        $this->data['success'] = $res['MESSAGE'];

        return true;
    }
}
