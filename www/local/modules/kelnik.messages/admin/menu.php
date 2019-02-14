<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('kelnik.admin_helper')
    || !Loader::includeModule('kelnik.messages')
) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('kelnik.messages') < 'R') {
    return [];
}

return [
    [
        "parent_menu" => "global_menu_content",
        "sort" => 220,
        "url" => \Kelnik\Messages\Model\AdminInterface\MessagesListHelper::getUrl(),
        "text" => Loc::getMessage('KELNIK_MESSAGES_MODULE'),
        "title" => Loc::getMessage('KELNIK_MESSAGES_MODULE_TITLE'),
        'icon' => 'kelnik-admin-menu_icon',
        'page_icon' => 'kelnik-admin-menu_icon',
        "items_id" => "kelnik_vacancy",
        "items" => [
            [
                'parent_menu' => 'kelnik_vacancy',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_MESSAGES_LIST_MENU'),
                'url' => \Kelnik\Messages\Model\AdminInterface\MessagesListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Messages\Model\AdminInterface\MessagesEditHelper::getUrl()
                ]
            ],
        ]
    ]
];
