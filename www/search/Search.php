<?php

use Bitrix\Main\Entity\ExpressionField;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Info\Model\DocsTable as InfoDocsTable;
use Kelnik\News\Categories\CategoriesTable;
use Kelnik\News\News\NewsTable;
use Kelnik\Refbook\Model\DocsTable as RefDocsTable;
use Kelnik\Refbook\Model\ResidentTable;
use Kelnik\Text\Blocks\BlocksTable;
use Kelnik\Text\Blocks\CategoriesTable as CategoriesTextTable;
use Kelnik\Vacancy\Model\VacancyTable;
use Kelnik\Infrastructure\Model\PlatformTable;

class Search
{
    private $needle;
    private $type;
    private $key;
    private $language;
    public $json;

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
        $cache = \Bitrix\Main\Application::getInstance()->getManagedCache();

        if ($this->type) {
            if (method_exists($this, $this->type)) {
                $this->{$this->type}();
            }
            return;
        }

        $this->searchNews();
        $this->searchTextBlocks();
        $this->searchResidents();
        $this->searchVacancies();
        $this->searchDocs();
        $this->searchTextCategories();
        $this->searchPlatformInfrastructure();

        $data = BitrixHelper::jsonResponse($this->json);
        $cacheTtl = 'search';
        $cacheId = 'globalSearch';

        if ($cache->read($cacheTtl, $cacheId)) {
            $result = $cache->get($cacheId);
        } else {
            $cache->set($cacheId, array("key" => $data));
        }

        return $result;
    }

    private function searchNews()
    {
        global $DB;
        $connection = Bitrix\Main\Application::getConnection();
        $sqlHelper = $connection->getSqlHelper();
        $needle = '%' . $sqlHelper->forSql($this->needle) . '%';
        $newsTable = NewsTable::getTableName();
        $categoryTable = CategoriesTable::getTableName();
        $queryString = "SELECT 
        `{$newsTable}`.`ID`,
        `{$newsTable}`.`NAME`,
        `{$categoryTable}`.`NAME` AS `CATEGORY_NAME`,
        `{$newsTable}`.`CODE` AS `CODE`,
        CASE
            WHEN `TEXT` LIKE '{$needle}' THEN `TEXT`
            WHEN `TEXT_PREVIEW` LIKE '{$needle}' THEN `TEXT_PREVIEW`
            END AS `SEARCH_TEXT`
        FROM `{$newsTable}` 
        LEFT JOIN `{$categoryTable}` ON `{$newsTable}`.`CAT_ID` = `{$categoryTable}`.`ID`
        WHERE `{$newsTable}`.`ACTIVE` = 'Y'
        AND ( `TEXT` LIKE '{$needle}' OR `TEXT_PREVIEW` LIKE '{$needle}') 
        AND `LANG` = '{$this->language}'
        LIMIT 6";
        $query = $DB->Query($queryString, true);

        $count = 0;
        while ($oneNews = $query->fetch()) {
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
        $connection = Bitrix\Main\Application::getConnection();
        $sqlHelper = $connection->getSqlHelper();
        $residentTable = ResidentTable::getTableName();
        $text = $sqlHelper->forSql($this->conditionByLanguage('text'));
        $name = $sqlHelper->forSql($this->conditionByLanguage('name'));
        $needle = '%' . $sqlHelper->forSql($this->needle) . '%';
        $linkLanguage = $this->conditionByLanguage('link');

        $queryString = "SELECT
        CASE
            WHEN `{$text}` LIKE '{$needle}' THEN `{$text}`
            WHEN `{$name}` LIKE '{$needle}' THEN `{$name}`
            END AS `SEARCH_TEXT`
        FROM `{$residentTable}`
        WHERE `ACTIVE` = 'Y'
        AND (`{$text}` LIKE '{$needle}' OR `{$name}` LIKE '{$needle}')
        LIMIT 6";
        $query = $DB->Query($queryString, true);

        $count = 0;
        while ($resident = $query->fetch()) {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'residents',
                'section' => $this->language == 'en' ? 'Residents' : 'Резиденты',
                'NAME' => $this->getPreviewText($resident['SEARCH_TEXT']),
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $linkLanguage . '/residents/'
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
        $connection = Bitrix\Main\Application::getConnection();
        $sqlHelper = $connection->getSqlHelper();
        $needle = '%' . $sqlHelper->forSql($this->needle) . '%';
        $queryString = "SELECT 
        CASE
            WHEN DESCR LIKE '{$needle}' THEN DESCR
            WHEN DUTIES LIKE '{$needle}' THEN DUTIES
            WHEN REQUIREMENTS LIKE '{$needle}' THEN REQUIREMENTS
            WHEN CONDITIONS LIKE '{$needle}' THEN CONDITIONS
            END AS `SEARCH_TEXT`
        FROM `{$vacancyTable}`  
        WHERE `ACTIVE` = 'Y'
        AND ( DESCR LIKE '{$needle}' 
            OR DUTIES LIKE '{$needle}' 
            OR REQUIREMENTS LIKE '{$needle}' 
            OR CONDITIONS LIKE '{$needle}')
        LIMIT 6";
        $query = $DB->Query($queryString, true);

        $count = 0;
        while ($vacancy = $query->fetch()) {
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

    private function searchTextCategories()
    {
        $textBlocks = CategoriesTextTable::getList(
            [
                'select' => ['ID', 'TITLE', 'ALIAS'],
                'filter' => [
                    '%TITLE' => $this->needle,
                ],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 6
            ]
        )->FetchAll();

        foreach ($textBlocks as $count => $textBlock) {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'textCategory',
                'section' => $textBlock['TITLE'],
                'NAME' => $textBlock['TITLE'],
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $textBlock['ALIAS'] . '/'
            ];
            if ($count >= 5 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=TextBlocks&q=' . $this->needle;
                $this->key++;
                break;
            }
        }
    }

    private function searchPlatformInfrastructure()
    {
        \Bitrix\Main\Loader::includeModule('kelnik.infrastructure');
        $name = $this->conditionByLanguage('likeName');
        $extraConditionForName = $this->language === 'ru' ? '_RU' : '';
        $textBlocks = PlatformTable::getList(
            [
                'select' => ['ID', 'NAME_RU', 'NAME_EN', 'ALIAS'],
                'filter' => [
                    $name => $this->needle,
                ],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 6
            ]
        )->FetchAll();

        foreach ($textBlocks as $count => $textBlock) {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'infrastructurePlatform',
                'section' => $this->language == 'en' ? 'Infrastructure' : 'Инфраструктура',
                'NAME' => $textBlock[$this->conditionByLanguage('name') . $extraConditionForName],
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->conditionByLanguage('link') . '/infrastructure/' . $textBlock['ALIAS'] . '/'
            ];
            if ($count >= 6 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=TextBlocks&q=' . $this->needle;
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

    private function conditionByLanguage($condition)
    {
        $this->language = 'ru';
        if (strpos($_SERVER['HTTP_REFERER'], 'en')) {
            $this->language = 'en';
        }

        $conditionByLanguageArray = [
            'likeName' => [
                'en' => '%NAME_EN',
                'ru' => '%NAME_RU'
            ],
            'text' => [
                'en' => 'TEXT_EN',
                'ru' => 'TEXT',
            ],
            'name' => [
                'en' => 'NAME_EN',
                'ru' => 'NAME'
            ],
            'link' => [
                'en' => '/en',
                'ru' => '',
            ],
        ];

        return $conditionByLanguageArray[$condition][$this->language];
    }

}
