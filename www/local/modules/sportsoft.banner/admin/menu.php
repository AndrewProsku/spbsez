<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Sportsoft\Banner\Model\AdminInterface\BannerListHelper;
use Sportsoft\Banner\Model\AdminInterface\BannerEditHelper;
use Sportsoft\Banner\Model\AdminInterface\BannerPositionListHelper;
use Sportsoft\Banner\Model\AdminInterface\BannerPositionEditHelper;

if (!Loader::includeModule('kelnik.admin_helper')
    || !Loader::includeModule('sportsoft.banner')
) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('sportsoft.banner') < 'R') {
    return [];
}

return [
    'parent_menu' => 'global_menu_content',
    'sort' => 180,
    'icon' => 'iblock_menu_icon_types',
    'page_icon' => 'iblock_menu_icon_types',
    'text' => Loc::getMessage('SPORTSOFT_BANNER_MENU'),
    "items_id"    => 'sportsoft_banner',
    'url' => BannerListHelper::getUrl(),
    'more_url' => [
        BannerEditHelper::getUrl(),
    ],
    "items" => [
        [
            'parent_menu' => 'sportsoft_banner',
            'sort' => 180,
            'text' => Loc::getMessage('SPORTSOFT_BANNER_POSITION_MENU'),
            'url' => BannerPositionListHelper::getUrl(),
            'more_url' => [
                BannerPositionEditHelper::getUrl()
            ]
        ]
    ]
];
