<?php

namespace Kelnik\Questions\Component;

use Bex\Bbc;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Questions\Model\QuestionsTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class QuestionsList extends Bbc\Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.questions'];

    protected function executeMain()
    {
        try {
            $this->arResult['QUESTIONS'] = QuestionsTable::getList([
                'select' => [
                    'NAME',
                    'URL'
                ],
                'filter' => [
                    '=ACTIVE' => 'Y',
                    '=LANG' => LANGUAGE_ID ?? 'ru',
                ],
                'order' => [
                    'SORT' => 'ASC'
                ]
            ])->fetchAll();

            if (!$this->arResult['QUESTIONS']) {
                return;
            }
        } catch (\Exception $e) {
        }
    }
}
