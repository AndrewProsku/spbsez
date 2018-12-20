<?php

namespace Kelnik\User\Components;

use Bex\Bbc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class AuthForm extends Bbc\Basis
{
    protected $needModules = [];
    protected $checkParams = [];

    protected function executeMain()
    {
        global $USER;
    }
}
