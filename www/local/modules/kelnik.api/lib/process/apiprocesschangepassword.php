<?php
namespace Kelnik\Api\Process;

use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;

/**
 * Class ApiProcessChangePassword
 *
 * Обработка запроса смены пароля
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessChangePassword extends ApiProcessAbstract
{
    public function execute(array $request): bool
    {
        global $USER;

        if ($USER->IsAuthorized()) {
            $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
            return false;
        }

        $fields = [
            'USER_CHECKWORD',
            'USER_LOGIN',
            'password',
            'new-password'
        ];

        $params = [];

        foreach ($fields as $field) {
            $params[$field] = trim(ArrayHelper::getValue($request, $field, false));
            if (empty($params[$field])) {
                $this->errors[] = Loc::getMessage('KELNIK_API_REQUEST_ERROR');
                return false;
            }
        }

        $user = \CUser::GetByLogin($params['USER_LOGIN'])->fetch();

        if (empty($user['ID'])) {
            $this->errors[] = Loc::getMessage('KELNIK_API_USER_NOT_FOUND');
            false;
        }

        $policity = \CUser::GetGroupPolicy($user['ID']);

        if ($params['password'] !== $params['new-password']) {
            $this->errors[] = Loc::getMessage('KELNIK_API_PASSWORD_CONFIRM_ERROR');
            return false;
        }

        if (!empty($policity['PASSWORD_LENGTH']) && mb_strlen($params['password']) < (int)$policity['PASSWORD_LENGTH']) {
            $this->errors[] = Loc::getMessage('KELNIK_API_PASSWORD_LENGTH_ERROR', ['#NUM#' => (int)$policity['PASSWORD_LENGTH']]);
            return false;
        }

        $res = new \CUser();

        $res = $res->ChangePassword(
            $params['USER_LOGIN'],
            $params['USER_CHECKWORD'],
            $params['password'],
            $params['new-password']
        );

        if ($res['TYPE'] == 'ERROR') {
            $this->errors[] = $res['MESSAGE'];
            return false;
        }

        $this->data['success'] = $res['MESSAGE'];

        return true;
    }
}
