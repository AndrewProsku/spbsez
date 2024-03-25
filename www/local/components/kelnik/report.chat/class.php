<?php

namespace Kelnik\Messages\Components;

use Bex\Bbc\Basis;
use Bitrix\Main\Context;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;
use Kelnik\Messages\Model\MessagesChatTable;
use Kelnik\Messages\Model\MessagesChat;
use Kelnik\Report\Model\ReportFieldsTable;
use Kelnik\Report\Model\AdminInterface\ReportsEditHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class MessagesList extends Basis
{
    protected $cacheTemplate = false;
    protected $needModules = ['kelnik.report', 'kelnik.messages'];
    protected $checkParams = [
        'REPORT_ID' => ['type' => 'int', 'error' => false],
    ];

    /**
     * @var integer
     */
    protected $userId;

    /**
     * @var integer
     */
    protected $reportId;

    protected function executeProlog()
    {
        $this->userId = \CUser::GetID();
        $this->reportId = $this->arParams['REPORT_ID'];

        if (!$this->userId || !$this->reportId) {
            return false;
        }
    }

    protected function executeMain()
    {
    	$this->answerHadler();

        $this->arResult['REPORT_ID'] = $this->reportId;

        //получаем сообщения по отчёту
        $messages = MessagesChatTable::getChatMessagesByReport($this->reportId);

        foreach ($messages as &$message) {
	        $message['USER_NAME'] = $this->getUserName($message);

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
        }

        foreach ($messages as $k => &$message) {
            $this->setChild($k, $message, $messages);
        }

        $this->arResult['MESSAGES'] = $messages;

    }

    protected function answerHadler()
    {
    	$request = \Bitrix\Main\Context::getCurrent()->getRequest();
    	if ($request->get("sendAnswer") && $request->get("sendAnswer") == 'Y') {
	    	$answer = $request->get("answer");
	    	if ($answer) {
	    		$chat = new MessagesChat;

		        //ищем ИД сообщения в тексте ответа
		        preg_match_all("/#(.*)#/U",  $answer, $matches);
		        $cleanAnswer = trim(str_replace($matches[0], '', $answer));
		        if ($matches[1][0] > 0) {
		        	$parentId = $matches[1][0];
		        	$parentMessage = MessagesChatTable::getChatMessages(['=ID' => $parentId, '=REPORT_ID' => $this->reportId]);
		        	if ($parentMessage) {
                        if ($parentMessage->getParentId() > 0) {
                            $parentId = $parentMessage->getParentId();
                        }
                        $chat->setParentId($parentId);
                    }
		        }

		        $chat->setText($cleanAnswer)
		        	->setUserId($this->userId)
		            ->setReportId($this->reportId)
		            ->setIsAdmin(0)
		            ->setDateModified(new DateTime())
		            ->setDateCreated(new DateTime());
                $chat->save();
		    } else {
		    	return false;
		    }
		} else {
			return false;
		}
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

    protected function setChild($k, &$message, &$messages) {
        foreach ($messages as $key => $value) {
            if ($value['PARENT_ID'] == $k) {
                $this->setChild($key, $value, $messages);
                $message['CHILDREN'][$value['ID']] = $value;
                unset($messages[$key]);
            }
        }
    }
}
