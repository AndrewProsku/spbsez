<?php

namespace Kelnik\Report\Component;

use Bex\Bbc;
use Bitrix\Main\Localization\Loc;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Report\Reports;
use Kelnik\Userdata\Profile\Profile;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class ReportList extends Bbc\Basis
{
    use Bbc\Traits\Elements;

    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.report', 'iblock', 'kelnik.userdata'];
    protected $checkParams = [];

    /**
     * @var Profile
     */
    protected $profile;

    protected function executeProlog()
    {
        global $USER;

        $this->addCacheAdditionalId($USER->GetID());
        $this->addCacheAdditionalId(date('Y'));

        $this->profile = Profile::getInstance($USER->GetID());

        if (!$this->profile->canReport()) {
            LocalRedirect(LANG_DIR . 'cabinet/');
        }
    }

    protected function executeMain()
    {
        $this->arResult['DISABLED'] = true;
        $this->arResult['REPORTS']  = [];
        $this->arResult['YEAR']     = date('Y');

        try {
            $reports = ReportsTable::getList([
                'filter' => [
                    '=COMPANY_ID' => $this->profile->getCompanyId()
                ],
                'order' => [
                    'YEAR' => 'DESC',
                    'QUARTER' => 'ASC'
                ]
            ])->fetchCollection();
        } catch (\Exception $e) {
            return;
        }

        if (!$reports->count()) {
            return;
        }

        $this->arResult['DISABLED'] = false;

        foreach ($reports as $v) {
            $year = $v->getYear();

            if (!isset($this->arResult['REPORTS'][$year])) {
                $this->arResult['REPORTS'][$year] = [
                    'NAME' => $year,
                    'IS_ARCHIVE' => $v->getIsArchive(),
                    'ELEMENTS' => []
                ];
            }

            $this->arResult['REPORTS'][$year]['ELEMENTS'][] = $v;
        }
    }
}
