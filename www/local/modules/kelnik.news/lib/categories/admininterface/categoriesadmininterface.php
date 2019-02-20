<?php

namespace Kelnik\News\Categories\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\SlugWidget;

Loc::loadMessages(__FILE__);

class CategoriesAdminInterface extends AdminInterface
{
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'MAIN' => [
                'NAME' => Loc::getMessage('KELNIK_NEWS_CAT_TAB_MAIN'),
                'FIELDS' => [
                    'ID' => [
                        'WIDGET' => new NumberWidget(),
                        //'READONLY' => true,
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true,
                    ],
                    'NAME' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '60',
                        'FILTER' => '%',
                        'REQUIRED' => true,
                        'EDIT_LINK' => true
                    ],
                    'CODE' => [
                        'WIDGET' => new SlugWidget(),
                        'SIZE' => '60',
                        'FILTER' => '%',
                        'REQUIRED' => true,
                        'UNIQUE' => true,
                        'LINKED_FIELD' => 'NAME'
                    ],
                    'ACTIVE' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => true
                    ],
//                    'DATE_CREATE' => array(
//                        'WIDGET' => new DateTimeWidget(),
//                        'READONLY' => true,
//                        'HIDE_WHEN_CREATE' => true
//                    ),
//                    'CREATED_BY' => array(
//                        'WIDGET' => new UserWidget(),
//                        'READONLY' => true,
//                        'HIDE_WHEN_CREATE' => true
//                    ),
//                    'MODIFIED_BY' => array(
//                        'WIDGET' => new UserWidget(),
//                        'READONLY' => true,
//                        'HIDE_WHEN_CREATE' => true
//                    ),
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function helpers()
    {
        return [
            '\Kelnik\News\Categories\AdminInterface\CategoriesListHelper',
            '\Kelnik\News\Categories\AdminInterface\CategoriesEditHelper'
        ];
    }
}
