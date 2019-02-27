<?php

namespace Kelnik\Usermenu\Components;

use Bex\Bbc;
use Kelnik\Helpers\ArrayHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class LangMenu extends Bbc\Basis
{
    protected $cacheTemplate = false;

    protected function executeProlog()
    {
        $this->addCacheAdditionalId(LANGUAGE_ID);
    }

    protected function executeMain()
    {
        $this->setResultCacheKeys(['LANGS', 'CURRENT_LANG']);

        $this->arResult['CURRENT_LANG'] = '';
        $this->arResult['LANGS'] = [
            'ru' => [
                'LABEL' => 'Ru',
                'NAME' => 'Рус',
                'URL' => '/',
                'SELECTED' => false
            ],
            'en' => [
                'LABEL' => 'En',
                'NAME' => 'Eng',
                'URL' => '/en/',
                'SELECTED' => false
            ],
            'ch' => [
                'LABEL' => 'Ch',
                'NAME' => '中國',
                'URL' => '/ch/',
                'SELECTED' => false
            ]
        ];

        if (!isset($this->arResult['LANGS'][LANGUAGE_ID])) {
            return;
        }

        $this->arResult['LANGS'][LANGUAGE_ID]['SELECTED'] = true;
        $this->arResult['CURRENT_LANG'] = ArrayHelper::getValue($this->arResult['LANGS'], LANGUAGE_ID . '.LABEL');
    }
}
