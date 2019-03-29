<?php

namespace Kelnik\News\News\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\DateTimeWidget;
use Kelnik\AdminHelper\Widget\FileWidget;
use Kelnik\AdminHelper\Widget\MultiWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\AdminHelper\Widget\SlugWidget;
use Kelnik\News\Categories\CategoriesTable;
use Kelnik\News\News\TagsTable;

Loc::loadMessages(__FILE__);

class NewsAdminInterface extends AdminInterface
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
                    'CAT_ID' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'HEADER' => true,
                        'FILTER' => true,
                        'REQUIRED' => true,
                        'VARIANTS' => CategoriesTable::getAdminAssocList(),
                        'DEFAULT_VARIANT' => null,
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
                    'TAGS_LIST' => [
                        'WIDGET' => new MultiWidget(),
                        'FILTER' => false,
                        'VIRTUAL '=> true,
                        'READONLY' => true,
                        'FORCE_SELECT' => true,
                        'SEPARATOR' => ', ',
                        'TITLE' => Loc::getMessage('KELNIK_NEWS_TAGS')
                    ],
                    'DATE_SHOW' => [
                        'WIDGET' => new DateTimeWidget(),
                        'HEADER' => true
                    ],
                    'ACTIVE' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => true
                    ],
//                    'DATE_ACTION_START' => [
//                        'WIDGET' => new DateTimeWidget(),
//                        'HEADER' => false,
//                        'DEFAULT' => null
//                    ],
//                    'DATE_ACTION_FINISH' => [
//                        'WIDGET' => new DateTimeWidget(),
//                        'HEADER' => false,
//                        'DEFAULT' => null
//                    ],
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
                    'TAGS' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => TagsTable::getAdminAssocList(),
                        'MULTIPLE' => true,
                        'STYLE' => 'width:250px; height:200px',
                        'TITLE' => Loc::getMessage('KELNIK_NEWS_TAGS'),
                        'HEADER' => false
                    ]
                ]
            ],
            'PREVIEW' => [
                'NAME' => Loc::getMessage('KELNIK_NEWS_TAB_PREVIEW'),
                'FIELDS' => [
                    'IMAGE_PREVIEW' => [
                        'WIDGET' => new FileWidget(),
                        'IMAGE' => true,
                        'HEADER' => false
                    ],
                    'TEXT_PREVIEW' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false
                    ],
                ]
            ],
            'CONTENT' => [
                'NAME' => Loc::getMessage('KELNIK_NEWS_TAB_CONTENT'),
                'FIELDS' => [
                    'IMAGE' => [
                        'WIDGET' => new FileWidget(),
                        'IMAGE' => true,
                        'HEADER' => false
                    ],
                    'IMAGES' => [
                        'WIDGET' => new FileWidget(),
                        'IMAGE' => true,
                        'DESCRIPTION_FIELD' => true,
                        'HEADER' => false,
                        'MULTIPLE' => true,
                        'TITLE' => Loc::getMessage('KELNIK_NEWS_IMAGES')
                    ],
                    'TEXT' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false
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
            '\Kelnik\News\News\AdminInterface\NewsListHelper',
            '\Kelnik\News\News\AdminInterface\NewsEditHelper'
        ];
    }
}
