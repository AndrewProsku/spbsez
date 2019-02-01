<?php

namespace Kelnik\Usermenu\Components;

use Bex\Bbc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class UserMenuList extends Bbc\Basis
{
    protected $needModules = [];
    protected $checkParams = [];
    protected $cacheTemplate = false;

    protected function executeMain()
    {
        global $USER;

        $this->abortResultCache();

        $this->arResult['IS_AUTHORIZED'] = !empty($USER) && $USER instanceof \CUser
                                            ? $USER->IsAuthorized()
                                            : false;
    }
}
