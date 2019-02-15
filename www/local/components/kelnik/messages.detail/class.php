<?php

namespace Kelnik\Messages\Components;

use Bex\Bbc\Basis;
use Kelnik\Messages\MessageModel;
use Kelnik\Userdata\Profile\ProfileModel;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class MessagesDetail extends Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.userdata', 'kelnik.messages'];
    protected $checkParams = [
        'ELEMENT_TYPE' => ['type' => 'string'],
        'ELEMENT_ID' => ['type' => 'int']
    ];

    protected function executeProlog()
    {
        global $USER;

        $this->addCacheAdditionalId($USER->GetID());
    }

    protected function executeMain()
    {
        global $USER;

        $this->setResultCacheKeys(['ELEMENT']);

        $profile = ProfileModel::getInstance($USER->GetID());

        if (!$profile->canMessages()) {
            LocalRedirect('/cabinet/');
        }

        $messages = MessageModel::getInstance($profile);
        $this->arResult['ELEMENT'] = $messages->getMessage($this->arParams['ELEMENT_TYPE'], $this->arParams['ELEMENT_ID']);
    }
}
