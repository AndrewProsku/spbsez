<?php

namespace Kelnik\Api\Process;


use Kelnik\Helpers\ArrayHelper;

/**
 * Class ApiProcessReport
 *
 * Профиль
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessReport extends ApiProcessAbstract
{
    public function execute(array $request): bool
    {
        $id = ArrayHelper::getValue($request, 'id', 0);

        return true;
    }
}
