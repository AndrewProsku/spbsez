<?php

namespace Kelnik\Siteinfo\Components;

use Bex\Bbc;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!Loader::includeModule('bex.bbc')) {
    return false;
}

class SiteInfoList extends Bbc\Basis
{
    protected $needModules = ['kelnik.multisites'];
    protected $checkParams = [];

    protected function executeMain()
    {
        $this->arResult = \Kelnik\Multisites\Settings\CurrentSite::getInstance()->getData();
        $this->arResult['PHONE_CODE'] = str_replace(' ', '', $this->arResult['PHONE']);

        $langId = strtoupper(Context::getCurrent()->getLanguage());

        if (!empty($this->arResult['PRESS_CONTACT_' . $langId])) {
            $this->arResult['PRESS_CONTACT'] = $this->arResult['PRESS_CONTACT_' . $langId];
        }
    }
}
