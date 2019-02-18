<?php

namespace Kelnik\Messages\Components;

use Bex\Bbc\BasisRouter;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class Messages extends BasisRouter
{
    protected $cacheTemplate = false;

    protected function setSefDefaultParams()
    {
        $this->defaultUrlTemplates404 = [
            'list' => '',
            'search' => 'search/',
            'detail' => '#ELEMENT_TYPE#-#ELEMENT_ID#/'
        ];

        $this->componentVariables = [
            'ELEMENT_ID',
            'ELEMENT_TYPE'
        ];
    }

    protected function executeProlog()
    {
        $this->variables['IS_SEARCH'] = $this->isSearchRequest();
    }
}
