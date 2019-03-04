<?php

namespace Kelnik\Messages\Components;

use Bex\Bbc\Basis;
use Bitrix\Iblock\Component\Tools;
use Kelnik\Messages\MessageEnvelope;
use Kelnik\Requests\Model\NotifyTable;
use Kelnik\Userdata\Profile\ProfileEnvelope;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class MessagesDetail extends Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.userdata', 'kelnik.messages', 'iblock'];
    protected $checkParams = [
        'ELEMENT_TYPE' => ['type' => 'string'],
        'ELEMENT_ID' => ['type' => 'int']
    ];
    /**
     * @var ProfileEnvelope
     */
    private $profile;

    protected function executeProlog()
    {
        global $USER;

        $this->addCacheAdditionalId($USER->GetID());
        $this->profile = ProfileEnvelope::getInstance($USER->GetID());
    }

    protected function executeMain()
    {
        $this->setResultCacheKeys(['ELEMENT']);

        if (!$this->profile->canMessages()) {
            LocalRedirect('/cabinet/');
        }

        $messages = MessageEnvelope::getInstance($this->profile);
        $messages->dateFormat = $this->arParams['DATE_FORMAT'];
        $this->arResult['ELEMENT'] = $messages->getMessage($this->arParams['ELEMENT_TYPE'], $this->arParams['ELEMENT_ID']);

        if (!$this->arResult['ELEMENT']) {
            Tools::process404(
                'Not Found',
                true,
                true,
                true
            );
        }
    }

    protected function executeEpilog()
    {
        if (empty($this->arResult['ELEMENT']) || $this->arResult['ELEMENT']['IS_NEW'] !== NotifyTable::YES) {
            return;
        }

        MessageEnvelope::getInstance($this->profile)->setViewed($this->arParams['ELEMENT_TYPE'], $this->arParams['ELEMENT_ID']);
    }
}
