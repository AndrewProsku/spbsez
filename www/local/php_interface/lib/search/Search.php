<?php

use Bitrix\Main\Entity\ExpressionField;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Info\Model\DocsTable as InfoDocsTable;
use Kelnik\Infrastructure\Model\PlatformTable;
use Kelnik\News\Categories\CategoriesTable;
use Kelnik\News\News\NewsTable;
use Kelnik\Refbook\Model\DocsTable as RefDocsTable;
use Kelnik\Refbook\Model\ResidentTable;
use Kelnik\Refbook\Model\TeamTable;
use Kelnik\Refbook\Model\ResidentTypesTable;
use Kelnik\Text\Blocks\BlocksTable;
use Kelnik\Text\Blocks\CategoriesTable as CategoriesTextTable;
use Kelnik\Vacancy\Model\VacancyTable;

class Search
{
    private $needle;
    private $type;
    private $key;
    private $language;
    private $linkForEmptyRequest;
    private $emptyQuery;
    private $stringForEmptyQuery = [];
    private $connection;
    private $sqlHelper;
    public $json;

    public function __construct($request)
    {
        $this->key = 0;
        $this->json = BitrixHelper::getDefaultJson();
        if (!$request) {
            $this->json['status'] = 0;
            $this->json['errors'][] = 'Нет строки поиска';
        }

        $this->needle = preg_replace('/[^ a-zа-яё\d]/ui', '', $request['q']);
        $this->needle = preg_replace("/\s{2,}/"," ", $this->needle);
        $this->needle = trim($this->needle);

        if ($request['type']) {
            $this->type = 'search' . $request['type'];
        }

        $this->language = 'ru';

        if (strpos($_SERVER['HTTP_REFERER'], 'en')) {
            $this->language = 'en';
        }

        $this->connection = Bitrix\Main\Application::getConnection();
        $this->sqlHelper = $this->connection->getSqlHelper();
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
        $this->searchTeam();
        $this->searchResedentsDescription();
        $this->searchTeamDescription();
        $this->searchResidentsTypes();

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
        $needle = '%' . $this->sqlHelper->forSql($this->needle) . '%';
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
        $needle = str_replace(' ', '|', $this->sqlHelper->forSql($this->needle));
        $textBlocks = BlocksTable::getList(
            [
                'select' => ['ID', 'ACTIVE', 'CATEGORY', 'BODY'],
                'filter' => [
                    '=ACTIVE' => BlocksTable::YES,
                    '?BODY' => $needle,
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
        $text = $this->sqlHelper->forSql($this->conditionByLanguage('text'));
        $name = $this->sqlHelper->forSql($this->conditionByLanguage('name'));
        $needle = '%' . $this->sqlHelper->forSql($this->needle) . '%';
        $linkLanguage = $this->conditionByLanguage('link');
        $section = $this->language == 'en' ? 'Residents' : 'Резиденты';
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
                'section' => $section,
                'NAME' => $this->getPreviewText($resident['SEARCH_TEXT']),
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $linkLanguage . '/residents/'
            ];
            if ($count >= 5 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=Residents&q=' . $this->needle;
                $this->key++;
                break;
            }
        }
        if (empty($resident)) {
            $allResidents = ResidentTable::getList(
                [
                    'select' => [$name],
                    'filter' => [
                        '=ACTIVE' => ResidentTable::YES,
                    ],
                    'group' => ['ID'],
                    'order' => ['ID' => 'desc'],
                ]
            )->FetchAll();

            $typos = $this->searchByTypos($this->needle, $name, $allResidents);
            if ($typos) {
                $this->json['data'][$this->key]['items'][] = [
                    'page' => 'residents',
                    'section' => $section,
                    'NAME' => $typos,
                    'LINK' => $_SERVER['HTTP_ORIGIN'] . $linkLanguage . '/residents/',
                ];
            }
        }
    }

    private function searchVacancies()
    {
        global $DB;
        $vacancyTable = VacancyTable::getTableName();
        $needle = '%' . $this->sqlHelper->forSql($this->needle) . '%';
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
        $needle = str_replace(' ', '|', $this->sqlHelper->forSql($this->needle));

        $docs = RefDocsTable::getList(
            [
                'select' => ['ID', 'NAME', 'FILE_ID'],
                'filter' => [
                    '=ACTIVE' => RefDocsTable::YES,
                    '?NAME' => $needle
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
                    '?NAME' => $needle
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

        if (empty($docs)) {
            $allDocks = InfoDocsTable::getList(
                [
                    'select' => ['NAME', 'FILE_ID'],
                    'filter' => [
                        '=ACTIVE' => InfoDocsTable::YES,
                    ],
                    'group' => ['ID'],
                    'order' => ['ID' => 'desc'],
                ]
            )->FetchAll();

            $typos = $this->searchByTypos($this->needle, 'NAME', $allDocks);
            if ($typos) {
                $this->json['data'][$this->key]['items'][] = [
                    'page' => 'docs',
                    'NAME' => $typos,
                    'LINK' => CFile::GetFileArray($this->linkForEmptyRequest)['SRC'],
                ];
            }
        }
    }

    private function searchTextCategories()
    {
        $needle = str_replace(' ', '|', $this->sqlHelper->forSql($this->needle));
        $sectionName = $this->language === 'ru' ? 'Разделы' : 'Sections';
        $textCategories = CategoriesTextTable::getList(
            [
                'select' => ['ID', 'TITLE', 'ALIAS'],
                'filter' => [
                    '?TITLE' => $needle,
                ],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 6
            ]
        )->FetchAll();

        foreach ($textCategories as $count => $textBlock) {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'textCategory',
                'section' => $sectionName,
                'NAME' => $textBlock['TITLE'],
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $textBlock['ALIAS'] . '/'
            ];
            if ($count >= 5 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=TextCategories&q=' . $this->needle;
                $this->key++;
                break;
            }
        }

        if (empty($textCategories)) {
            $allTextCategories = CategoriesTextTable::getList(
                [
                    'select' => ['TITLE', 'ALIAS'],
                    'group' => ['ID'],
                    'order' => ['ID' => 'desc'],
                ]
            )->FetchAll();

            $typos = $this->searchByTypos($this->needle, 'TITLE', $allTextCategories);
            if ($typos) {
                $this->json['data'][$this->key]['items'][] = [
                    'page' => 'textCategory',
                    'section' => $sectionName,
                    'NAME' => $typos,
                    'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->linkForEmptyRequest . '/',
                ];
            }
        }
    }

    private function searchPlatformInfrastructure()
    {
        \Bitrix\Main\Loader::includeModule('kelnik.infrastructure');
        $nameLike = $this->conditionByLanguage('likeName');
        $extraConditionForName = $this->language === 'ru' ? '_RU' : '';
        $name = $this->conditionByLanguage('name') . $extraConditionForName;
        $textBlocks = PlatformTable::getList(
            [
                'select' => ['ID', 'NAME_RU', 'NAME_EN', 'ALIAS'],
                'filter' => [
                    $nameLike => $this->needle,
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
                'NAME' => $textBlock[$name],
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->conditionByLanguage('link') . '/infrastructure/' . $textBlock['ALIAS'] . '/'
            ];
            if ($count >= 6 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=TextBlocks&q=' . $this->needle;
                $this->key++;
                break;
            }
        }

        if ($this->key === 0) {
            $allTextBlocks = PlatformTable::getList(
                [
                    'select' => [$name, 'ALIAS'],
                    'group' => ['ID'],
                    'order' => ['ID' => 'desc'],
                ]
            )->FetchAll();

            $typos = $this->searchByTypos($this->needle, $name, $allTextBlocks);

            if ($typos) {
                $this->json['data'][$this->key]['items'][] = [
                    'page' => 'infrastructurePlatform',
                    'section' => $this->language == 'en' ? 'Infrastructure' : 'Инфраструктура',
                    'NAME' => $typos,
                    'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->conditionByLanguage('link') . '/infrastructure/' . $this->linkForEmptyRequest . '/'
                ];
            }
        }
    }

    private function searchTeam()
    {
        $needle = str_replace(' ', '|', $this->sqlHelper->forSql($this->needle));
        $nameIsRegular = $this->conditionByLanguage('nameRegular');
        $name = $this->conditionByLanguage('name');
        $companyTeam = TeamTable::getList(
            [
                'select' => ['ID', 'NAME', 'NAME_EN'],
                'filter' => [
                    $nameIsRegular => $needle,
                ],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 6
            ]
        )->FetchAll();

        foreach ($companyTeam as $count => $team) {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'companyTeam',
                'section' => $this->language == 'en' ? 'Team' : 'Команда',
                'NAME' => $team[$name],
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->conditionByLanguage('link') . '/about/management-company/#company-team'
            ];
            if ($count >= 6 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=CompanyTeam&q=' . $this->needle;
                $this->key++;
                break;
            }
        }

        if ($this->key === 0) {
            $companyTeamAll = TeamTable::getList(
                [
                    'select' => [$name],
                    'group' => ['ID'],
                    'order' => ['ID' => 'desc'],
                ]
            )->FetchAll();

            $typos = $this->searchByTypos($this->needle, $name, $companyTeamAll);

            if ($typos) {
                $this->json['data'][$this->key]['items'][] = [
                    'page' => 'companyTeam',
                    'section' => $this->language == 'en' ? 'Team' : 'Команда',
                    'NAME' => $typos,
                    'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->conditionByLanguage('link') . '/about/management-company/#company-team'
                ];
            }
        }
    }

    private function searchTeamDescription()
    {
        $needle = str_replace(' ', '|', $this->sqlHelper->forSql($this->needle));
        $textIsRegular = $this->conditionByLanguage('textRegular');
        $text = $this->conditionByLanguage('text');
        $companyTeamDescription = TeamTable::getList(
            [
                'select' => ['ID', 'TEXT', 'TEXT_EN'],
                'filter' => [
                    $textIsRegular => $needle,
                ],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 6
            ]
        )->FetchAll();

        foreach ($companyTeamDescription as $count => $teamDecr) {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'descriptionTeam',
                'section' => $this->language == 'en' ? 'Participants description' : 'Описание сотрудников',
                'NAME' => $teamDecr[$text],
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->conditionByLanguage('link') . '/about/management-company/#company-team'
            ];
            if ($count >= 6 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=DescriptionTeam&q=' . $this->needle;
                $this->key++;
                break;
            }
        }

        if ($this->key === 0) {
            $teamDesrAll = TeamTable::getList(
                [
                    'select' => [$text],
                    'group' => ['ID'],
                    'order' => ['ID' => 'desc'],
                ]
            )->FetchAll();

            $typos = $this->searchByTypos($this->needle, $text, $teamDesrAll);

            if ($typos) {
                $this->json['data'][$this->key]['items'][] = [
                    'page' => 'descriptionTeam',
                    'section' => $this->language == 'en' ? 'Participants description' : 'Описание сотрудников',
                    'NAME' => $typos,
                    'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->conditionByLanguage('link') . '/about/management-company/#company-team'
                ];
            }
        }
    }

    private function searchResidentsTypes()
    {
        $needle = str_replace(' ', '|', $this->sqlHelper->forSql($this->needle));
        $nameIsRegular = $this->conditionByLanguage('nameRegular');
        $name = $this->conditionByLanguage('name');
        $residentsTypes = ResidentTypesTable::getList(
            [
                'select' => ['ID', 'NAME', 'NAME_EN'],
                'filter' => [
                    $nameIsRegular => $needle,
                ],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 6
            ]
        )->FetchAll();

        foreach ($residentsTypes as $count => $residentType) {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'typesResidents',
                'section' => $this->language == 'en' ? 'Categories residents' : 'Категории резидентов',
                'NAME' => $residentType[$name],
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->conditionByLanguage('link') . '/residents/#types-residents'
            ];
            if ($count >= 6 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=ResidentsTypes&q=' . $this->needle;
                $this->key++;
                break;
            }
        }

        if ($this->key === 0) {
            $residentsTypesAll = TeamTable::getList(
                [
                    'select' => [$name],
                    'group' => ['ID'],
                    'order' => ['ID' => 'desc'],
                ]
            )->FetchAll();

            $typos = $this->searchByTypos($this->needle, $name, $residentsTypesAll);

            if ($typos) {
                $this->json['data'][$this->key]['items'][] = [
                    'page' => 'typesResidents',
                    'section' => $this->language == 'en' ? 'Categories residents' : 'Категории резидентов',
                    'NAME' => $typos,
                    'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->conditionByLanguage('link') . '/residents/#types-residents'
                ];
            }
        }
    }

    private function searchResedentsDescription()
    {
        $needle = str_replace(' ', '|', $this->sqlHelper->forSql($this->needle));
        $textIsRegular = $this->conditionByLanguage('textRegular');
        $text = $this->conditionByLanguage('text');
        $residentsDescription = ResidentTable::getList(
            [
                'select' => ['ID', 'TEXT', 'TEXT_EN'],
                'filter' => [
                    $textIsRegular => $needle,
                ],
                'group' => ['ID'],
                'order' => ['ID' => 'desc'],
                'limit' => 6
            ]
        )->FetchAll();

        foreach ($residentsDescription as $count => $resDesc) {
            $this->json['data'][$this->key]['items'] [] = [
                'page' => 'residentsDescription',
                'section' => $this->language == 'en' ? 'Description residents' : 'Описание резидентов',
                'NAME' => $resDesc[$text],
                'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->conditionByLanguage('link') . '/residents/#residents-description'
            ];
            if ($count >= 6 && !$this->type) {
                $this->json['data'][$this->key]['linkMore'] = '/search/?type=DescriptionResidents&q=' . $this->needle;
                $this->key++;
                break;
            }
        }

        if ($this->key === 0) {
            $resDescAll = ResidentTable::getList(
                [
                    'select' => [$text],
                    'group' => ['ID'],
                    'order' => ['ID' => 'desc'],
                ]
            )->FetchAll();

            $typos = $this->searchByTypos($this->needle, $text, $resDescAll);

            if ($typos) {
                $this->json['data'][$this->key]['items'][] = [
                    'page' => 'residentsDescription',
                    'section' => $this->language == 'en' ? 'Description residents' : 'Описание резидентов',
                    'NAME' => $typos,
                    'LINK' => $_SERVER['HTTP_ORIGIN'] . $this->conditionByLanguage('link') . '/residents/#residents-description'
                ];
            }
        }
    }

    private function getPreviewText(string $text): string
    {
        $text = str_replace('&nbsp;', ' ', strip_tags($text));
        $this->emptyQuery = false;
        $position = $this->checkPosition($text, $this->needle);

        $start = $position - 30;
        $lenght = 80;

        if ($start < 0) {
            $start = 0;
            $lenght = $lenght - $start;
        }

        $text = substr($text, $start, $lenght);
        $position = $this->checkPosition($text, $this->needle);

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

        if ($this->emptyQuery) {
            $text = str_replace([$this->stringForEmptyQuery[0], $this->stringForEmptyQuery[1]], ['<em>' . $this->stringForEmptyQuery[0] . '</em>', '<em>' . $this->stringForEmptyQuery[1] . '</em>'], $text);
        }

        return $text;
    }

    private function conditionByLanguage($condition)
    {
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
            'nameRegular' => [
                'en' => '?NAME_EN',
                'ru' => '?NAME'
            ],
            'textRegular' => [
                'en' => '?TEXT_EN',
                'ru' => '?TEXT',
            ],
        ];

        return $conditionByLanguageArray[$condition][$this->language];
    }

