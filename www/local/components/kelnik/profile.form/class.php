<?php

namespace Kelnik\User\Components;

use Bex\Bbc;
use Kelnik\Userdata\Model\ContactTable;
use Kelnik\Userdata\Data;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class ProfileForm extends Bbc\Basis
{
    protected $needModules = ['kelnik.userdata'];
    protected $checkParams = [
        'SECTION' => ['type' => 'string']
    ];
    protected $cacheTemplate = false;

    protected function executeMain()
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            return;
        }

        if ($this->arParams['SECTION'] == 'docs') {
            // TODO get docs
        }
    }
}
