<?php
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Report\Model\StatusTable;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

\Bitrix\Main\Loader::includeModule('kelnik.report');

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$reportId = $request->get('reportId');
if ($reportId > 0) {
	$report = ReportsTable::getList([
		'filter' => ['ID' => $reportId]
	])->fetchObject();
	$report->setStatusId(StatusTable::NEW);
    $report->setIsLocked(false);
    $report->save();
    echo '{"error": false}';
} else {
	echo '{"error": true, "message": "Отчёт не нейден"}';
}
