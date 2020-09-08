<?php

namespace Kelnik\News\Component;

use Bex\Bbc\BasisRouter;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class NewsRouter extends BasisRouter
{
    protected $defaultSefPage = 'index';
    protected $cacheTemplate = false;

    protected function setSefDefaultParams()
    {
        $this->defaultUrlTemplates404 = [
            'index' => '',
            'section' => '#SECTION_ID#/',
            'detail' => '#SECTION_ID#/#ELEMENT_ID#/'
        ];

        $this->componentVariables = [
            'SECTION_ID',
            'SECTION_CODE',
            'ELEMENT_ID',
            'ELEMENT_CODE'
        ];
    }

    protected function executeProlog()
    {
        foreach (['SECTION_ID', 'SECTION_CODE'] as $field) {
            if (!isset($this->variables[$field]) && !empty($this->arParams[$field])) {
                $this->variables[$field] = $this->arParams[$field];
            }
        }
    }
}
