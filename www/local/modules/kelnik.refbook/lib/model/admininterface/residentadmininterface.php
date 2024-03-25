<?php

namespace Kelnik\Refbook\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\FileWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\Refbook\Model\ResidentTable;
use Kelnik\Refbook\Model\ResidentTypesTable;
use Kelnik\Refbook\DateTimeWidgetExt;

Loc::loadMessages(__FILE__);

class ResidentAdminInterface extends AdminInterface
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
                    'ID'       => [
                        'WIDGET'           => new NumberWidget(),
                        'READONLY'         => true,
                        'FILTER'           => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'ACTIVE'   => [
                        'WIDGET'   => new CheckboxWidget(),
                        'DEFAULT'  => ResidentTable::YES
                    ],
                    'TYPE_ID' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => ResidentTypesTable::getAdminAssocList(),
                        'DEFAULT' => 0,
                        'REQUIRED' => true
                    ],
                    'NAME'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => true
                    ],
                    'PLACE' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => ResidentTable::getPlaces()
                    ],
                    'SITE' => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'SORT'     => [
                        'WIDGET'  => new NumberWidget(),
                        'DEFAULT' => 500,
                        'FILTER' => false,
                        'SIZE' => 5
                    ],
                    'IMAGE_ID' => [
                        'WIDGET' => new FileWidget(),
                        'IMAGE' => true,
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'STATUS_DATE' => [
                        'WIDGET' => new DateTimeWidgetExt(),
                        'HEADER' => true,
                        'REQUIRED' => true
                    ],
                    'PHONE'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => false
                    ],
                    'ADDRESS'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 80,
                        'FILTER'   => '%',
                        'REQUIRED' => false
                    ],
                    'PROJECT_STAGE'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => false
                    ],
                    'TEXT' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'IMAGES' => [
                        'WIDGET' => new FileWidget(),
                        'IMAGE' => true,
                        'DESCRIPTION_FIELD' => true,
                        'HEADER' => false,
                        'MULTIPLE' => true,
                        'TITLE' => Loc::getMessage('KELNIK_FIELDS_IMAGES')
                    ]
                ]
            ],
            'MAIN_EN' => [
                'NAME'   => Loc::getMessage('KELNIK_FIELDS_MAIN_EN'),
                'FIELDS' => [
                    'NAME_EN'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => true
                    ],
                    'IMAGE_ID_EN' => [
                        'WIDGET' => new FileWidget(),
                        'IMAGE' => true,
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'ADDRESS_EN'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 80,
                        'FILTER'   => '%',
                        'REQUIRED' => false
                    ],
                    'PROJECT_STAGE_EN' => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => false
                    ],
                    'TEXT_EN' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
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
            '\Kelnik\Refbook\Model\AdminInterface\ResidentListHelper',
            '\Kelnik\Refbook\Model\AdminInterface\ResidentEditHelper',
        ];
    }
}
