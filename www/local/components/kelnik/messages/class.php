<?php

namespace Kelnik\Messages\Components;

use Bex\Bbc;
use Kelnik\Messages\MessageModel;
use Kelnik\Userdata\Profile\ProfileModel;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class MessagesList extends Bbc\Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.userdata', 'kelnik.messages'];
    protected $checkParams = [
        'ELEMENT_ID' => ['type' => 'string', 'error' => false],
        'YEAR' => ['type' => 'int', 'error' => false],
        'IS_SEARCH' => ['type' => 'string', 'error' => false]
    ];

    protected function executeProlog()
    {
        global $USER;

        $this->addCacheAdditionalId($USER->GetID());

        if (!$this->arParams['YEAR']) {
            $this->arParams['YEAR'] = date('Y');
        }
    }

    protected function executeMain()
    {
        global $USER;

        $this->setResultCacheKeys(['YEARS', 'MESSAGES']);

        $profile = ProfileModel::getInstance($USER->GetID());

        if (!$profile->canMessages()) {
            LocalRedirect('/cabinet/');
        }

        $messages = MessageModel::getInstance($profile);

        $this->arResult['YEARS'] = $messages->getYears();
        $this->arResult['MESSAGES'] = $messages->getList();
    }
}
