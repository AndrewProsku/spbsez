<?php

namespace Kelnik\User\Components;

use Bex\Bbc;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Requests\Model\AreaTable;
use Kelnik\Requests\Model\ServiceTable;
use Kelnik\Requests\Model\TypeTable;
use Kelnik\Userdata\Profile\Profile;
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
    protected $checkParams = [
        'SUB_TYPE' => ['type' => 'string', 'error' => false]
    ];
    protected $cacheTemplate = false;

    protected function executeMain()
    {
        global $USER;

        if ($this->arParams['SUB_TYPE'] === TypeTable::SUB_TYPE_SERVICE) {
            $this->arResult['TYPES'] = ServiceTable::getTypes();

            return true;
        }

        $request = Context::getCurrent()->getRequest();

        $this->arResult['ERRORS'] = [
            'FIELDS' => [],
            'TEXT' => []
        ];

        if (!$USER->IsAuthorized()) {
            return false;
        }

        try {
            $profile = Profile::getInstance($USER->GetID());
            $sectionRequests = new ProfileSectionRequests($profile);
            $sectionRequests->setFormType($this->arParams['SUB_TYPE']);
        } catch (\Exception $exception) {
            return false;
        }

        if (!$profile->canRequest()) {
            LocalRedirect(LANG_DIR . 'cabinet/');
        }

        if ($request->isPost()) {
            $this->abortCache();

            $formData = $sectionRequests->prepareData($request->getPostList()->toArray());

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

        $typesTable = $this->arParams['SUB_TYPE'] === TypeTable::SUB_TYPE_STANDARD
                        ? TypeTable::class
                        : AreaTable::class;

        $this->arResult['TYPES'] = $typesTable::getAssoc([
            'select' => ['ID', 'NAME'],
            'filter' => [
                '=ACTIVE' => $typesTable::YES
            ],
            'order' => [
                'SORT' => 'ASC'
            ]
        ], 'ID');
    }
}
