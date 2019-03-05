<?php

namespace Kelnik\Report\Component;

use Bex\Bbc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class ReportDetail extends Bbc\Basis
{
    use Bbc\Traits\Elements;

    protected $needModules = ['kelnik.report', 'iblock'];
    protected $checkParams = [
        'ELEMENT_ID' => ['type' => 'int', 'error' => false]
    ];

    protected function executeProlog()
    {
        if (!$this->arParams['ELEMENT_ID']) {
            $this->return404(true);
        }
    }

    protected function executeMain()
    {
        $element = [];

//        if (!$element && $this->arParams['SET_404'] === 'Y') {
//            $this->return404();
//        }
    }
}
