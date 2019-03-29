<?php
if (!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true) {
    die();
}

use Bitrix\Main\Localization\Loc;

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !\Bitrix\Main\Loader::includeModule('kelnik.infrastructure')
) {
    return false;
}

Loc::loadMessages(__FILE__);

try
{
    $sortOrders = [
        'ASC' => Loc::getMessage('KELNIK_INFRA_LIST_SORT_ORDER_ASC'),
        'DESC' => Loc::getMessage('KELNIK_INFRA_LIST_SORT_ORDER_DESC')
    ];

    $sortFields = [
        'DATE_SHOW' => Loc::getMessage('KELNIK_INFRA_LIST_SORT_DATE_SHOW'),
        'ID'        => Loc::getMessage('KELNIK_INFRA_LIST_SORT_ID')
    ];

    $arComponentParameters = [
        'GROUPS' => [
            'AJAX' => [
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_GROUP_AJAX')
            ],
            'SEO' => [
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_GROUP_SEO')
            ],
            'OTHERS' => [
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_GROUP_OTHERS')
            ]
        ],
        'PARAMETERS' => [
            'SORT_BY_1' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_SORT_BY_1'),
                'TYPE' => 'LIST',
                'VALUES' => $sortFields
            ],
            'SORT_ORDER_1' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_SORT_ORDER_1'),
                'TYPE' => 'LIST',
                'VALUES' => $sortOrders
            ],
            'SORT_BY_2' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_SORT_BY_2'),
                'TYPE' => 'LIST',
                'VALUES' => $sortFields
            ],
            'SORT_ORDER_2' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_SORT_ORDER_2'),
                'TYPE' => 'LIST',
                'VALUES' => $sortOrders
            ],
            'USE_AJAX' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_USE_AJAX'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'Y'
            ],
            'AJAX_TYPE' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_AJAX_TYPE'),
                'TYPE' => 'LIST',
                'VALUES' => [
                    'DEFAULT' => Loc::getMessage('KELNIK_INFRA_LIST_AJAX_TYPE_DEFAULT'),
                    'JSON' => Loc::getMessage('KELNIK_INFRA_LIST_AJAX_TYPE_JSON')
                ]
            ],
            'AJAX_TEMPLATE_PAGE' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_AJAX_TEMPLATE_PAGE'),
                'TYPE' => 'STRING',
                'DEFAULT' => ''
            ],
            'AJAX_COMPONENT_ID' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_AJAX_COMPONENT_ID'),
                'TYPE' => 'STRING',
                'DEFAULT' => ''
            ],
            'SET_SEO_TAGS' => [
                'PARENT' => 'SEO',
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_SET_SEO_TAGS'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'Y'
            ],
            'SET_404' => [
                'PARENT' => 'OTHERS',
                'NAME' => Loc::getMessage('KELNIK_INFRA_LIST_SET_404'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ],
            'CACHE_TIME' => [
                'DEFAULT' => 3600
            ]
        ]
    ];
}
catch (Exception $e)
{
    ShowError($e->getMessage());
}
