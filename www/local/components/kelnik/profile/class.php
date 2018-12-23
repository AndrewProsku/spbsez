<?php

namespace Kelnik\User\Components;

use Bex\Bbc;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Kelnik\Userdata\Model\DocsTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class ProfileForm extends Bbc\Basis
{
    protected $needModules = ['kelnik.userdata'];
    protected $checkParams = [
        'SECTION' => ['type' => 'string']
    ];
    protected $cacheTemplate = false;

    protected function executeMain()
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            return false;
        }

        if ($this->arParams['SECTION'] == 'docs') {
            return $this->processDocs();
        }
    }

    protected function processDocs()
    {
        global $USER;

        $allowExt = DocsTable::getAllowExt();
        foreach ($allowExt as $k => $v) {
            $this->arResult['ACCEPT'][] = $k;
            $this->arResult['ACCEPT'][] = '.' . $v;
        }

        $this->arResult['ACCEPT'] = implode(',', $this->arResult['ACCEPT']);
        $this->arResult['ERROR'] = false;

        $doc = Context::getCurrent()->getRequest()->getFile('doc');

        if (!empty($doc)) {
            if (!is_uploaded_file($doc['tmp_name'])) {
                $this->arResult['ERROR'] = Loc::getMessage('KELNIK_PROFILE_DOC_FILE_UPLOAD_ERROR');
                return false;
            }

            if (!in_array($doc['type'], array_keys($allowExt))) {
                $this->arResult['ERROR'] = Loc::getMessage('KELNIK_PROFILE_DOC_FILE_EXT_ERROR');
                return false;
            }

            try {
                $doc['MODULE_ID'] = 'kelnik.userdata';
                $fileId = \CFile::SaveFile($doc, $doc['MODULE_ID'], true);
            } catch (\Exception $e) {
                $this->arResult['ERROR'] = $e->getMessage();
                return false;
            }

            if (!$fileId) {
                $this->arResult['ERROR'] = Loc::getMessage('KELNIK_PROFILE_DOC_FILE_UPLOAD_ERROR');
                return false;
            }

            try {
                DocsTable::add([
                    'USER_ID' => $USER->GetID(),
                    'FILE_ID' => $fileId
                ]);
            } catch (\Exception $e) {}

            LocalRedirect('/cabinet/docs/');
        }

        $this->arResult['DOCS'] = DocsTable::getListByUser($USER->GetID());

        return true;
    }
}
