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

$cnt = \Kelnik\Report\Model\ReportsTable::getRow([
    'select' => [
        new \Bitrix\Main\ORM\Fields\ExpressionField(
            'CNT',
            'COUNT(%s)',
            'ID'
        )
    ],
    'filter' => [
        '=STATUS_ID' => \Kelnik\Report\Model\StatusTable::CHECKING
    ]
]);

$cnt = \Kelnik\Helpers\ArrayHelper::getValue($cnt, 'CNT', 0);
$cnt = $cnt ? ' (' . $cnt . ')' : null;

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
                'text' => Loc::getMessage('KELNIK_REPORT_REPORTS_MENU') . $cnt,
                'url' => \Kelnik\Report\Model\AdminInterface\ReportsListHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Report\Model\AdminInterface\ReportsEditHelper::getUrl()
                ]
            ],
            [
                'parent_menu' => 'kelnik_report',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REPORT_REPORTS_TREE_MENU'),
                'url' => \Kelnik\Report\Model\AdminInterface\ReportsTreeHelper::getUrl(),
                'more_url' => [
                    \Kelnik\Report\Model\AdminInterface\ReportsEditHelper::getUrl()
                ]
            ],
            [
                'parent_menu' => 'kelnik_report',
                'sort' => 180,
                'icon' => 'iblock_menu_icon',
                'page_icon' => 'iblock_menu_icon',
                'text' => Loc::getMessage('KELNIK_REPORT_REPORTS_EXPORT_MENU'),
                'url' => \Kelnik\Report\Model\AdminInterface\ReportsExportHelper::getUrl(),
            ],
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
