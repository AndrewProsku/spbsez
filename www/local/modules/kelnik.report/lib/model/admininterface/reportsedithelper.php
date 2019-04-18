<?php
namespace Kelnik\Report\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use CAdminTabControl;
use Kelnik\AdminHelper\Helper\AdminEditHelper;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Report\Model\Report;
use Kelnik\Report\Model\ReportFieldsTable;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Report\Model\StatusTable;

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
                'KEY' => $k
            ];
        }

        parent::__construct($fields, $tabs);

        if (empty($this->data['ID'])) {
            return;
        }

        $this->tabControl = false;
        $this->report = ReportsTable::getReport($this->data['COMPANY_ID'], $this->data['ID']);
        $this->report->fill();

        if (!empty($_REQUEST['done']) || !empty($_REQUEST['decline'])) {
            $this->saveElement($this->data['ID']);

            LocalRedirect(ReportsListHelper::getUrl());
        }
    }

    protected function editAction()
    {
        return false;
    }

    protected function saveElement($id = null)
    {
        global $USER;

        $this->report->setStatusId( StatusTable::DONE);
        $this->report->setDateModified(new DateTime());
        $this->report->setModifiedBy($USER->GetID());
        $this->report->setIsLocked(false);

        if (!empty($_REQUEST['decline'])) {
            $this->report->setStatusId(StatusTable::DECLINED);

            $this->report->setNameComment(ArrayHelper::getValue($_REQUEST, 'commentMain.NAME'));
            $this->report->setNameSezComment(ArrayHelper::getValue($_REQUEST, 'commentMain.NAME_SEZ'));
            $this->report->updateFieldComments(ArrayHelper::getValue($_REQUEST, 'comment', []));

            return $this->report->save();
        }

        $this->report->setNameComment(null);
        $this->report->setNameSezComment(null);

        return $this->report->save();
    }

    public function show()
    {
        global $APPLICATION;

        (new \CAdminContextMenu([current($this->getMenu())]))->Show();

        if (!$this->hasReadRights() || empty($this->data['ID'])) {
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
        $stages = \Kelnik\Report\Model\ReportFieldsTable::getStages();
        $formDefaults = [
            ReportFieldsTable::FORM_TAXES,
            ReportFieldsTable::FORM_RESULT,
            'MAIN'
        ];

        foreach ($this->tabs as $tabKey => $tab) {
            $tabControl->BeginNextTab();
            echo '<tr><td>';
            $formNum = $tab['KEY'];
            if (!in_array($formNum, $formDefaults, true)) {
                $tab['DIV'] = 'form_default';
            }
            include 'tabs/tab_' . strtolower($tab['DIV']) . '.php';
            echo '</td></tr>';
        }

        $tabControl->Buttons();
        include_once 'tabs/buttons.php';

        $tabControl->End();
        echo '</form>';
    }

    public function getValue($fieldName, $groupId = 0, $formNum = 0, $returnField = 'VALUE')
    {
        $keys = $this->report->getFields()->getAssocArray();
        $values = $this->report->getFields()->getArray();

        return ArrayHelper::getValue(
            $values,
            ArrayHelper::getValue($keys, $fieldName. '.' . $groupId . '.' . $formNum) . '.' . $returnField
        );
    }

    public function getValueComment($fieldName, $groupId = 0, $formNum = 0)
    {
        return htmlentities(
            $this->getValue($fieldName, $groupId, $formNum, 'COMMENT'),
            ENT_QUOTES.
            'UTF-8'
        );
    }

    public function getGroup($formNum, $type)
    {
        return ArrayHelper::getValue(
            $this->report->getGroups()->getArray(),
            $type . '.' . $formNum,
            []
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
