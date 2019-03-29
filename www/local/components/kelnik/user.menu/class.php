<?php

namespace Kelnik\Usermenu\Components;

use Bex\Bbc;
use Kelnik\Messages\MessageService;
use Kelnik\UserData\Profile\Profile;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class UserMenuList extends Bbc\Basis
{
    protected $needModules = ['kelnik.userdata', 'kelnik.messages'];
    protected $checkParams = [];
    protected $cacheTemplate = false;

    protected function executeMain()
    {
        global $USER;

        $this->abortResultCache();

        $this->arResult['MESSAGES'] = 0;
        $this->arResult['IS_AUTHORIZED'] = !empty($USER) && $USER instanceof \CUser
                                            ? $USER->IsAuthorized()
                                            : false;

        if ($this->arResult['IS_AUTHORIZED']) {
            $profile = Profile::getInstance((int)$USER->GetID());

            if ($profile->canMessages()) {
                $this->arResult['MESSAGES'] = MessageService::getInstance($profile)->calcCount()->getCountNew();
            }
        }
    }
}
