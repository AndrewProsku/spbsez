<?php


$json = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/tests/search/globalSearch.json');
die($json);
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Kelnik\Helpers\BitrixHelper;

$requiredModules = [
    'kelnik.estate',
    'kelnik.estatesearch'
];

foreach ($requiredModules as $requiredModule) {
    if (!CModule::IncludeModule($requiredModule)) {
        die(json_encode([
            'request' => [
                'status' => 0,
                'errors' => [
                    'Internal error'
                ]
            ]
        ]));
    }
}

$json = BitrixHelper::getDefaultJson();

$searchClass = '\\Kelnik\\EstateSearch\\Visual\\' . ucfirst($_REQUEST['step']) . 'Visual';

if (!class_exists($searchClass)) {
    $json['request']['errors'] = 'Error load step class';
    BitrixHelper::jsonResponse($json);
    die;
};

try {
    $search = new $searchClass;
    $json['request']['result'] = 1;
    $request = $_REQUEST;
    $json['data'] = $search->processAjaxResult($request);

} catch (\Exception $e) {
    $json['request']['errors'] = $e->getMessage();
}

BitrixHelper::jsonResponse($json);
