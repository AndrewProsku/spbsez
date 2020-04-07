<?php
if (!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true) {
    die();
}

use Bitrix\Main\Localization\Loc;

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !\Bitrix\Main\Loader::includeModule('kelnik.info')
) {
    return false;
}

Loc::loadMessages(__FILE__);

try
{
    $arComponentParameters = [
        'GROUPS' => [
            'AJAX' => [
                'NAME' => Loc::getMessage('KELNIK_INFO_LIST_GROUP_AJAX')
            ]
        ],
        'PARAMETERS' => [
            'SECTION' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_INFO_SECTION'),
                'TYPE' => 'LIST',
                'VALUES' => \Kelnik\Info\Model\TypesTable::getAdminAssocList()
            ],
            'YEAR' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_INFO_YEAR'),
                'TYPE' => 'LIST',
                'VALUES' => \Kelnik\Info\Model\DocsTable::getYearsList(),
                'ADDITIONAL_VALUES' => 'Y'
            ],
            'SHOW_FILTER' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_INFO_SHOW_FILTER'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ],
            'USE_AJAX' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_INFO_LIST_USE_AJAX'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'Y'
            ],
            'AJAX_TYPE' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_INFO_LIST_AJAX_TYPE'),
                'TYPE' => 'LIST',
                'VALUES' => [
                    'DEFAULT' => Loc::getMessage('KELNIK_INFO_LIST_AJAX_TYPE_DEFAULT'),
                    'JSON' => Loc::getMessage('KELNIK_INFO_LIST_AJAX_TYPE_JSON')
                ]
            ],
            'AJAX_TEMPLATE_PAGE' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_INFO_LIST_AJAX_TEMPLATE_PAGE'),
                'TYPE' => 'STRING',
                'DEFAULT' => ''
            ],
            'AJAX_COMPONENT_ID' => [
                'PARENT' => 'AJAX',
                'NAME' => Loc::getMessage('KELNIK_INFO_LIST_AJAX_COMPONENT_ID'),
                'TYPE' => 'STRING',
                'DEFAULT' => ''
            ],
            'CACHE_GROUPS' => [
                'PARENT' => 'CACHE_SETTINGS',
                'NAME' => Loc::getMessage('KELNIK_INFO_LIST_CACHE_GROUPS'),
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
