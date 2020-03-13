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
            $this->type = 'search' . $this->request['type'];
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
                'LINK' => $_SERVER['HTTP_ORIGIN'] . '/media/news/' . $oneNews['CODE'] . '/'
            ];
            if ($count >= 5 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=News&q=' . $this->needle;
                $this->key++;
                break;
            }
        }
    }

    private function searchTextBlocks()
    {
        $residents = Kelnik\Text\Blocks\BlocksTable::getList(
            [
                'select' => ['ID', 'ACTIVE', 'CATEGORY', 'BODY'],
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
            $text = strip_tags($oneResident['BODY']);
            $text = str_replace('&nbsp;', ' ', $text);
            $position = stripos($text, $this->needle);
            $start = $position - 20;
            $lenght = 70;
            if ($start < 0) {
                $start = 0;
                $lenght = $lenght - $start;
            }
            $text = substr($text, $start, $lenght);
            $position = stripos($text, $this->needle);
            $first = substr($text, 0, 1);
            if (!($position <= 2 || $first === strtoupper($first))) {
                $text = ltrim($text);
                $position = stripos($text, ' ');
                $text = substr($text, $position + 1);
            }
            $end = substr($text, -1);
            if (strtolower($end) !== strtoupper($end)) {
                $textArray = explode(' ', $text);
                array_pop($textArray);
                $text = implode(' ', $textArray);
            }
            $first = substr($text, 0, 1);
            if ($first !== strtoupper($first)) {
                $text = '...' . $text;
            }
            if (!in_array(substr($text, -1), ['!', '.', '?', ';', ':'])) {
                $text .= '...';
            }


            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'news',
                'section' => $oneResident['KELNIK_TEXT_BLOCKS_BLOCKS_CATEGORY_TITLE'],
                'NAME' => $text,
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
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=Docs&q=' . $this->needle;
                $this->key++;
                break;
            }
        }
    }
}

$search = new Search($_REQUEST);
$search->doSearch();
