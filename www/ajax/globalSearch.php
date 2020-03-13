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

class Search
{

    private $request;
    private $needle;
    public $json;
    private $type;
    private $key = 0;

    public function __construct($req)
    {
        $this->request = $req;
        $this->json = BitrixHelper::getDefaultJson();
        if (!$this->request) {
            $this->json['status'] = 0;
            $this->json['errors'][] = 'Нет строки поиска';
        }
        $this->needle = $this->request['q'];

        if ($this->request['type']) {
            $this->type = 'search'.$this->request['type'];
        }
    }

    public function doSearch()
    {
        if ($this->type) {
            if (method_exists($this, $this->type)) {
                $this->{$this->type}();
            }
            return;
        } else {
            $this->searchTextBlocks();
            $this->searchNews();
            $this->searchDocs();
        }
        BitrixHelper::jsonResponse($this->json);
    }

    private function searchNews()
    {
        $news = NewsTable::getList(
            [
                'select' => ['ID', 'NAME', 'CAT', 'CODE', 'TEXT', 'TEXT'],
                'filter' => ['%TEXT' => $this->needle],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 5
            ]
        )->FetchAll();

        foreach ($news as $count => $oneNews) {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'news',
                'section' => $oneNews['KELNIK_NEWS_NEWS_NEWS_CAT_NAME'],
                'NAME' => $oneNews['NAME'],
                'LINK' => $_SERVER['HTTP_ORIGIN'].'/media/news/'.$oneNews['CODE'].'/'
            ];
            if ($count >= 5 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=News&q='.$this->needle;
                $this->key++;
                break;
            }
        }
    }

    private function searchTextBlocks()
    {
        $residents = Kelnik\Text\Blocks\BlocksTable::getList(
            [
                'select' => ['ID', 'ACTIVE', 'CATEGORY','BODY'],
                'filter' => [
                    '%BODY' => $this->needle,
                    'ACTIVE' => 'Y'
                ],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 5
            ]
        )->FetchAll();

        foreach ($residents as $count => $oneResident) {
//            $strings = preg_split('/\. |\\n|\\r|\\t|, /', $oneResident['BODY'],-1,PREG_SPLIT_NO_EMPTY);
            $name = preg_match("/([^\\n\\t\\r,]+{$this->needle}[^\\n\\t\\r\.]+)/", $oneResident['BODY'], $matches);
//            $name = preg_match("/{$this->needle}/', $oneResident['BODY'], $matches);
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'news',
                'section' => $oneResident['KELNIK_TEXT_BLOCKS_BLOCKS_CATEGORY_TITLE'],
                'NAME' => $matches[0],
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $oneResident['KELNIK_TEXT_BLOCKS_BLOCKS_CATEGORY_ALIAS'] . '/'
            ];
            if ($count >= 5 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=News&q=' . $this->needle;
                $this->key++;
                break;
            }
        }
    }

    private function searchDocs()
    {
        $docs = Kelnik\Refbook\Model\DocsTable::getList(
            [
                'select' => ['ID', 'NAME', 'FILE_ID'],
                'filter' => ['%NAME' => $this->needle],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 5
            ]
        )->fetchAll();

        $infoDocs = Kelnik\Info\Model\DocsTable::getList(
            [
                'select' => ['ID', 'NAME', 'FILE_ID'],
                'filter' => ['%NAME' => $this->needle],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 5
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
            if ($count >= 5 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=Docs&q='.$this->needle;
                $this->key++;
                break;
            }
        }
    }
}

$search = new Search($_REQUEST);
$search->doSearch();
