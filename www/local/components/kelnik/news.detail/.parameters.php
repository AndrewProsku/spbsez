<?php

use Bitrix\Main\Localization\Loc;
use Kelnik\News\Categories\CategoriesTable;

if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')
    || !\Bitrix\Main\Loader::includeModule('kelnik.news')
) {
    return false;
}

Loc::loadMessages(__FILE__);

try
{
    $sections = CategoriesTable::getAdminAssocList();

    $ogTagsFields = [
        'TITLE' => [
            'NAME' => Loc::getMessage('KELNIK_NEWS_DETAIL_ELEMENT_NAME'),
            'SEO_TITLE' => Loc::getMessage('KELNIK_NEWS_DETAIL_PAGE_TITLE')
        ],
        'DESCRIPTION' => [
            'PREVIEW_TEXT' => Loc::getMessage('KELNIK_NEWS_DETAIL_PREVIEW_TEXT'),
            'DETAIL_TEXT' => Loc::getMessage('KELNIK_NEWS_DETAIL_DETAIL_TEXT'),
            'SEO_DESCRIPTION' => Loc::getMessage('KELNIK_NEWS_DETAIL_PAGE_DESCRIPTION')
        ],
        'IMAGE' => [
            'PREVIEW_PICTURE' => Loc::getMessage('KELNIK_NEWS_DETAIL_PREVIEW_PICTURE'),
            'DETAIL_PICTURE' => Loc::getMessage('KELNIK_NEWS_DETAIL_DETAIIL_PICTURE')
        ],
        'URL' => [
            'SHORT_LINK' => Loc::getMessage('KELNIK_NEWS_DETAIL_SHORT_LINK')
        ]
    ];

    $arComponentParameters = [
        'GROUPS' => [
            'OTHERS' => [
                'NAME' => Loc::getMessage('KELNIK_NEWS_DETAIL_GROUP_OTHERS')
            ],
            'SEO' => [
                'NAME' => Loc::getMessage('KELNIK_NEWS_DETAIL_GROUP_SEO')
            ],
        ],
        'PARAMETERS' => [
            'SECTION_ID' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_NEWS_DETAIL_SECTION_ID'),
                'TYPE' => 'LIST',
                'VALUES' => CategoriesTable::getComponentList()
            ],
            'ELEMENT_ID' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_NEWS_DETAIL_ELEMENT_ID'),
                'TYPE' => 'string'
            ],
            'ELEMENT_CODE' => [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('KELNIK_NEWS_DETAIL_ELEMENT_CODE'),
                'TYPE' => 'string'
            ],
            'SET_SEO_TAGS' => [
                'PARENT' => 'SEO',
                'NAME' => Loc::getMessage('KELNIK_NEWS_DETAIL_SET_SEO_TAGS'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'Y'
            ],
            'SET_404' => [
                'PARENT' => 'OTHERS',
                'NAME' => Loc::getMessage('KELNIK_NEWS_DETAIL_SET_404'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ],
            'CACHE_GROUPS' => [
                'PARENT' => 'CACHE_SETTINGS',
                'NAME' => Loc::getMessage('KELNIK_NEWS_DETAIL_CACHE_GROUPS'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ],
            'CACHE_TIME' => [
                'DEFAULT' => 360000
            ]
        ]
    ];
}
catch (Exception $e)
{
    ShowError($e->getMessage());
}
