<?php
namespace Kelnik\Api\Process;

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
