<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('kelnik.admin_helper') || !Loader::includeModule('kelnik.refbook')) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('kelnik.refbook') < 'R') {
    return [];
}

return [
    [
        "parent_menu" => "global_menu_content",
        "sort" => 220,
        "url" => \Kelnik\Refbook\Model\AdminInterface\PartnerListHelper::getUrl(),
        "text" => Loc::getMessage('KELNIK_REFBOOK_MODULE'),
        "title" => Loc::getMessage('KELNIK_REFBOOK_MODULE_TITLE'),
        'icon' => 'kelnik-admin-menu_icon',
        'page_icon' => 'kelnik-admin-menu_icon',
        "items_id" => "kelnik_refbook",
        "items" => [
            [
                'parent_menu' => 'kelnik_refbook',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REFBOOK_PARTNER_MENU'),
                'url' => \Kelnik\Refbook\Model\AdminInterface\PartnerListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Refbook\Model\AdminInterface\PartnerEditHelper::getUrl()
                ]
            ],
            [
                'parent_menu' => 'kelnik_refbook',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REFBOOK_RESIDENT_MENU'),
                'url' => \Kelnik\Refbook\Model\AdminInterface\ResidentListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Refbook\Model\AdminInterface\ResidentEditHelper::getUrl()
                ],
                "items_id" => "kelnik_refbook_residents",
                "items" => [
                    [
                        'parent_menu' => 'kelnik_refbook_residents',
                        'sort' => 180,
                        'text' => Loc::getMessage('KELNIK_REFBOOK_RESIDENT_LIST_MENU'),
                        'url' => \Kelnik\Refbook\Model\AdminInterface\ResidentListHelper::getUrl(),
                        'more_url' => [
                            \Kelnik\Refbook\Model\AdminInterface\ResidentEditHelper::getUrl()
                        ]
                    ],
                    [
                        'parent_menu' => 'kelnik_refbook_residents',
                        'sort' => 180,
                        'text' => Loc::getMessage('KELNIK_REFBOOK_RESIDENT_TYPES_MENU'),
                        'url' => \Kelnik\Refbook\Model\AdminInterface\ResidentTypesListHelper::getUrl(),
                        'more_url' => [
                            \Kelnik\Refbook\Model\AdminInterface\ResidentTypesEditHelper::getUrl()
                        ]
                    ]
                ]
            ],
            [
                'parent_menu' => 'kelnik_refbook',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REFBOOK_REVIEW_MENU'),
                'url' => \Kelnik\Refbook\Model\AdminInterface\ReviewListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Refbook\Model\AdminInterface\ReviewEditHelper::getUrl()
                ]
            ],
            [
                'parent_menu' => 'kelnik_refbook',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REFBOOK_TEAM_MENU'),
                'url' => \Kelnik\Refbook\Model\AdminInterface\TeamListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Refbook\Model\AdminInterface\TeamEditHelper::getUrl()
                ]
            ],
            [
                'parent_menu' => 'kelnik_refbook',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REFBOOK_DOCS_MENU'),
                'url' => \Kelnik\Refbook\Model\AdminInterface\DocsListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Refbook\Model\AdminInterface\DocsEditHelper::getUrl()
                ]
            ],
            [
                'parent_menu' => 'kelnik_refbook',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REFBOOK_STRATEGY_DOCS_MENU'),
                'url' => \Kelnik\Refbook\Model\AdminInterface\StrategyDocsListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Refbook\Model\AdminInterface\StrategyDocsEditHelper::getUrl()
                ]
            ],
            [
                'parent_menu' => 'kelnik_refbook',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REFBOOK_PRES_MENU'),
                'url' => \Kelnik\Refbook\Model\AdminInterface\PresListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Refbook\Model\AdminInterface\PresEditHelper::getUrl()
                ]
            ]
        ]
    ]
];
