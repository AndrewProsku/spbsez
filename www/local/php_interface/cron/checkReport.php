<?php
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Mail\Event;

$docRoot  = realpath(__DIR__ . '/../../../');

if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = $docRoot;
}

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_STATISTIC', true);
define('STOP_STATISTICS', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

//получаем список периодов отчётности
Loader::includeModule('highloadblock'); 
$hlbl = 2;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock); 
$entity_data_class = $entity->getDataClass(); 
$rsData = $entity_data_class::getList(array(
   'select' => array('*'),
   'order' => array('ID' => 'ASC'),
   'filter' => array()
));
$arPeriods = array();
while ($arData = $rsData->Fetch()) {
   $arPeriods[$arData['UF_CODE']] = [
        'START' => $arData['UF_DATE_START'],
        'END' => $arData['UF_DATE_END']
   ];
}

$arPeriodsFormat = [];
//выбираем даты начала отчётности
if (!empty($arPeriods['FIRST_QUARTER']['START'])) {
    $arStart = explode('.', $arPeriods['FIRST_QUARTER']['START']);
    $arPeriodsFormat['FIRST_QUARTER'] = mktime(0, 0, 0, $arStart[1], $arStart[0], date('Y'));
}

if (!empty($arPeriods['SECOND_QUARTER']['START'])) {
    $arStart = explode('.', $arPeriods['SECOND_QUARTER']['START']);
    $arPeriodsFormat['SECOND_QUARTER'] = mktime(0, 0, 0, $arStart[1], $arStart[0], date('Y'));
}

if (!empty($arPeriods['THIRD_QUARTER']['START'])) {
    $arStart = explode('.', $arPeriods['THIRD_QUARTER']['START']);
    $arPeriodsFormat['THIRD_QUARTER'] = mktime(0, 0, 0, $arStart[1], $arStart[0], date('Y'));
}

if (!empty($arPeriods['PRE_ANNUAL']['START'])) {
    $arStart = explode('.', $arPeriods['PRE_ANNUAL']['START']);           
    $arPeriodsFormat['PRE_ANNUAL'] = mktime(0, 0, 0, $arStart[1], $arStart[0], date('Y'));
}

if (!empty($arPeriods['ANNUAL']['START'])) {
    $arStart = explode('.', $arPeriods['ANNUAL']['START']);
    $arPeriodsFormat['ANNUAL'] = mktime(0, 0, 0, $arStart[1], $arStart[0], date('Y'));
}

//выбираем даты окончания отчётности
$arPeriodsEndFormat = [];
if (!empty($arPeriods['FIRST_QUARTER']['END'])) {
    $arEnd = explode('.', $arPeriods['FIRST_QUARTER']['END']);
    $arPeriodsEndFormat['FIRST_QUARTER'] = mktime(0, 0, 0, $arEnd[1], $arEnd[0], date('Y'));
}

if (!empty($arPeriods['SECOND_QUARTER']['END'])) {
    $arEnd = explode('.', $arPeriods['SECOND_QUARTER']['END']);
    $arPeriodsEndFormat['SECOND_QUARTER'] = mktime(0, 0, 0, $arEnd[1], $arEnd[0], date('Y'));
}

if (!empty($arPeriods['THIRD_QUARTER']['END'])) {
    $arEnd = explode('.', $arPeriods['THIRD_QUARTER']['END']);
    $arPeriodsEndFormat['THIRD_QUARTER'] = mktime(0, 0, 0, $arEnd[1], $arEnd[0], date('Y'));
}

if (!empty($arPeriods['PRE_ANNUAL']['END'])) {
    $arEnd = explode('.', $arPeriods['PRE_ANNUAL']['END']);           
    $arPeriodsEndFormat['PRE_ANNUAL'] = mktime(0, 0, 0, $arEnd[1], $arEnd[0], date('Y'));
}

if (!empty($arPeriods['ANNUAL']['END'])) {
    $arEnd = explode('.', $arPeriods['ANNUAL']['END']);
    $arPeriodsEndFormat['ANNUAL'] = mktime(0, 0, 0, $arEnd[1], $arEnd[0], date('Y'));
}

$currentDate = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

global $SUER;
//выбираем юзеров без галочки об отправке, с заполненной компанией, из групп резидентов
$dbUserList = $USER->getList($by = "id", $order = "asc", ['UF_CHECK_REPORT' => false, 'WORK_COMPANY' => '_%', 'GROUPS_ID' => [9,10]]);
$userEmail = [];
while ($arUser = $dbUserList->fetch()) {
    $userEmail[$arUser['ID']] = $arUser['EMAIL'];
}
if (!empty($userEmail)) {
    //берём часть
    $targetMails = array_chunk($userEmail, 20, true);
    $targetMails = $targetMails[0];    
    //проверяем, совпадает ли текущая дата с началом отчётности
    $matchDate = false;
    $periodCode = false;
    foreach ($arPeriodsFormat as $period => $date) {
        if ($date == $currentDate) {
            $matchDate = true;
            $periodCode = $period;
        }
    }
    if ($matchDate) {
        foreach ($targetMails as $uid => $mail) {
            //отправляем письма о начале отчётности - отправка в первый день начала отчётности
            $event = new Event();            
            $fields = ["RESIDENT_EMAIL" => $mail];
            if ($arPeriodsEndFormat[$periodCode]) {
                $fields["END_REPORT_PERIOD"] = FormatDate('d F Y', $arPeriodsEndFormat[$periodCode]);
            }
            $event->send(array(
                "EVENT_NAME" => "REPORT_NOTIFICATION",
                "LID" => "s1",
                "C_FIELDS" => $fields,
            ));
            //проставляем флаг пользователю, что письмо отправлено
            $USER->Update($uid, ['UF_CHECK_REPORT' => true]);
        }
    }    
} else {
    //если пользователи не найдены, то вероятно, все флаги проставлены, и письма отправлены    
    //выбираем юзеров с галочкой об отправке, с заполненной компанией, из групп резидентов
    //обнуляем флаги при условии, что сегодня не день начала отчётности
    if (!in_array($currentDate, $arPeriodsFormat)) {
        $dbUserList = $USER->getList($by = "id", $order = "asc", ['UF_CHECK_REPORT' => true, 'WORK_COMPANY' => '_%', 'GROUPS_ID' => [9,10]]);
        $userIds = [];
        while ($arUser = $dbUserList->fetch()) {
            $userIds[] = $arUser['ID'];
        }
        if (!empty($userIds)) {
            foreach ($userIds as $uid) {
                $USER->Update($uid, ['UF_CHECK_REPORT' => false]);
            }
        }
    }
}
