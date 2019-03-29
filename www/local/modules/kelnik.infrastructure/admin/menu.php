<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('kelnik.admin_helper')
    || !Loader::includeModule('kelnik.infrastructure')
) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('kelnik.infrastructure') < 'R') {
    return [];
}

return [
    'parent_menu' => 'global_menu_content',
    'sort' => 230,
    'icon' => 'kelnik-admin-menu_icon',
    'page_icon' => 'kelnik-admin-menu_icon',
    'text' => Loc::getMessage('KELNIK_INFRASTRUCTURE_MENU'),
    'items_id'    => 'kelnik_infrastructure',
    'url' => \Kelnik\Infrastructure\Model\AdminInterface\PlatformListHelper::getUrl(),
    'items' => [
        [
            'parent_menu' => 'kelnik_infrastructure',
            'sort' => 180,
            'icon' => 'iblock_menu_icon',
            'page_icon' => 'iblock_menu_icon',
            'text' => Loc::getMessage('KELNIK_INFRASTRUCTURE_MENU_PLATFORM'),
            'url' => \Kelnik\Infrastructure\Model\AdminInterface\PlatformListHelper::getUrl(),
            'more_url' => [
                \Kelnik\Infrastructure\Model\AdminInterface\PlatformEditHelper::getUrl()
            ]
        ],
        [
            'parent_menu' => 'kelnik_infrastructure',
            'sort' => 180,
            'icon' => 'iblock_menu_icon',
            'page_icon' => 'iblock_menu_icon',
            'text' => Loc::getMessage('KELNIK_INFRASTRUCTURE_MENU_MAP'),
            'url' => \Kelnik\Infrastructure\Model\AdminInterface\MapListHelper::getUrl(),
            'more_url' => [
                \Kelnik\Infrastructure\Model\AdminInterface\MapEditHelper::getUrl()
            ]
        ],
        [
            'parent_menu' => 'kelnik_infrastructure',
            'sort' => 180,
            'icon' => 'iblock_menu_icon',
            'page_icon' => 'iblock_menu_icon',
            'text' => Loc::getMessage('KELNIK_INFRASTRUCTURE_MENU_PLAN'),
            'url' => \Kelnik\Infrastructure\Model\AdminInterface\PlanListHelper::getUrl(),
            'more_url' => [
                \Kelnik\Infrastructure\Model\AdminInterface\PlanEditHelper::getUrl()
            ]
        ]
    ]
];
