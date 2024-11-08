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

    protected function executeProlog()
    {
        global $USER;

        try {
            $this->profile = Profile::getInstance((int)$USER->GetID());
        } catch (\Exception $e) {
        }

        if (!$USER->IsAuthorized()
            || !$this->profile
            || !$this->profile->hasAccess()
        ) {
            LocalRedirect(LANG_DIR);
        }

        $this->addCacheAdditionalId($USER->GetID());
    }

    protected function executeMain()
    {
        self::registerCacheTag('kelnik:profile_' . $this->profile->getId());
        self::registerCacheTag('kelnik:profileCompany_' . $this->profile->getCompanyId());

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
            LocalRedirect(LANG_DIR . 'cabinet/');
        }

        $this->arResult['USERS'] = (new ProfileSectionAdmins($this->profile))->getList();
    }
}
