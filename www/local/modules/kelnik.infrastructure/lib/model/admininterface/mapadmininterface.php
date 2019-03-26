<?php

namespace Kelnik\Infrastructure\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\FileWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\Infrastructure\Model\PlatformTable;

Loc::loadMessages(__FILE__);

class MapAdminInterface extends AdminInterface
{
    public function fields()
    {
        return [
            'MAIN' => [
                'NAME' => Loc::getMessage('KELNIK_INFRASTRUCTURE_TAB_MAIN'),
                'FIELDS' => [
                    'ID' => [
                        'WIDGET' => new NumberWidget(),
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true,
                    ],
                    'ACTIVE' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => true,
                        'FIELD_TYPE' => CheckboxWidget::TYPE_STRING
                    ],
                    'PLATFORM_ID' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => PlatformTable::getAdminAssocList(),
                        'REQUIRED' => true
                    ],
                    'NAME_RU' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'REQUIRED' => true,
                        'EDIT_LINK' => true
                    ],
                    'NAME_EN' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => false,
                        'REQUIRED' => true,
                        'EDIT_LINK' => true
                    ],
                    'MAP_COORDS_LAT' => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'MAP_COORDS_LNG' => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'MAKE_ROUTE' => [
                        'WIDGET' => new CheckboxWidget(),
                        'HEADER' => false,
                        'FILTER' => false,
                        'FIELD_TYPE' => CheckboxWidget::TYPE_STRING
                    ]
                ]
            ]
        ];
    }

    public function helpers()
    {
        return array(
            MapListHelper::class,
            MapEditHelper::class
        );
    }
}
