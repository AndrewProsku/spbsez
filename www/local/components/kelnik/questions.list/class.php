<?php

namespace Kelnik\Questions\Component;

use Bex\Bbc;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Questions\Model\QuestionsTable;
use Bitrix\Main\Context;

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
            $filter = [
                '=ACTIVE' => 'Y',
                '=LANG' => LANGUAGE_ID,
            ];
            $select = [
                '*'
            ];

            if($this->arParams['SEARCH_NAME']){
                $request = Context::getCurrent()->getRequest();
                $searchValue = $request->get($this->arParams['SEARCH_NAME']);
                if($searchValue){
                    $filter['%NAME'] = $searchValue;
                }
            }
            if($this->arParams['USE_TYPES'] == 'Y'){
                $filter['=TYPE.ACTIVE'] = 'Y';
                $select = array_merge(
                    $select,
                    [
                        'TYPE_NAME' => 'TYPE.NAME',
                        'TYPE_NAME_EN' => 'TYPE.NAME_EN'
                    ]
                );
            }

            $this->arResult['QUESTIONS'] = QuestionsTable::getList([
                'select' => $select,
                'filter' => $filter,
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
