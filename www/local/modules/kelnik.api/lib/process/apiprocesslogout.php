<?php
namespace Kelnik\Api\Process;

use Bitrix\Main\Localization\Loc;
use Kelnik\Requests\Customer\CustomerTable;

/**
 * Class ApiProcessLogout
 *
 * Завершение сессии
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessLogout extends ApiProcessLogin
{
    public function execute(array $request): bool
    {
        global $USER;

        $USER->Logout();

        return true;
    }
}
