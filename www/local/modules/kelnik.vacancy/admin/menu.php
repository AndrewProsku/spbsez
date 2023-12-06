<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('kelnik.admin_helper')
    || !Loader::includeModule('kelnik.vacancy')
) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('kelnik.vacancy') < 'R') {
    return [];
}

return [
    [
        "parent_menu" => "global_menu_content",
        "sort" => 220,
        "url" => \Kelnik\Vacancy\Model\AdminInterface\VacancyListHelper::getUrl(),
        "text" => Loc::getMessage('KELNIK_VACANCY_MODULE'),
        "title" => Loc::getMessage('KELNIK_VACANCY_MODULE_TITLE'),
        'icon' => 'kelnik-admin-menu_icon',
        'page_icon' => 'kelnik-admin-menu_icon',
        "items_id" => "kelnik_vacancy",
        "items" => [
            [
                'parent_menu' => 'kelnik_vacancy',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_VACANCY_LIST_MENU'),
                'url' => \Kelnik\Vacancy\Model\AdminInterface\VacancyListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Vacancy\Model\AdminInterface\VacancyEditHelper::getUrl()
                ]
            ],
            [
                'parent_menu' => 'kelnik_vacancy',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_VACANCY_RESPONSE_MENU'),
                'url' => \Kelnik\Vacancy\Model\AdminInterface\ResponseListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Vacancy\Model\AdminInterface\ResponseEditHelper::getUrl()
                ]
            ],
            [
                'parent_menu' => 'kelnik_vacancy',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_VACANCY_RESIDENTS'),
                'url' => \Kelnik\Vacancy\Model\AdminInterface\VacancyResidentsListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Vacancy\Model\AdminInterface\VacancyResidentsEditHelper::getUrl()
                ]
            ],
        ]
    ]
];
