<?php

namespace Kelnik\User\Components;

use Bex\Bbc;
use Bitrix\Main\Localization\Loc;
use Kelnik\UserData\Profile\Profile;
use Kelnik\UserData\Model\DocsTable;
use Kelnik\UserData\Profile\ProfileSectionAdmins;
use Kelnik\UserData\Profile\ProfileSectionDocs;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class ProfileForm extends Bbc\Basis
{
    protected $needModules = ['kelnik.userdata'];
    protected $checkParams = [
        'SECTION' => ['type' => 'string']
    ];
    protected $cacheTemplate = false;

    /**
     * @var \Kelnik\UserData\Profile\Profile
     */
    protected $profile;

    protected function executeMain()
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            return false;
        }

        try {
            $this->profile = Profile::getInstance((int)$USER->GetID());
        } catch (\Exception $e) {
            return false;
        }

        if (!$this->profile->hasAccess()) {
            LocalRedirect('/');
        }

        $this->arResult['IS_ADMIN'] = $this->profile->isResidentAdmin();

        $method = 'process' . ucfirst($this->arParams['SECTION']);

        if (!method_exists($this, $method)) {
            return false;
        }

        return $this->{$method}();
    }

    protected function processDocs()
    {
        $allowExt = DocsTable::getAllowExt();
        foreach ($allowExt as $k => $v) {
            $this->arResult['ACCEPT'][] = $k;
            $this->arResult['ACCEPT'][] = '.' . $v;
        }

        $this->arResult['ACCEPT'] = implode(',', $this->arResult['ACCEPT']);
        $this->arResult['ERROR'] = false;

        $this->arResult['DOCS'] = (new ProfileSectionDocs($this->profile))->getList();

        return true;
    }

    protected function processAdmins()
    {
        if (!$this->profile->canEditResident()) {
            LocalRedirect('/cabinet/');
        }

        $this->arResult['USERS'] = (new ProfileSectionAdmins($this->profile))->getList();
    }
}
