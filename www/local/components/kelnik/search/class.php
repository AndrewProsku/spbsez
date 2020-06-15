<?php

require($_SERVER['DOCUMENT_ROOT'] . '/search/Search.php');

use Bex\Bbc;
use Bitrix\Main\Entity\ExpressionField;
use Kelnik\Helpers\BitrixHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}


class SearchList extends Bbc\Basis
{
    protected $needModules = [
        'kelnik.news',
        'kelnik.text',
        'kelnik.refbook',
        'kelnik.info',
        'kelnik.vacancy'
    ];

    protected $checkParams = [];

    protected function executeMain()
    {
       $this->SetResultCacheKeys($_REQUEST['q'] ?: 1);
    }

    protected function executeEpilog()
    {
        $search = new Search($_REQUEST);
        $search->executeSearch($this->needModules);

    }

}
