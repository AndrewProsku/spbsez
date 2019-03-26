<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kelnik\Userdata\Model\AdminInterface\ContactEditHelper;
use Kelnik\Userdata\Model\AdminInterface\ContactListHelper;
use Kelnik\Userdata\Model\AdminInterface\DocsEditHelper;
use Kelnik\Userdata\Model\AdminInterface\DocsListHelper;

if (!Loader::includeModule('kelnik.admin_helper')
    || !Loader::includeModule('kelnik.userdata')
) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('kelnik.userdata') < 'R') {
    return [];
}

return [
    [
        "parent_menu" => "global_menu_content",
        "sort"        => 220,
        "url"         => ContactListHelper::getUrl(),
        "text"        => Loc::getMessage('KELNIK_USERDATA_MODULE'),
        "title"       => Loc::getMessage('KELNIK_USERDATA_MODULE_TITLE'),
        'icon' => 'kelnik-admin-menu_icon',
        'page_icon' => 'kelnik-admin-menu_icon',
        "items_id"    => "kelnik_userdata",
        "items"       => [
            [
                'parent_menu' => 'kelnik_userdata',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_USERDATA_CONTACT_MENU'),
                'items_id' => 'kelinik_userdata_cont',
                'items' => [
                    [
                        'parent_menu' => 'kelnik_userdata_cont',
                        'sort' => 180,
                        'icon' => 'iblock_menu_icon',
                        'page_icon' => 'iblock_menu_icon',
                        'text' => Loc::getMessage('KELNIK_USERDATA_CONTACT_LIST_MENU'),
                        'url' => ContactListHelper::getUrl(),
                        'more_url' => [
                            ContactEditHelper::getUrl()
                        ]
                    ],
                    [
                        'parent_menu' => 'kelinik_userdata_cont',
                        'sort' => 180,
                        'icon' => 'iblock_menu_icon',
                        'page_icon' => 'iblock_menu_icon',
                        'text' => Loc::getMessage('KELNIK_USERDATA_CONTACT_TREE_MENU'),
                        'url' => \Kelnik\UserData\Model\AdminInterface\ContactTreeHelper::getUrl()
                    ]
                ]
            ],
            [
                'parent_menu' => 'kelnik_userdata',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_USERDATA_DOCS_MENU'),
                'items_id' => 'kelinik_userdata_docs',
                'items' => [
                    [
                        'parent_menu' => 'kelinik_userdata_docs',
                        'sort' => 180,
                        'icon' => 'iblock_menu_icon',
                        'page_icon' => 'iblock_menu_icon',
                        'text' => Loc::getMessage('KELNIK_USERDATA_DOCS_LIST_MENU'),
                        'url' => DocsListHelper::getUrl(),
                        'more_url' => [
                            DocsEditHelper::getUrl()
                        ]
                    ],
                    [
                        'parent_menu' => 'kelinik_userdata_docs',
                        'sort' => 180,
                        'icon' => 'iblock_menu_icon',
                        'page_icon' => 'iblock_menu_icon',
                        'text' => Loc::getMessage('KELNIK_USERDATA_DOCS_TREE_MENU'),
                        'url' => \Kelnik\UserData\Model\AdminInterface\DocsTreeHelper::getUrl()
                    ]
                ]
            ]
        ]
    ]
];
