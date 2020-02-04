<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Kelnik\Helpers\BitrixHelper;
use Kelnik\News\News\NewsTable;

$requiredModules = [
    'kelnik.news',
    'kelnik.text'
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

$filter = array('?NAME' => $_REQUEST['q']);

$news = NewsTable::getList(
    [
        'select' => ['ID', 'NAME', 'CAT', 'CODE'],
        'filter' => $filter,
        'group' => ['ID']
    ]
)->FetchAll();

foreach ($news as $oneNews){
    $json['data']['items'] [] = [
        'page' => 'news',
        'section' => $oneNews['KELNIK_NEWS_NEWS_NEWS_CAT_NAME'],
        'NAME' => $oneNews['NAME'],
        'LINK' => $_SERVER['HTTP_ORIGIN'].'/media/news/'.$oneNews['CODE'].'/'
    ];
}


BitrixHelper::jsonResponse($json);
