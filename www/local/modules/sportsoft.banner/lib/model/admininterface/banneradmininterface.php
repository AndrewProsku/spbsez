<?php

namespace Sportsoft\Banner\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\ColorPickerWidget;
use Kelnik\AdminHelper\Widget\FileWidget;
use Sportsoft\Banner\Model\BannerPositionTable;

Loc::loadMessages(__FILE__);

class BannerAdminInterface extends AdminInterface
{
    public function fields()
    {
        return [
            'MAIN' => [
                'NAME' => Loc::getMessage('SPORTSOFT_BANNER_TAB_MAIN'),
                'FIELDS' => [
                    'ID' => [
                        'WIDGET' => new NumberWidget(),
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true,
                    ],
                    'ACTIVE' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => true
                    ],
                    'POSITION_ID' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => BannerPositionTable::getAdminAssocList(),
                        'DEFAULT' => 0,
                        'REQUIRED' => true
                    ],
                    'NAME' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '60',
                        'FILTER' => '%',
                        'REQUIRED' => true,
                        'EDIT_LINK' => true
                    ],
                    'NAME_EN' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '60',
                        'FILTER' => '%',
                        'EDIT_LINK' => true
                    ],
                    'SUBTITLE' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '60',
                        'FILTER' => '%',
                        'EDIT_LINK' => true
                    ],
                    'SUBTITLE_EN' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '60',
                        'FILTER' => '%',
                        'EDIT_LINK' => true
                    ],
                    'LINK' => [
                        'WIDGET' => new StringWidget(),
                    ],
                    'IMAGE' => [
                        'WIDGET' => new FileWidget(),
                    ],
                    'OVERLAY' => [
                        'WIDGET' => new ColorPickerWidget(),
                    ],
                ]
            ]
        ];
    }

    public function helpers()
    {
        return array(
            '\Sportsoft\Banner\Model\AdminInterface\BannerListHelper',
            '\Sportsoft\Banner\Model\AdminInterface\BannerEditHelper'
        );
    }
}
