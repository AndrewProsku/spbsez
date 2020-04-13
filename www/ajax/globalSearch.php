<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Entity\ExpressionField;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Info\Model\DocsTable as InfoDocsTable;
use Kelnik\News\Categories\CategoriesTable;
use Kelnik\News\News\NewsTable;
use Kelnik\Refbook\Model\DocsTable as RefDocsTable;
use Kelnik\Refbook\Model\ResidentTable;
use Kelnik\Text\Blocks\BlocksTable;
use Kelnik\Vacancy\Model\VacancyTable;

$requiredModules = [
    'kelnik.news',
    'kelnik.text',
    'kelnik.refbook',
    'kelnik.info',
    'kelnik.vacancy'
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
    private $needle;
    public $json;
    private $type;
    private $key;

    public function __construct($request)
    {
        $this->key = 0;
        $this->json = BitrixHelper::getDefaultJson();
        if (!$request) {
            $this->json['status'] = 0;
            $this->json['errors'][] = 'Нет строки поиска';
        }
        $this->needle = $request['q'];

        if ($request['type']) {
            $this->type = 'search' . $request['type'];
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
            $this->searchNews();
            $this->searchTextBlocks();
            $this->searchResidents();
            $this->searchVacancies();
            $this->searchDocs();
        }
        BitrixHelper::jsonResponse($this->json);
    }

    private function searchNews()
    {
        global $DB;
        $newsTable = NewsTable::getTableName();
        $categoryTable = CategoriesTable::getTableName();
        $queryString = "SELECT 
        `{$newsTable}`.`ID`,
        `{$newsTable}`.`NAME`,
        `{$categoryTable}`.`NAME` AS `CATEGORY_NAME`,
        `{$newsTable}`.`CODE` AS `CODE`,
        CASE
            WHEN TEXT LIKE '%{$this->needle}%' THEN TEXT
            WHEN TEXT_PREVIEW LIKE '%{$this->needle}%' THEN TEXT_PREVIEW
            END AS `SEARCH_TEXT`
        FROM `{$newsTable}` 
        LEFT JOIN `{$categoryTable}` ON `{$newsTable}`.`CAT_ID` = `{$categoryTable}`.`ID`
        WHERE `{$newsTable}`.`ACTIVE` = 'Y'
        AND ( `TEXT` LIKE '%{$this->needle}%' OR `TEXT_PREVIEW` LIKE '%{$this->needle}%') 
        LIMIT 6";
        $query = $DB->Query($queryString, true);

        $count = 0;
        while ($oneNews = $query->fetch())
        {
            $count++;
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'news',
                'section' => $oneNews['CATEGORY_NAME'],
                'NAME' => $this->getPreviewText($oneNews['SEARCH_TEXT']),
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
        $textBlocks = BlocksTable::getList(
            [
                'select' => ['ID', 'ACTIVE', 'CATEGORY', 'BODY'],
                'filter' => [
                    '=ACTIVE' => BlocksTable::YES,
                    '%BODY' => $this->needle,
                ],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 6
            ]
        )->FetchAll();

        foreach ($textBlocks as $count => $textBlock) {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'textBlocks',
                'section' => $textBlock['KELNIK_TEXT_BLOCKS_BLOCKS_CATEGORY_TITLE'],
                'NAME' => $this->getPreviewText($textBlock['BODY']),
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $textBlock['KELNIK_TEXT_BLOCKS_BLOCKS_CATEGORY_ALIAS'] . '/'
            ];
            if ($count >= 5 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=TextBlocks&q=' . $this->needle;
                $this->key++;
                break;
            }
        }
    }

    private function searchResidents()
    {
        global $DB;
        $residentTable = ResidentTable::getTableName();
        $queryString = "SELECT
        CASE
            WHEN `TEXT` LIKE '%{$this->needle}%' THEN `TEXT`
            WHEN `NAME` LIKE '%{$this->needle}%' THEN `NAME`
            END AS `SEARCH_TEXT`
        FROM `{$residentTable}`
        WHERE `ACTIVE` = 'Y'
        AND (`TEXT` LIKE '%{$this->needle}%' OR `NAME` LIKE '%{$this->needle}%' )
        LIMIT 6";
        $query = $DB->Query($queryString, true);

        $count = 0;
        while ($resident = $query->fetch())
        {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'residents',
                'section' => 'Резиденты',
                'NAME' => $this->getPreviewText($resident['SEARCH_TEXT']),
                'LINK' => $_SERVER['HTTP_ORIGIN'] . '/residents/'
            ];
            if ($count >= 5 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=Residents&q=' . $this->needle;
                $this->key++;
                break;
            }
        }
    }

    private function searchVacancies()
    {
        global $DB;
        $vacancyTable = VacancyTable::getTableName();
        $queryString = "SELECT 
        CASE
            WHEN DESCR LIKE '%{$this->needle}%' THEN DESCR
            WHEN DUTIES LIKE '%{$this->needle}%' THEN DUTIES
            WHEN REQUIREMENTS LIKE '%{$this->needle}%' THEN REQUIREMENTS
            WHEN CONDITIONS LIKE '%{$this->needle}%' THEN CONDITIONS
            END AS `SEARCH_TEXT`
        FROM `{$vacancyTable}`  
        WHERE `ACTIVE` = 'Y'
        AND ( DESCR LIKE '%{$this->needle}%' 
            OR DUTIES LIKE '%{$this->needle}%' 
            OR REQUIREMENTS LIKE '%{$this->needle}%' 
            OR CONDITIONS LIKE '%{$this->needle}%')
        LIMIT 6";
        $query = $DB->Query($queryString, true);

        $count = 0;
        while ($vacancy = $query->fetch())
        {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'vacancies',
                'section' => 'Вакансии',
                'NAME' => $this->getPreviewText($vacancy['SEARCH_TEXT']),
                'LINK' => $_SERVER['HTTP_ORIGIN'] . '/vacancy/'
            ];
            if ($count >= 5 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=Vacancies&q=' . $this->needle;
                $this->key++;
                break;
            }
        }
    }

    private function searchDocs()
    {
        $docs = RefDocsTable::getList(
            [
                'select' => ['ID', 'NAME', 'FILE_ID'],
                'filter' => [
                    '=ACTIVE' => RefDocsTable::YES,
                    '%NAME' => $this->needle
                ],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 6
            ]
        )->fetchAll();

        $infoDocs = InfoDocsTable::getList(
            [
                'select' => ['ID', 'NAME', 'FILE_ID'],
                'filter' => [
                    '=ACTIVE' => InfoDocsTable::YES,
                    '%NAME' => $this->needle
                ],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 6
            ]
        )->fetchAll();

        $docs = array_merge($docs, $infoDocs);

        foreach ($docs as $count => $doc) {
            $this->json['data'][$this->key]['documents'] [] = [
                'page' => 'docs',
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

    private function getPreviewText(string $text): string
    {
        $text = str_replace('&nbsp;', ' ', strip_tags($text));

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
        if (!($position <= 1 || $first === strtoupper($first))) {
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

        return $text;
    }
}

$search = new Search($_REQUEST);
$search->doSearch();
