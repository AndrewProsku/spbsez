<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kelnik\News\Categories\AdminInterface\CategoriesEditHelper;
use Kelnik\News\Categories\AdminInterface\CategoriesListHelper;
use Kelnik\News\News\AdminInterface\NewsEditHelper;
use Kelnik\News\News\AdminInterface\NewsListHelper;
use Kelnik\News\News\AdminInterface\TagsEditHelper;
use Kelnik\News\News\AdminInterface\TagsListHelper;

if (
    !Loader::includeModule('kelnik.admin_helper')
    || !Loader::includeModule('kelnik.news')
) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('kelnik.news') < 'R') {
    return [];
}

$categories = Kelnik\News\Categories\CategoriesTable::getList([
    'select' => [
        'ID',
        'NAME'
    ],
    'order' => [
        'NAME' => 'ASC'
    ]
])->fetchAll();

$newsByCat = [];

if ($categories) {
    foreach ($categories as $cat) {
        $url = NewsListHelper::getUrl([
            'set_filter' => 'Y',
            'adm_filter_applied' => 0,
            'find_CAT_ID' => $cat['ID']
        ]);

        $newsByCat[] = [
            'parent_menu' => 'menu_kelnik_news_list',
            'sort' => 100,
            'icon' => 'fileman_menu_icon_sections',
            'page_icon' => 'fileman_menu_icon_sections',
            'text' => $cat['NAME'],
            'url' => $url
        ];
    }
}

return [
    [
        'parent_menu' => 'global_menu_content',
        'sort' => 220,
        'url' => CategoriesListHelper::getUrl(),
        'text' => Loc::getMessage('KELNIK_NEWS_MODULE'),
        'title' => Loc::getMessage('KELNIK_NEWS_MODULE_TITLE'),
        'icon'        => 'kelnik-admin-menu_icon',
        'page_icon'   => 'kelnik-admin-menu_icon',
        'items_id' => 'kelnik_news',
        'items' => [
            [
                'parent_menu' => 'global_menu_content',
                'sort' => 100,
                'icon' => 'fileman_menu_icon_sections',
                'page_icon' => 'fileman_menu_icon_sections',
                'text' => Loc::getMessage('KELNIK_NEWS_CAT'),
                'url' => CategoriesListHelper::getUrl(),
                'more_url' => [
                    CategoriesEditHelper::getUrl(),
                ]
            ],
            [
                'parent_menu' => 'global_menu_content',
                'sort' => 100,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_NEWS_TAGS'),
                'url' => TagsListHelper::getUrl(),
                'more_url' => [
                    TagsEditHelper::getUrl(),
                ]
            ],
            [
                'parent_menu' => 'global_menu_content',
                'sort' => 100,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_NEWS'),
                'url' => NewsListHelper::getUrl(),
                'more_url' => [
                    NewsEditHelper::getUrl(),
                ],
                'items_id' => 'menu_kelnik_news_list',
                'items' => $newsByCat
            ]
        ],
    ]
];
