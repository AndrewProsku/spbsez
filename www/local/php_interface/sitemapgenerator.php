<?php

class SiteMapGenerator
{
    use BitrixManagedCache;

    const TYPE_FILE = 'F';
    const TYPE_DIR = 'D';
    const YES = 'Y';

    private $modules = [
        'seo',
        'kelnik.news',
        'kelnik.infrastructure',
        'kelnik.helpers'
    ];

    /**
     * Настройки сайта для генерации карты.
     * Раздел в админке /bitrix/admin/seo_sitemap.php?lang=ru
     * Берется первый сайт из списка
     *
     * @var array
     */
    private $settings = [];

    /**
     * @var string
     */
    private $map = '';

    /**
     * @var array
     */
    private $paths = [];

    private $dataCache = [];

    public function __construct()
    {
        foreach ($this->modules as $module) {
            if (!\Bitrix\Main\Loader::includeModule($module)) {
               throw new \Bitrix\Main\LoaderException('Can\'t load module ' . $module);
            }
        }
    }

    public function run()
    {
        $this->loadData();
        $this->generateMap();
    }

    public function getMap()
    {
        return $this->map;
    }

    public function save($filePath = false)
    {
        return file_put_contents($filePath ? $filePath : $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $this->settings['FILENAME_INDEX'], $this->map);
    }

    protected function loadData(): bool
    {
        $cacheId = self::getCacheId([]);

        if ($cache = self::getCache($cacheId)) {
            $this->paths = \Kelnik\Helpers\ArrayHelper::getValue($cache, 'paths', []);
            $this->settings = \Kelnik\Helpers\ArrayHelper::getValue($cache, 'settings', []);

            return true;
        }

        $site = \Bitrix\Seo\SitemapTable::getRow([
            'select' => [
                'SITE_ID', 'NAME', 'SETTINGS'
            ],
            'filter' => [
                '=ACTIVE' => \Bitrix\Seo\SitemapTable::ACTIVE
            ]
        ]);

        if (!$site) {
            return false;
        }

        $this->settings = unserialize($site['SETTINGS']);
        $this->paths = ['/'];

        $this->scanDirs(
            $site['SITE_ID'],
            $this->settings['logical'] == self::YES,
            $this->paths
        );

        $this->loadModuleData();

        self::setCache(
            $cacheId,
            [
                'paths' => $this->paths,
                'settings' => $this->settings
            ]
        );

        return true;
    }

    protected function loadModuleData()
    {
        $sections = array_keys(self::getSections());

        foreach ($this->paths as $path) {
            if (empty($path['DATA']['ABS_PATH'])) {
                continue;
            }

            foreach ($sections as $section) {
                if (false === strpos($path['DATA']['ABS_PATH'], $section)) {
                    continue;
                }

                $data = $this->getModuleData(
                    $section,
                    self::getLangByPath($path['DATA']['ABS_PATH'])
                );

                if ($data) {
                    foreach ($data as $v) {
                        $modTime = time();
                        if (!empty($v['DATE_MODIFIED']) && $v['DATE_MODIFIED'] instanceof \Bitrix\Main\Type\DateTime) {
                            $modTime = $v['DATE_MODIFIED']->getTimestamp();
                        }

                        $this->paths[] = [
                            'TYPE' => 'F',
                            'DATA' => [
                                'ABS_PATH' => $path['DATA']['ABS_PATH'] . '/' . $v['ALIAS'] . '/',
                                'TYPE' => 'F',
                                'TIMESTAMP' => $modTime
                            ]
                        ];
                    }
                }

                continue 2;
            }
        }
    }

    /**
     * @param $moduleName
     * @param string $lang
     * @return array
     */
    protected function getModuleData($moduleName, $lang = 'ru')
    {
        $lang = strtolower($lang);
        $moduleName = strtolower($moduleName);
        $cacheKey = $moduleName . '_' . $lang;

        if (isset($this->dataCache[$cacheKey])) {
            return $this->dataCache[$cacheKey];
        }

        $sections = self::getSections();

        $section = \Kelnik\Helpers\ArrayHelper::getValue($sections, $moduleName, []);

        if (!$section) {
            return [];
        }

        $className = $section['class'];
        $select = $section['select'];
        $filter = [
            '=ACTIVE' => $className::YES
        ];

        if (isset($section['cat'])) {
            $filter['=CAT_ID'] = $section['cat'][$lang];
        }

        $this->dataCache[$cacheKey] = $className::getList([
            'select' => $select,
            'filter' => $filter
        ])->fetchAll();

        return $this->dataCache[$cacheKey];
    }

