<?php

namespace Kelnik\Messages\Components;

use Bex\Bbc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class MessagesList extends Bbc\Basis
{
    protected $needModules = ['kelnik.userdata', 'kelnik.messages'];
    protected $checkParams = [];

    protected function executeMain()
    {
        // TODO: your own code here
    }
}
