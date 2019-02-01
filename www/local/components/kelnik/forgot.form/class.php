<?php

namespace Kelnik\User\Components;

use Bex\Bbc;
use Bitrix\Main\Context;
use Kelnik\Helpers\ArrayHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class AuthForm extends Bbc\Basis
{
    protected $needModules = [];
    protected $checkParams = [];
    protected $cacheTemplate = false;

    protected function executeMain()
    {
        $getParams = Context::getCurrent()->getRequest()->getQueryList()->toArray();
        $fields = [
            'change_password',
            'USER_CHECKWORD',
            'USER_LOGIN'
        ];

        foreach ($fields as $field) {
            $this->arResult['PARAMS'][$field] = htmlentities(
                ArrayHelper::getValue($getParams, $field, false),
                ENT_QUOTES,
                'UTF-8'
            );
        }

        $this->arResult['CHANGE_PASSWORD'] = $this->arResult['PARAMS']['change_password'] === 'yes';

        if (!$this->arResult['CHANGE_PASSWORD']) {
            return;
        }

        $user = \CUser::GetByLogin($this->arResult['PARAMS']['USER_LOGIN'])->fetch();

        if (empty($user['ID'])) {
            $this->arResult['CHANGE_PASSWORD'] = false;
            return;
        }
    }

    protected function executeEpilog()
    {
        global $APPLICATION;

        if (!$this->arResult['CHANGE_PASSWORD']) {
            return;
        }

        $APPLICATION->SetTitle('Ввод нового пароля');
    }
}
