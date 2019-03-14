<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kelnik\Requests\Model\AdminInterface\AreaEditHelper;
use Kelnik\Requests\Model\AdminInterface\AreaListHelper;
use Kelnik\Requests\Model\AdminInterface\ServiceEditHelper;
use Kelnik\Requests\Model\AdminInterface\ServiceListHelper;
use Kelnik\Requests\Model\AdminInterface\SiteMsgListHelper;
use Kelnik\Requests\Model\AdminInterface\SiteMsgEditHelper;
use Kelnik\Requests\Model\AdminInterface\StandartListHelper;
use Kelnik\Requests\Model\AdminInterface\StandartEditHelper;
use Kelnik\Requests\Model\AdminInterface\StatusEditHelper;
use Kelnik\Requests\Model\AdminInterface\StatusListHelper;
use Kelnik\Requests\Model\AdminInterface\TypeEditHelper;
use Kelnik\Requests\Model\AdminInterface\TypeListHelper;

if (!Loader::includeModule('kelnik.admin_helper')
    || !Loader::includeModule('kelnik.requests')
) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('kelnik.requests') < 'R') {
    return [];
}

return [
    [
        "parent_menu" => "global_menu_content",
        "sort"        => 240,
        "url"         => StandartListHelper::getUrl(),
        "text"        => Loc::getMessage('KELNIK_REQ_MODULE'),
        "title"       => Loc::getMessage('KELNIK_REQ_MODULE_TITLE'),
        "icon"        => "kelnik-admin-menu_icon",
        "page_icon"   => "kelnik-admin-menu_icon",
        "items_id"    => "kelnik_requests",
        "items"       =>
        [
            [
                'parent_menu' => 'global_menu_content',
                'sort' => 100,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REQ_STANDART'),
                'url' => StandartListHelper::getUrl(),
                'more_url' => [
                    StandartEditHelper::getUrl(),
                ]
            ],
            [
                'parent_menu' => 'global_menu_content',
                'sort' => 100,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REQ_SITEMSG'),
                'url' => SiteMsgListHelper::getUrl(),
                'more_url' => [
                    SiteMsgEditHelper::getUrl(),
                ]
            ],
            [
                'parent_menu' => 'global_menu_content',
                'sort' => 100,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REQ_SERVICE'),
                'url' => ServiceListHelper::getUrl(),
                'more_url' => [
                    ServiceEditHelper::getUrl(),
                ]
            ],
            [
                'parent_menu' => 'global_menu_content',
                'sort' => 100,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REQ_TYPES'),
                'url' => TypeListHelper::getUrl(),
                'more_url' => [
                    TypeEditHelper::getUrl(),
                ]
            ],
            [
                'parent_menu' => 'global_menu_content',
                'sort' => 100,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REQ_AREAS'),
                'url' => AreaListHelper::getUrl(),
                'more_url' => [
                    AreaEditHelper::getUrl(),
                ]
            ],
            [
                'parent_menu' => 'global_menu_content',
                'sort' => 100,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REQ_STATUSES'),
                'url' => StatusListHelper::getUrl(),
                'more_url' => [
                    StatusEditHelper::getUrl(),
                ]
            ]
        ]
    ]
];
