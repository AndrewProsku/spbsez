<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kelnik\Multisites\Settings\AdminInterface\SitesListHelper;
use Kelnik\Multisites\Settings\AdminInterface\SitesEditHelper;

if (
    !Loader::includeModule('kelnik.admin_helper')
    || !Loader::includeModule('kelnik.multisites')
) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('kelnik.multisites') < 'R') {
    return [];
}

return [
    [
        'parent_menu' => 'global_menu_content',
        'sort' => 220,
        'icon' => 'kelnik-admin-menu_icon',
        'page_icon' => 'kelnik-admin-menu_icon',
        'text' => Loc::getMessage('KELNIK_MULTISITES'),
        'url' => SitesListHelper::getUrl(),
        'more_url' => [
            SitesEditHelper::getUrl(),
        ]
    ],
];
