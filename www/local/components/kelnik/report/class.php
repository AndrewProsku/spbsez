<?php

namespace Kelnik\Report\Component;

use Bex\Bbc\BasisRouter;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class ReportRouter extends BasisRouter
{
    protected $defaultSefPage = 'index';
    protected $cacheTemplate = false;

    protected function setSefDefaultParams()
    {
        $this->defaultUrlTemplates404 = [
            'index' => '',
            'section' => '',
            'detail' => '#ELEMENT_ID#/'
        ];

        $this->componentVariables = [
            'ELEMENT_ID'
        ];
    }
}
