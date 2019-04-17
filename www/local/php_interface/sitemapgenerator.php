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
        return file_put_contents($filePath ? $filePath : $_SERVER['DOCUMENT_ROOT'] . $this->settings['FILENAME_INDEX'], $this->map);
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
        foreach ($this->paths as $path) {
            if (empty($path['DATA']['ABS_NAME'])) {
                continue;
            }

            if (false !== strpos($path['DATA']['ABS_NAME'], 'infrastructure')) {

                $path = explode('/', trim($path['DATA']['ABS_NAME'], '/'));
                $lang = array_shift($path);

                $this->getModuleData('intrastructure', $lang);

                continue;
            }
        }
    }

    protected function getModuleData($moduleName, $lang = 'ru')
    {
        $lang = strtolower($lang);
        $moduleName = strtolower($moduleName);
        $cacheKey = $moduleName . '_' . $lang;

        if (isset($this->dataCache[$cacheKey])) {
            return $this->dataCache[$cacheKey];
        }

        $tableClasses = [
            'infrastructure' => [
                'class' => \Kelnik\Infrastructure\Model\PlatformTable::class
            ],
            'news' => [
                'class' => \Kelnik\News\News\NewsTable::class,
                'cat' => [
                    'ru' => \Kelnik\News\Categories\CategoriesTable::NEWS_RU,
                    'en' => \Kelnik\News\Categories\CategoriesTable::NEWS_EN
                ]
            ],
            'articles' => [
                'class' => \Kelnik\News\News\NewsTable::class,
                'cat' => [
                    'ru' => \Kelnik\News\Categories\CategoriesTable::ARTICLES_RU,
                    'en' => \Kelnik\News\Categories\CategoriesTable::ARTICLES_EN
                ]
            ]
        ];

        $table = \Kelnik\Helpers\ArrayHelper::getValue($tableClasses, $moduleName, []);

        if (!$table) {
            return [];
        }

        $className = $table['class'];
        $select = ['ID', 'ALIAS'];
        $filter = [
            '=ACTIVE' => $className::YES
        ];

        if (isset($table['cat'])) {
            $filter['=CAT_ID'] = $table['cat'][$lang];
        }

        $this->dataCache[$cacheKey] = $className::getList([
            'select' => $select,
            'filter' => $filter
        ])->fetchAll();

        return $this->dataCache[$cacheKey];
    }

    protected function scanDirs($siteId, $logical, $paths)
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
                $this->scanDirs($siteId, $logical, $dir);
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
            $this->map .= '<url>';
            $this->map .= '<loc>' . $this->getBaseUrl() . ltrim($path['DATA']['ABS_PATH'], '/') . '</loc>';
            $this->map .= '<lastmod>' . date('Y-m-d', strtotime($path['DATA']['DATE'])). '</lastmod>';
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
}
