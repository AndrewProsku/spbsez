<?php
namespace Kelnik\Api\Process;

use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;

/**
 * Class ApiProcessLogin
 *
 * Обработка запроса авторизации
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessLogin extends ApiProcessAbstract
{
    public function execute(array $request): bool
    {
        global $USER, $APPLICATION;

        $fields = [
            'email',
            'password'
        ];

        $data = [];

        foreach ($fields as $field) {
            $data[$field] = trim(ArrayHelper::getValue($request, $field, false));

            if (!$data[$field]) {
                $this->errors[] = Loc::getMessage('KELNIK_API_LOGIN_OR_PWD_EMPTY');
                return false;
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = Loc::getMessage('KELNIK_API_LOGIN_OR_PWD_EMPTY');
            return false;
        }

        $userData = \CUser::GetList(
            ($by = 'LAST_NAME'),
            ($order = 'asc'),
            ['=EMAIL' => $data['email']]
        )->GetNext();

        if (empty($userData['LOGIN'])) {
            $this->errors[] = Loc::getMessage('KELNIK_API_LOGIN_INCORRECT');
            return false;
        }

        if (!empty($USER) && $USER instanceof \CUser) {
            if ($USER->IsJustAuthorized()) {
                try {
                    $USER->Logout();
                } catch (\Exception $e) {}
            }
        }

        try {
            $this->data['auth'] = $USER->Login($userData['LOGIN'], $data['password'], 'N');
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        if (true === $this->data['auth']) {
            $APPLICATION->SetAuthResult($this->data['auth']);
            $USER->CheckAuthActions();
            $this->data['backUrl'] = '/cabinet/';
        } else {
            $this->data['error'] = $this->errors[] = strip_tags(ArrayHelper::getValue($this->data['auth'], 'MESSAGE'));
            $this->data['auth'] = false;
        }

        return true;
    }
}
