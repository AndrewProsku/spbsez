<?php

namespace Kelnik\Report\Model\AdminInterface;

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use CAdminTabControl;
use Kelnik\AdminHelper\Helper\AdminBaseHelper;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Report\Model\Export;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Report\Model\StatusTable;
use Kelnik\Userdata\Profile\Profile;

Loc::loadMessages(
    __DIR__ . DIRECTORY_SEPARATOR
    . '..' . DIRECTORY_SEPARATOR . 'reportstable.php'
);

class ReportsExportHelper extends AdminBaseHelper
{
    protected static $model = ReportsTable::class;

    /**
     * @var array
     */
    protected $companies = [];

    /**
     * @var array
     */
    protected $periods = [];

    public function __construct(array $fields, array $tabs = [], $module = "")
    {
        parent::__construct($fields, $tabs, $module);

        $this->companies = Profile::getAdminCompanyList();

        try {
            $tmp = ReportsTable::getAssoc([
                'select' => [
                    'YEAR', 'TYPE'
                ],
                'filter' => [
                    '=COMPANY_ID' => array_keys($this->companies),
                    '=STATUS_ID' => StatusTable::DONE
                ],
                'order' => [
                    'YEAR' => 'DESC',
                    'TYPE' => 'ASC'
                ]
            ]);
        } catch (\Exception $e) {
            $tmp = [];
        }

        if (!$tmp) {
            return;
        }

        foreach ($tmp as $v) {
            $this->periods[] = [
                'ID' => $v['YEAR'] . '_' . $v['TYPE'],
                'YEAR' => $v['YEAR'],
                'NAME' => ArrayHelper::getValue(ReportsTable::getTypes(), $v['TYPE'], '-')
            ];
        }
    }

    public function show()
    {
        global $APPLICATION;

        if (!$this->hasWriteRights()) {
            $this->addErrors(Loc::getMessage('KELNIK_ADMIN_HELPER_ACCESS_FORBIDDEN'));
            $this->showMessages();

            return false;
        }

        $request = Context::getCurrent()->getRequest();

        if ($request->isPost()) {
            $APPLICATION->RestartBuffer();

            list($year, $type) = explode('_', $request->getPost('period'));

            try {
                $export = new Export(
                    $year,
                    $type,
                    (array) $request->getPost('residents')
                );
                $export->getFile();
            } catch (\Exception $e) {
                $this->addErrors([$e->getMessage()]);
                LocalRedirect(self::getUrl());
            }

            exit;
        }

        $tabControl = new CAdminTabControl('tabControl', [
            [
                'DIV' => 'common',
                'TAB' => Loc::getMessage('KELNIK_REPORTS_TAB_COMMON_NAME'),
                'TITLE' => Loc::getMessage('KELNIK_REPORTS_TAB_COMMON_TITLE'),
            ]
        ]);


        $this->showMessages();


        $tabControl->begin();
        ?>
        <style>
            .report-export-wrapper {
                display: flex;
                justify-content: center;
                flex-flow: column;
                align-items: center;
            }
            .export-block-row {
                display: flex;
            }
            .export-block {
                margin: 10px;
                width: 200px;
            }
            .export-block label,
            .export-block select {
                width: 100%;
            }
            .export-block label {
                display: block;
                font-weight: bold;
                font-size: 16px;
                margin: 0 0 10px 0;
            }
        </style>
        <form method="post"
              enctype="multipart/form-data"
              action="<?= self::getUrl([]); ?>">
            <?php
                echo bitrix_sessid_post();
                $tabControl->beginNextTab();
            ?>
            <tr>
                <td>
                    <div class="report-export-wrapper">
                        <div class="export-block-row">
                            <div class="export-block">
                                <label for="residents"><?= Loc::getMessage('KELNIK_REPORT_EXPORT_RESIDENT'); ?></label>
                                <select id="residents" name="residents[]" multiple="multiple">
                                    <?php foreach ($this->companies as $residentId => $resident): ?>
                                        <option value="<?= $residentId; ?>"><?= $resident; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="export-block">
                                <label for="period"><?= Loc::getMessage('KELNIK_REPORT_EXPORT_PERIOD'); ?></label>
                                <select id="period" name="period">
                                    <?php
                                        $year = (int) current($this->periods)['YEAR'];
                                    ?>
                                    <optgroup label="<?= $year; ?>">
                                        <?php foreach ($this->periods as $period): ?>
                                            <?php if((int)$period['YEAR'] !== $year): ?>
                                                </optgroup>
                                                <optgroup label="<?= $year = (int)$period['YEAR']; ?>">
                                            <?php endif; ?>
                                            <option value="<?= $period['ID']; ?>"><?= $period['NAME']; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="export-block-row adm-detail-content-btns">
                            <input class="adm-btn-save" type="submit" value="<?= Loc::getMessage('KELNIK_REPORT_EXPORT_BTN'); ?>">
                        </div>
                    </div>
                </td>
            </tr>
            <?php $tabControl->end(); ?>
        </form>
        <?php
    }
}
