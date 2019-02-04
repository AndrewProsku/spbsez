<?php

namespace Kelnik\User\Components;

use Bex\Bbc;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Requests\Model\StandartTable;
use Kelnik\Requests\Model\TypeTable;
use Kelnik\Userdata\Profile\ProfileModel;
use Kelnik\Userdata\Profile\ProfileSectionRequests;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class RequestForm extends Bbc\Basis
{
    protected $needModules = ['kelnik.requests', 'kelnik.userdata'];
    protected $checkParams = [];
    protected $cacheTemplate = false;

    protected function executeMain()
    {
        global $USER;

        $request = Context::getCurrent()->getRequest();
        $this->arResult['ERRORS'] = [
            'FIELDS' => [],
            'TEXT' => []
        ];
        $arResult['FORM'] = [
            'NAME' => '',
            'THEME' => 0,
            'MESSAGE' => ''
        ];

        if (!$USER->IsAuthorized()) {
            return false;
        }

        try {
            $profile = new ProfileModel($USER->GetID());
            $sectionRequests = new ProfileSectionRequests($profile);
        } catch (\Exception $exception) {
            return false;
        }

        if (!$profile->canRequest()) {
            LocalRedirect('/cabinet/');
        }

        if ($request->isPost()) {
            $this->abortCache();
            $postData = $request->getPostList()->toArray();
            foreach ($arResult['FORM'] as $k => $v) {
                $postKey = strtolower($k);

                $row = ArrayHelper::getValue($postData, $postKey);
                $row = $postKey == 'theme'
                        ? (int) $row
                        : htmlentities($row, ENT_QUOTES, 'UTF-8');
                $this->arResult['FORM'][$k] = $row;

                if ($postKey == 'message' && mb_strlen($row) < 4) {
                    $row = null;
                }

                if (!$row) {
                    $this->arResult['ERRORS']['FIELDS'][$k] = Loc::getMessage('KELNIK_REQ_FIELD_REQUIRED');
                }
            }

            if (!$sectionRequests->canAddNewRow()) {
                $this->arResult['ERRORS']['TEXT'][] = Loc::getMessage('KELNIK_REQ_TIME_LEFT');
            }

            if (!$this->arResult['ERRORS']['FIELDS'] && !$this->arResult['ERRORS']['TEXT']) {
                $this->arResult['REQUEST_ID'] = $sectionRequests->add([
                    'TYPE_ID' => (int) $this->arResult['FORM']['THEME'],
                    'NAME'    => $this->arResult['FORM']['NAME'],
                    'BODY'    => $this->arResult['FORM']['MESSAGE']
                ]);

                $this->arResult['USER_EMAIL'] = $profile->getField('EMAIL');

                return true;
            }
        }

        $this->arResult['TYPES'] = TypeTable::getAssoc([
            'select' => ['ID', 'NAME'],
            'filter' => [
                '=ACTIVE' => TypeTable::YES
            ],
            'order' => [
                'SORT' => 'ASC'
            ]
        ], 'ID');
    }
}
