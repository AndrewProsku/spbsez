<?php
namespace Kelnik\Report\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use CAdminTabControl;
use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Report\Model\Report;
use Kelnik\Report\Model\ReportFieldsTable;
use Kelnik\Report\Model\ReportsTable;

Loc::loadMessages(__FILE__);

class ReportsEditHelper extends AdminEditHelper
{
    protected static $model = ReportsTable::class;

    /**
     * @var Report
     */
    protected $report;

    public function __construct(array $fields, array $tabs = [])
    {
        $tmpTabs = array_merge(
            [
                'MAIN' => [
                    'title' => Loc::getMessage('KELNIK_TAB_MAIN')
                ]
            ],
            ReportFieldsTable::getFormConfig()
        );

        $tabs = [];

        foreach ($tmpTabs as $k => $v) {
            $tabs[] = [
                'DIV' => $k !== 'MAIN' ? 'FORM_' . $k : $k,
                'TAB' => $v['title'],
                'ICON' => '',
                'TITLE' => $v['title'],
                'VISIBLE' => true,
                'TMPL' => 'fields_' . $k
            ];
        }

        parent::__construct($fields, $tabs);

        $this->tabControl = false;
        $this->report = ReportsTable::getReport($this->data['COMPANY_ID'], $this->data['ID']);
        $this->report->fill();
    }

    public function show()
    {
        global $APPLICATION;

        if (!$this->hasReadRights()) {
            $this->addErrors(Loc::getMessage('KELNIK_ADMIN_HELPER_ACCESS_FORBIDDEN'));
            $this->showMessages();

            return false;
        }

        $tabControl = new CAdminTabControl('tabControl', $this->tabs);

        $APPLICATION->SetAdditionalCSS(getLocalPath('css/kelnik.report/report.css'));

        $tabControl->Begin();
        echo '<form method="POST" enctype="multipart/form-data">';
        echo bitrix_sessid_post();

        $formConfig = ReportFieldsTable::getFormConfig();

        foreach ($this->tabs as $tabKey => $tab) {
            $tabControl->BeginNextTab();
            include 'tabs/tab_' . strtolower($tab['DIV']) . '.php';
        }

        $tabControl->Buttons();
        include_once 'tabs/buttons.php';

        $tabControl->End();
        echo '</form>';
    }

    public function getValue($fieldName, $groupId = 0)
    {
        $keys = $this->report->getFields()->getAssocArray();
        $values = $this->report->getFields()->getArray();

        return ArrayHelper::getValue(
            $values,
            ArrayHelper::getValue($keys, $fieldName. '.' . $groupId) . '.VALUE'
        );
    }

    public function getComment($name)
    {
        return htmlentities(
                ArrayHelper::getValue($this->data, $name . '_COMMENT'),
                ENT_QUOTES,
                'UTF-8'
        );
    }
}
