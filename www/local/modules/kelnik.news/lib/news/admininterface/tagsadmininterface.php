<?php

namespace Kelnik\News\News\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\News\News\TagsTable;

Loc::loadMessages(__FILE__);

class TagsAdminInterface extends AdminInterface
{
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'MAIN' => [
                'NAME' => Loc::getMessage('KELNIK_NEWS_TAB_MAIN'),
                'FIELDS' => [
                    'ID' => [
                        'WIDGET' => new NumberWidget(),
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true,
                    ],
                    'NAME' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 60,
                        'FILTER' => '%',
                        'REQUIRED' => true,
                        'EDIT_LINK' => true
                    ],
                    'SORT' => [
                        'WIDGET' => new NumberWidget(),
                        'DEFAULT' => TagsTable::SORT_DEFAULT,
                        'SIZE' => 5
                    ]
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
            TagsListHelper::class,
            TagsEditHelper::class
        ];
    }
}