    /**
     * @param $siteId
     * @param $logical
     * @param array $paths
     */
    protected function scanDirs($siteId, $logical, array $paths)
    {
        foreach ($paths as $path) {
            $dirs = \CSeoUtils::getDirStructure(
                $logical,
                $siteId,
                $path
            );

            foreach ($dirs as $k => $v) {
                if ($v['DATA']['TYPE'] === self::TYPE_FILE && $v['DATA']['NAME'] !== 'index.php') {
                    continue;
                }

                if ($v['DATA']['TYPE'] === self::TYPE_DIR && $this->canScan($v['DATA']['ABS_PATH'])) {
                    continue;
                }

                unset($dirs[$k]);
            }

            if (!$dirs) {
                continue;
            }

            $this->paths = array_merge($this->paths, $dirs);

            foreach ($dirs as $dir) {
                if (empty($dir['DATA']['TYPE']) || $dir['DATA']['TYPE'] !== self::TYPE_DIR) {
                    continue;
                }
                $this->scanDirs($siteId, $logical, [$dir['DATA']['ABS_PATH']]);
            }
        }
    }

    protected function canScan($absPath)
    {
        $absPath = str_replace('//', '/', $absPath);

        if (!$absPath) {
            return true;
        }

        return \Kelnik\Helpers\ArrayHelper::getValue($this->settings, 'DIR.' . $absPath, 'Y') === self::YES;
    }

    protected function generateMap()
    {
        $this->map = '<?xml version="1.0" encoding="UTF-8"?>';
        $this->map .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($this->paths as $path) {
            $url = $this->getBaseUrl();

            if (!empty($path['DATA']['ABS_PATH'])) {
                $url .= ltrim($path['DATA']['ABS_PATH'], '/') .
                        ($path['DATA']['TYPE'] === self::TYPE_DIR ? '/' : '');
            }

            $this->map .= '<url>';
            $this->map .= '<loc>' . $url . '</loc>';
            if (!empty($path['DATA']['TIMESTAMP'])) {
                $this->map .= '<lastmod>' . date('Y-m-d', $path['DATA']['TIMESTAMP']) . '</lastmod>';
            }
            $this->map .= '<changefreq>monthly</changefreq>';
            $this->map .= '<priority>0.8</priority>';
            $this->map .= '</url>';
        }

        $this->map .= '</urlset>';
    }

    protected function getBaseUrl()
    {
        return ($this->settings['PROTO'] ? 'https' : 'http') . '://' . $this->settings['DOMAIN'] . '/';
    }

    protected static function getSections(): array
    {
        return [
            'infrastructure' => [
                'class' => \Kelnik\Infrastructure\Model\PlatformTable::class,
                'select' => ['ALIAS']
            ],
            'news' => [
                'class' => \Kelnik\News\News\NewsTable::class,
                'select' => ['ALIAS' => 'CODE', 'DATE_MODIFY'],
                'cat' => [
                    'ru' => \Kelnik\News\Categories\CategoriesTable::NEWS_RU,
                    'en' => \Kelnik\News\Categories\CategoriesTable::NEWS_EN
                ]
            ],
            'articles' => [
                'class' => \Kelnik\News\News\NewsTable::class,
                'select' => ['ALIAS' => 'CODE', 'DATE_MODIFY'],
                'cat' => [
                    'ru' => \Kelnik\News\Categories\CategoriesTable::ARTICLES_RU,
                    'en' => \Kelnik\News\Categories\CategoriesTable::ARTICLES_EN
                ]
            ]
        ];
    }

    protected static function getLangByPath($path)
    {
        $langs = [
            SezLang::ENGLISH_DIR => 'en',
            SezLang::CHINESE_DIR => 'ch'
        ];

        foreach ($langs as $k => $v) {
            if (0 === strpos($k, $path)) {
                return $v;
            }
        }

        return 'ru';
    }
}
