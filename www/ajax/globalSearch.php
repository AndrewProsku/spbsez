<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Kelnik\Helpers\BitrixHelper;
use Kelnik\News\News\NewsTable;

$requiredModules = [
    'kelnik.news',
    'kelnik.text',
    'kelnik.refbook',
    'kelnik.info'
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

class Search {

    private $request;
    private $filter;
    public $json;
    private $type;
    private $key = 0;

    function __construct($req){
        $this->request = $req;
        $this->json = BitrixHelper::getDefaultJson();
        if(!$this->request) {
            $this->json['status'] = 0;
            $this->json['errors'][] = 'Нет строки поиска';
        }
        $this->filter = array('%NAME' => $req['q']);

        if($this->request['type']) {
            $this->type = 'search'.$this->request['type'];
        }
    }

    public function doSearch(){
        if($this->type) {
            if(method_exists($this, $this->type)){
                $this->{$this->type}();
            }
            return;
        }
        else{
            $this->searchNews();
            $this->searchDocs();
        }
        BitrixHelper::jsonResponse($this->json);
    }

    public function searchNews(){
        $news = NewsTable::getList(
            [
                'select' => ['ID', 'NAME', 'CAT', 'CODE'],
                'filter' => $this->filter,
                'group' => ['ID'],
                'order' => ['ID' => 'desc']
            ]
        )->FetchAll();

        foreach ($news as $count => $oneNews){
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'news',
                'section' => $oneNews['KELNIK_NEWS_NEWS_NEWS_CAT_NAME'],
                'NAME' => $oneNews['NAME'],
                'LINK' => $_SERVER['HTTP_ORIGIN'].'/media/news/'.$oneNews['CODE'].'/'
            ];
            if($count >= 2 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=News&q='.$_REQUEST['q'];
                $this->key++;
                break;
            }
        }
    }

    public function searchDocs(){
        $docs = Kelnik\Refbook\Model\DocsTable::getList(
            [
                'select' => ['ID', 'NAME', 'FILE_ID'],
                'filter' => $this->filter,
                'group' => ['ID'],
                'order' => ['ID' => 'desc']
            ]
        )->fetchAll();

        $infoDocs = Kelnik\Info\Model\DocsTable::getList(
            [
                'select' => ['ID', 'NAME', 'FILE_ID'],
                'filter' => $this->filter,
                'group' => ['ID'],
                'order' => ['ID' => 'desc']
            ]
        )->fetchAll();

        $docs = array_merge($docs, $infoDocs);

        foreach ($docs as $count => $doc) {
            $this->json['data'][$this->key]['documents'] [] = [
                'page' => 'docs',
                //  'section' => $oneNews['KELNIK_NEWS_NEWS_NEWS_CAT_NAME'],
                'NAME' => $doc['NAME'],
                'LINK' => CFile::GetFileArray($doc['FILE_ID'])['SRC']
            ];
            if($count >= 2 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=Docs&q='.$_REQUEST['q'];
                $this->key++;
                break;
            }
        }
    }
}

$search = new Search($_REQUEST);
$search->doSearch();