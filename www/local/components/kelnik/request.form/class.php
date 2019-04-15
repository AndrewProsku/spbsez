<?php

namespace Kelnik\User\Components;

use Bex\Bbc;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Kelnik\Requests\Model\AreaTable;
use Kelnik\Requests\Model\ServiceTable;
use Kelnik\Requests\Model\ServiceTypeTable;
use Kelnik\Requests\Model\TypeTable;
use Kelnik\UserData\Profile\Profile;
use Kelnik\UserData\Profile\ProfileSectionRequests;

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

    /**
     * @var Profile
     */
    protected $profile;

    /**
     * @var ProfileSectionRequests
     */
    protected $sectionRequests;

    protected function executeProlog()
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            return false;
        }

        try {
            $this->profile = Profile::getInstance((int)$USER->GetID());
            $this->sectionRequests = new ProfileSectionRequests($this->profile);
            $this->sectionRequests->setFormType($this->arParams['SUB_TYPE']);
        } catch (\Exception $exception) {
            return false;
        }

        if (!$this->profile->canRequest()) {
            LocalRedirect(LANG_DIR . 'cabinet/');
        }
    }

    protected function executeMain()
    {
        global $USER;

        if ($this->arParams['SUB_TYPE'] === TypeTable::SUB_TYPE_SERVICE) {
            $this->arResult['TYPES'] = ServiceTypeTable::getList([
                'filter' => [
                    '=ACTIVE' => ServiceTypeTable::YES
                ],
                'order' => [
                    'SORT' => 'ASC'
                ]
            ]);

            $lang = strtoupper(Context::getCurrent()->getLanguage());

            if ($lang !== 'RU') {
                foreach ($this->arResult['TYPES'] as &$v) {
                    $v['NAME'] = !empty($v['NAME_' . $lang]) ? $v['NAME_' . $lang] : $v['NAME'];
                }
            }

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

        if ($request->isPost()) {
            $this->abortCache();

            $this->arResult['FORM'] = $this->sectionRequests->prepareData($request->getPostList()->toArray());
            $this->arResult['ERRORS']['FIELDS'] = $this->sectionRequests->getFormErrors();

            if (!$this->sectionRequests->canAddNewRow()) {
                $this->arResult['ERRORS']['TEXT'][] = Loc::getMessage('KELNIK_REQ_TIME_LEFT');
            }

            if (!$this->arResult['ERRORS']['FIELDS'] && !$this->arResult['ERRORS']['TEXT']) {
                $this->arResult['REQUEST_ID'] = $this->sectionRequests->add($this->arResult['FORM']);

                $this->arResult['USER_EMAIL'] = $this->profile->getField('EMAIL');

                return true;
            }
        }

        $typesTable = $this->arParams['SUB_TYPE'] === TypeTable::SUB_TYPE_STANDARD
                        ? TypeTable::class
                        : AreaTable::class;

        if ($this->arParams['SUB_TYPE'] === TypeTable::SUB_TYPE_PERMIT) {
            if (empty($this->arResult['FORM']['_PASS_'])) {
                $this->arResult['FORM']['_PASS_'] = [[]];
            }

            if (!empty($this->arResult['FORM']['DATE_START'])) {
                $this->arResult['FORM']['DATE_START'] = $this->arResult['FORM']['DATE_START']->format('d.m.Y, H:i');
            }
        }

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
