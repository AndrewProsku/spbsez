<?php

namespace Kelnik\Messages\Components;

use Bex\Bbc\Basis;
use Bitrix\Iblock\Component\Tools;
use Kelnik\Messages\MessageService;
use Kelnik\Requests\Model\NotifyTable;
use Kelnik\Userdata\Profile\Profile;

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
     * @var Profile
     */
    private $profile;

    protected function executeProlog()
    {
        global $USER;

        $this->addCacheAdditionalId($USER->GetID());
        $this->profile = Profile::getInstance($USER->GetID());

    }

    protected function executeMain()
    {
        $this->setResultCacheKeys(['ELEMENT']);

        if (!$this->profile->canMessages()) {
            LocalRedirect(LANG_DIR . 'cabinet/');
        }

        self::registerCacheTag('kelnik:messagesList');
        self::registerCacheTag('kelnik:messagesRow_' . $this->profile->getId() . '_' . $this->arParams['ELEMENT_TYPE'] . $this->arParams['ELEMENT_ID']);

        $messages = MessageService::getInstance($this->profile);
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

        MessageService::getInstance($this->profile)->setViewed($this->arParams['ELEMENT_TYPE'], $this->arParams['ELEMENT_ID']);
    }
}
