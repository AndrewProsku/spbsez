<?php
namespace Kelnik\Api\Process;


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
        print_r($request);
        die;

        return true;
    }
}
