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

class MessagesList extends Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.userdata', 'kelnik.messages'];
    protected $checkParams = [
        'IS_SEARCH' => ['type' => 'string', 'error' => false]
    ];

    protected function executeProlog()
    {
        global $USER;

        $this->addCacheAdditionalId($USER->GetID());
        $this->arParams['YEAR'] = date('Y');
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
        $messages->calcCount();
        $messages->sefFolder = $this->arParams['SEF_FOLDER'];
        $messages->dateFormat = $this->arParams['DATE_FORMAT'];

        $this->arResult['YEARS'] = $messages->getYears();
        $this->arResult['MESSAGES'] = $messages::prepareList($messages->getList($this->arParams['YEAR']));
    }
}
