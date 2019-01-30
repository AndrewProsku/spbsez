<?php

namespace Kelnik\User\Components;

use Bex\Bbc;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Kelnik\Userdata\Admin;
use Kelnik\Userdata\Data;
use Kelnik\Userdata\Model\DocsTable;

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

    protected function executeMain()
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            return false;
        }

        if ($this->arParams['SECTION'] == 'docs') {
            return $this->processDocs();
        } elseif ($this->arParams['SECTION'] == 'admins') {
            return $this->processAdmins();
        }
    }

    protected function processDocs()
    {
        global $USER;

        $allowExt = DocsTable::getAllowExt();
        foreach ($allowExt as $k => $v) {
            $this->arResult['ACCEPT'][] = $k;
            $this->arResult['ACCEPT'][] = '.' . $v;
        }

        $this->arResult['ACCEPT'] = implode(',', $this->arResult['ACCEPT']);
        $this->arResult['ERROR'] = false;

        $this->arResult['DOCS'] = DocsTable::getListByUser($USER->GetID());

        return true;
    }

    protected function processAdmins()
    {
        global $USER;

        try {
            $adminModel = new Admin($USER->GetID());

//            if (!$adminModel->hasEditResidentAdmin()) {
//                LocalRedirect('/cabinet/');
//            }

            $this->arResult['USERS'] = $adminModel->getEditableUsers();
        } catch (\Exception $e) {
            $this->arResult['USERS'] = [];
        }

        return true;
    }
}
