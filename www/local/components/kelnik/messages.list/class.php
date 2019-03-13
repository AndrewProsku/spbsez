<?php

namespace Kelnik\Messages\Components;

use Bex\Bbc\Basis;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\PluralHelper;
use Kelnik\Messages\MessageService;
use Kelnik\Userdata\Profile\Profile;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class MessagesList extends Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.userdata', 'kelnik.messages'];
    protected $checkParams = [
        'IS_SEARCH' => ['type' => 'string', 'error' => false]
    ];

    /**
     * @var Profile
     */
    protected $profile;

    protected function executeProlog()
    {
        global $USER;

        $this->addCacheAdditionalId($USER->GetID());
        $this->arParams['YEAR'] = date('Y');

        $this->profile = Profile::getInstance($USER->GetID());
    }

    protected function executeMain()
    {
        $this->setResultCacheKeys(['YEARS', 'MESSAGES', 'QUERY', 'CNT', 'CNT_WORD', 'SHOW_MORE']);

        if (!$this->profile->canMessages()) {
            LocalRedirect(LANG_DIR . 'cabinet/');
        }

        self::registerCacheTag('kelnik:messages_list_' . $this->profile->getId());

        $messages = MessageService::getInstance($this->profile);
        $messages->calcCount();
        $messages->sefFolder = $this->arParams['SEF_FOLDER'];
        $messages->dateFormat = $this->arParams['DATE_FORMAT'];

        if ($this->arParams['IS_SEARCH']) {
            $this->arResult['QUERY'] = htmlentities(Context::getCurrent()->getRequest()->getQuery('q'), ENT_QUOTES, 'UTF-8');
            $this->arResult['MESSAGES'] = $messages::prepareList(
                $messages->getList(
                    false,
                    Context::getCurrent()->getRequest()->getQuery('q')
                ),
                false
            );

            $this->arResult['CNT'] = count($this->arResult['MESSAGES']);
            $this->arResult['CNT_WORD'] = PluralHelper::pluralForm(
                $this->arResult['CNT'],
                [
                    Loc::getMessage('KELNIK_MESSAGES_CNT_1'),
                    Loc::getMessage('KELNIK_MESSAGES_CNT_2'),
                    Loc::getMessage('KELNIK_MESSAGES_CNT_3')
                ]
            );

            return true;
        }

        $this->arResult['YEARS'] = $messages->getYears();
        $this->arResult['MESSAGES'] = $messages::prepareList($messages->getList($this->arParams['YEAR']));
        $this->arResult['SHOW_MORE'] = count($messages->getMonthsByYear($this->arParams['YEAR'])) > MessageService::MONTHS_COUNT;
    }
}
