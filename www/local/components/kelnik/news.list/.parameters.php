<?php
if (!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Kelnik\News\Categories\CategoriesTable;
use Kelnik\News\News\NewsTable;

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !\Bitrix\Main\Loader::includeModule('kelnik.news')
) {
    return false;
}

Loc::loadMessages(__FILE__);

try
{
    $sortOrders = [
        'ASC' => Loc::getMessage('KELNIK_NEWS_LIST_SORT_ORDER_ASC'),
        'DESC' => Loc::getMessage('KELNIK_NEWS_LIST_SORT_ORDER_DESC')
    ];

    $sortFields = [
        'DATE_SHOW' => Loc::getMessage('KELNIK_NEWS_LIST_SORT_DATE_SHOW'),
        'ID'        => Loc::getMessage('KELNIK_NEWS_LIST_SORT_ID')
    ];

    $arComponentParameters = [
        'GROUPS' => [
            'AJAX' => [
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_GROUP_AJAX')
            ],
            'SEO' => [
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_GROUP_SEO')
            ],
            'OTHERS' => [
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_GROUP_OTHERS')
            ]
        ],
        'PARAMETERS' => [
            'SECTION_ID' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_SECTION_ID'),
                'TYPE' => 'LIST',
                'VALUES' => CategoriesTable::getComponentList()
            ],
            'OBJECT_ID' => [],
            'SECTION_CODE' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_SECTION_CODE'),
                'TYPE' => 'STRING'
            ],
            'SORT_BY_1' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_SORT_BY_1'),
                'TYPE' => 'LIST',
                'VALUES' => $sortFields
            ],
            'SORT_ORDER_1' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_SORT_ORDER_1'),
                'TYPE' => 'LIST',
                'VALUES' => $sortOrders
            ],
            'SORT_BY_2' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_SORT_BY_2'),
                'TYPE' => 'LIST',
                'VALUES' => $sortFields
            ],
            'SORT_ORDER_2' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_SORT_ORDER_2'),
                'TYPE' => 'LIST',
                'VALUES' => $sortOrders
            ],
            // 'RESULT_PROCESSING_MODE' => [
            //     'PARENT' => 'BASE',
            //     'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_RESULT_PROCESSING_MODE'),
            //     'TYPE' => 'LIST',
            //     'VALUES' => [
            //         'DEFAULT' => Loc::getMessage('KELNIK_NEWS_LIST_RESULT_PROCESSING_MODE_DEFAULT'),
            //         'EXTENDED' => Loc::getMessage('KELNIK_NEWS_LIST_RESULT_PROCESSING_MODE_EXTENDED')
            //     ]
            // ],
            // 'PAGER_SAVE_SESSION' => [
            //     'PARENT' => 'PAGER_SETTINGS',
            //     'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_NAV_SAVE_SESSION'),
            //     'TYPE' => 'CHECKBOX',
            //     'DEFAULT' => 'N'
            // ],
            'ELEMENTS_COUNT' => [
                'PARENT' => 'PAGER_SETTINGS',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_ELEMENTS_COUNT'),
                'TYPE' => 'STRING',
                'DEFAULT' => NewsTable::ITEMS_ON_PAGE
            ],
            'USE_AJAX' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_USE_AJAX'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'Y'
            ],
            'AJAX_TYPE' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_AJAX_TYPE'),
                'TYPE' => 'LIST',
                'VALUES' => [
                    'DEFAULT' => Loc::getMessage('KELNIK_NEWS_LIST_AJAX_TYPE_DEFAULT'),
                    'JSON' => Loc::getMessage('KELNIK_NEWS_LIST_AJAX_TYPE_JSON')
                ]
            ],
            // 'AJAX_HEAD_RELOAD' => [
            //     'PARENT' => 'AJAX',
            //     'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_AJAX_HEAD_RELOAD'),
            //     'TYPE' => 'CHECKBOX',
            //     'DEFAULT' => 'N'
            // ],
            'AJAX_TEMPLATE_PAGE' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_AJAX_TEMPLATE_PAGE'),
                'TYPE' => 'STRING',
                'DEFAULT' => ''
            ],
            'AJAX_COMPONENT_ID' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_AJAX_COMPONENT_ID'),
                'TYPE' => 'STRING',
                'DEFAULT' => ''
            ],
            'SET_SEO_TAGS' => [
                'PARENT' => 'SEO',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_SET_SEO_TAGS'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'Y'
            ],
            'SET_404' => [
                'PARENT' => 'OTHERS',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_SET_404'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ],
            'CACHE_GROUPS' => [
                'PARENT' => 'CACHE_SETTINGS',
                'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_CACHE_GROUPS'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ],
            'CACHE_TIME' => [
                'DEFAULT' => 360000
            ]
        ]
    ];

    if (!\Bitrix\Main\Loader::includeModule('kelnik.estate')) {
        unset($arComponentParameters['PARAMETERS']['OBJECT_ID']);
        return;
    }

    $arComponentParameters['PARAMETERS']['OBJECT_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('KELNIK_NEWS_LIST_OBJECT_ID'),
        'TYPE' => 'LIST',
        'VALUES' => array_merge(
            [
                0 => ''
            ],
            \Kelnik\Estate\Object\ObjectTable::getAdminAssocList()
        )
    ];
}
catch (Exception $e)
{
    ShowError($e->getMessage());
}