    private function searchByTypos($typo, $findByField, $library = [])
    {
        $distance = -1;
        foreach ($library as $sentence) {
            $replaceSentence = str_replace(['.', ',', '"', "'", "«", "»", 'ЗАО ', 'ООО ', 'АО ', ''], [''], $sentence[$findByField]);
            $expression = levenshtein($typo, $replaceSentence);

            if ($expression == 0) {
                $result = $sentence[$findByField];
                $distance = 0;
                break;
            }

            if ($expression <= $distance || $distance < 0) {
                foreach (['ALIAS', 'FILE_ID'] as $fieldForLink) {
                    if (array_key_exists($fieldForLink, $sentence)) {
                        $this->linkForEmptyRequest = $sentence[$fieldForLink];
                    }
                }

                $result = $sentence[$findByField];
                $distance = $expression;
            }
        }

        if ($distance != 0 && $distance <= 5) {
            $textOutput = $this->language == 'ru' ? 'Возможно вы имели в виду:' : 'Maybe you mean:';
            return "$textOutput $result?\n";
        }
    }

    private function checkPosition($text, $needle)
    {
        $this->stringForEmptyQuery = explode(' ', $needle);
        $position = stripos($text, $needle);

        if (!$position) {
           $position = $this->checkRegister($needle, $text);
        }

        if (!$position) {
            $this->emptyQuery = true;
            $position = stripos($text, $this->stringForEmptyQuery[1] . ' ' . $this->stringForEmptyQuery[0]);

            if (!$position) {
                $position = stripos($text, $this->stringForEmptyQuery[0]) ?: $position = stripos($text, $this->stringForEmptyQuery[1]);
            }
        }

        return $position;
    }

    private function checkRegister($needle, $text)
    {
        $arrayNeedle = explode(' ', $needle);
        foreach ($arrayNeedle as $word) {
            if (ctype_lower($word)) {
                $word = ucfirst($word);
                $sentence .= ' ' . $word;
            }
        }
        return stripos($text, $sentence);
    }
}
