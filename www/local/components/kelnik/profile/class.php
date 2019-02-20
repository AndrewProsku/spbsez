<?php

namespace Kelnik\User\Components;

use Bex\Bbc;
use Bitrix\Main\Localization\Loc;
use Kelnik\Userdata\Profile\ProfileModel;
use Kelnik\Userdata\Model\DocsTable;
use Kelnik\Userdata\Profile\ProfileSectionAdmins;
use Kelnik\Userdata\Profile\ProfileSectionDocs;

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
     * @var \Kelnik\Userdata\Profile\ProfileModel
     */
    protected $profile;

    protected function executeMain()
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            return false;
        }

        try {
            $this->profile = ProfileModel::getInstance($USER->GetID());
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
