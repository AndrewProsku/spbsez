<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('kelnik.admin_helper') || !Loader::includeModule('kelnik.info')) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('kelnik.info') < 'R') {
    return [];
}

return [
    [
        "parent_menu" => "global_menu_content",
        "sort" => 240,
        "url" => \Kelnik\Info\Model\AdminInterface\DocsListHelper::getUrl(),
        "text" => Loc::getMessage('KELNIK_INFO_MODULE'),
        "title" => Loc::getMessage('KELNIK_INFO_MODULE_TITLE'),
        'icon' => 'kelnik-admin-menu_icon',
        'page_icon' => 'kelnik-admin-menu_icon',
        "items_id" => "kelnik_info",
        "items" => [
            [
                'parent_menu' => 'kelnik_info',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_INFO_DOCS_MENU'),
                'url' => \Kelnik\Info\Model\AdminInterface\DocsListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Info\Model\AdminInterface\DocsEditHelper::getUrl()
                ]
            ],
            [
                'parent_menu' => 'kelnik_info',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_INFO_TYPES_MENU'),
                'url' => \Kelnik\Info\Model\AdminInterface\TypesListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Info\Model\AdminInterface\TypesEditHelper::getUrl()
                ]
            ],
            [
                'parent_menu' => 'kelnik_info',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_INFO_PROC_MENU'),
                'url' => \Kelnik\Info\Model\AdminInterface\ProcListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Info\Model\AdminInterface\ProcEditHelper::getUrl()
                ]
            ]
        ]
    ]
];
