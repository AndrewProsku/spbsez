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
use Kelnik\Userdata\Profile\Profile;
use Kelnik\Messages\Model\MessagesChatTable;
use Kelnik\Messages\Model\MessagesChat;
use Bitrix\Main\Request; 
use Bitrix\Main\Context;

ini_set('max_execution_time', 900);

Loc::loadMessages(__FILE__);

class ReportsEditHelper extends AdminEditHelper
{
    protected static $model = ReportsTable::class;

    /**
     * @var Report
     */
    protected $report;

    /**
     * @var MessagesChat
     */
    protected $messagesChat;


    public function __construct(array $fields, array $tabs = [])
    {
        $tmpTabs = array_merge(
            [
                'MAIN' => [
                    'title' => Loc::getMessage('KELNIK_TAB_MAIN')
                ]
            ],
            ReportFieldsTable::getFormConfig(),
            [
                'CHAT' => [
                    'title' => Loc::getMessage('KELNIK_TAB_CHAT')
                ]
            ]
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

        $companyName = \CUser::GetByID($this->report->getCompanyId())->Fetch();

        $this->setTitle(
            Loc::getMessage('KELNIK_REPORT_TITLE') . ': ' .
            strip_tags($companyName[Profile::COMPANY_NAME_FIELD] . ', ' . $this->report->getTypeName() . ' ' . $this->report->getYear())
        );

        if (!empty($_REQUEST['done']) || !empty($_REQUEST['decline'])) {
            $this->saveElement($this->data['ID']);

            LocalRedirect(ReportsListHelper::getUrl());
        }

        if ($_REQUEST['goBack'] == 'Y') {
            $this->report->setIsRedacting(false);
            $this->report->save();
            LocalRedirect(ReportsListHelper::getUrl());
        } else {
            $this->report->setIsRedacting(true);
            $this->report->save();   
        }

        //Сохраняем сообщение в чате
        $request = Context::getCurrent()->getRequest();
        $chatMessage = $request->getPost("chatMessage");
        global $USER;        
        if (!empty($chatMessage)) {
            $chat = new MessagesChat;
            //ищем ИД сообщения в тексте ответа 
            preg_match_all("/#(.*)#/U",  $chatMessage, $matches);
            $cleanAnswer = trim(str_replace($matches[0], '', $chatMessage));
            if ($matches[1][0] > 0) {
                $parentId = $matches[1][0];
                $parentMessage = MessagesChatTable::getChatMessages(['=ID' => $parentId, '=REPORT_ID' => $this->data['ID']]);
                if ($parentMessage) {
                    if ($parentMessage->getParentId() > 0) {
                        $parentId = $parentMessage->getParentId();
                    }
                    $chat->setParentId($parentId);
                }
            }
            $chat->setText($cleanAnswer)
                 ->setUserId($USER->GetID())
                 ->setReportId($this->data['ID'])
                 ->setIsAdmin(1)
                 ->setDateModified(new DateTime())
                 ->setDateCreated(new DateTime());
            if ($chat->save()) {
                //отправляем уведомление админу резидента
                $residentAdmin = \CUser::GetByID($this->report->getUserId())->Fetch();              
                \Bitrix\Main\Mail\Event::send(array(
                    'EVENT_NAME' => 'CHAT_REPORT_RESIDENT',
                    'LID' => 's1',
                    'C_FIELDS' => array(
                        'TEXT' => $cleanAnswer,
                        'REPORT_ID' => $this->data['ID'],
                        'RESIDENT_EMAIL' => $residentAdmin['EMAIL']
                    ),
                ));
            }
        }

        //Получаем сообщения для отчёта
        $this->messagesChat = MessagesChatTable::getChatMessagesByReport($this->data['ID']); 
        foreach ($this->messagesChat as $k => &$message) {
            /*if ($message['PARENT_ID']) {
                $parentMessage = MessagesChatTable::getChatMessageById($message['PARENT_ID']);                
                $parentMessageUserName = $this->getUserName($parentMessage); 
                $message['PARENT_MESSAGE'] = $parentMessageUserName . ': ' . $parentMessage['TEXT'];
            }*/

            if ($message['FIELD_ID']) {                         
                $field = ReportFieldsTable::getList()
                    ->fetchCollection()
                    ->getByPrimary($message['FIELD_ID']);
                $fieldBlockTitle = ReportFieldsTable::getFormBlockTitle($field->getName(), $field->getFormNum());
                $message['PARENT_MESSAGE'] = 'Ф-' . ($field->getFormNum() + 1) . ', блок ' . $fieldBlockTitle;                  
            }

            $message['USER_NAME'] = $this->getUserName($message);                                           
        }

        foreach ($this->messagesChat as $k => &$message) {
            $this->setChild($k, $message);
        }
    }

    protected function setChild($k, &$message) {
        foreach ($this->messagesChat as $key => $value) {
            if ($value['PARENT_ID'] == $k) {
                $this->setChild($key, $value);
                $message['CHILDREN'][$value['ID']] = $value;
                unset($this->messagesChat[$key]);
            }
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
        $this->report->setIsRedacting(false);

        if (!empty($_REQUEST['decline'])) {
            $this->report->setStatusId(StatusTable::DECLINED);

            $comments = ArrayHelper::getValue($_REQUEST, 'comment', []);

            $this->report->setNameComment(ArrayHelper::getValue($_REQUEST, 'commentMain.NAME'));
            $this->report->setNameSezComment(ArrayHelper::getValue($_REQUEST, 'commentMain.NAME_SEZ'));
            $this->report->updateFieldComments($comments);

            //сохраняем комментарий в чат с указанием поля
            foreach ($comments as $fieldId => $comment) {
                if (strlen($comment) > 0) {
                    $chat = new MessagesChat;
                    $chat->setText($comment)
                         ->setUserId($USER->GetID())
                         ->setReportId($this->data['ID'])
                         ->setFieldId($fieldId)
                         ->setIsAdmin(1)
                         ->setDateModified(new DateTime())
                         ->setDateCreated(new DateTime());
                    $chat->save();
                }
            }            

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
            'MAIN',
            'CHAT'
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

    public function getUserName($message) 
    {
        if ($message['IS_ADMIN'] == 1) {
            return "Администратор";
        } else {
            $user = \CUser::GetByID($message['USER_ID'])->Fetch();
            return $user['NAME'] . ' ' . $user['LAST_NAME'];
        }
    }
}
