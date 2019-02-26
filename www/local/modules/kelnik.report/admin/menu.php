<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('kelnik.admin_helper')
    || !Loader::includeModule('kelnik.report')
) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('kelnik.report') < 'R') {
    return [];
}

return [
    [
        "parent_menu" => "global_menu_content",
        "sort" => 220,
        "url" => '',
        "text" => Loc::getMessage('KELNIK_REPORT_MODULE'),
        "title" => Loc::getMessage('KELNIK_REPORT_MODULE_TITLE'),
        'icon' => 'kelnik-admin-menu_icon',
        'page_icon' => 'kelnik-admin-menu_icon',
        "items_id" => "kelnik_report",
        "items" => [
            [
                'parent_menu' => 'kelnik_report',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REPORT_STATUS_MENU'),
                'url' => \Kelnik\Report\Model\AdminInterface\StatusListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Report\Model\AdminInterface\StatusEditHelper::getUrl()
                ]
            ]
        ]
    ]
];
