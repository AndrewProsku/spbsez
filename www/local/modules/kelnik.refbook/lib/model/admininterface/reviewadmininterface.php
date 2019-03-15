<?php

namespace Kelnik\Refbook\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\FileWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\SlugWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\Refbook\Model\ReviewTable;

Loc::loadMessages(__FILE__);

class ReviewAdminInterface extends AdminInterface
{
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'MAIN' => [
                'NAME'   => Loc::getMessage('KELNIK_FIELDS_MAIN'),
                'FIELDS' => [
                    'ID'          => [
                        'WIDGET'           => new NumberWidget(),
                        'READONLY'         => true,
                        'FILTER'           => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'NAME'        => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => true
                    ],
                    'ALIAS'       => [
                        'WIDGET'       => new SlugWidget(),
                        'SIZE'         => 40,
                        'FILTER'       => false,
                        'HEADER'       => false,
                        'REQUIRED'     => true,
                        'LINKED_FIELD' => 'NAME'
                    ],
                    'ACTIVE'      => [
                        'WIDGET'   => new CheckboxWidget(),
                        'DEFAULT'  => ReviewTable::YES
                    ],
                    'SORT'        => [
                        'WIDGET'  => new NumberWidget(),
                        'DEFAULT' => 500,
                    ],
                    'IMAGE_ID'    => [
                        'WIDGET' => new FileWidget(),
                        'IMAGE'  => true,
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'IMAGE_BG_ID' => [
                        'WIDGET' => new FileWidget(),
                        'IMAGE'  => true,
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'COMMENT'     => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false,
                        'SIZE'   => 80
                    ],
                    'PREVIEW'     => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'BODY'        => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ]
                ],
            ],
            'MAIN_EN' => [
                'NAME'   => Loc::getMessage('KELNIK_FIELDS_MAIN_EN'),
                'FIELDS' => [
                    'NAME_EN'        => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => true
                    ],
                    'COMMENT_EN'     => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false,
                        'SIZE'   => 80
                    ],
                    'PREVIEW_EN'     => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'BODY_EN'        => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ]
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function helpers()
    {
        return [
            '\Kelnik\Refbook\Model\AdminInterface\ReviewListHelper',
            '\Kelnik\Refbook\Model\AdminInterface\ReviewEditHelper',
        ];
    }
}
