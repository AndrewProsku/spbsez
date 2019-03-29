<?php

namespace Kelnik\Infrastructure\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\FileWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\OrmElementWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\TextAreaWidget;
use Kelnik\Refbook\Model\AdminInterface\ResidentEditHelper;
use Kelnik\Refbook\Model\AdminInterface\ResidentListHelper;

Loc::loadMessages(__FILE__);

class PlanAdminInterface extends AdminInterface
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
                    'PLATFORM_ID' => [
                        'WIDGET' => new OrmElementWidget(),
                        'HELPER' => PlatformListHelper::class,
                        'EDIT_HELPER' => PlatformEditHelper::class,
                        'TITLE_FIELD_NAME' => 'NAME_RU',
                        'REQUIRED' => true
                    ],
                    'RESIDENT_ID' => [
                        'WIDGET' => new OrmElementWidget(),
                        'HELPER' => ResidentListHelper::class,
                        'EDIT_HELPER' => ResidentEditHelper::class,
                        'TITLE_FIELD_NAME' => 'NAME'
                    ],
                    'RESIDENT_IMAGE' => [
                        'WIDGET' => new FileWidget(),
                        'HEADER' => false,
                        'FILTER' => false,
                        'IMAGE' => true
                    ],
                    'ACTIVE' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => true,
                        'FIELD_TYPE' => CheckboxWidget::TYPE_STRING
                    ],
                    'AREA' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 5,
                        'FILTER' => '%',
                        'EDIT_LINK' => true
                    ],
                    'PRICE_RU' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 15,
                        'FILTER' => '%',
                        'EDIT_LINK' => true
                    ],
                    'PRICE_EN' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 15,
                        'FILTER' => false,
                        'HEADER' => false
                    ],
                    'RENT_RU' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 15,
                        'FILTER' => '%'
                    ],
                    'RENT_EN' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 15,
                        'FILTER' => false,
                        'HEADER' => false
                    ],
                    'HEAT' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => false,
                        'HEADER' => false,
                        'FIELD_TYPE' => CheckboxWidget::TYPE_STRING
                    ],
                    'ELECTRICITY' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => false,
                        'HEADER' => false,
                        'FIELD_TYPE' => CheckboxWidget::TYPE_STRING
                    ],
                    'WATER' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => false,
                        'HEADER' => false,
                        'FIELD_TYPE' => CheckboxWidget::TYPE_STRING
                    ],
                    'STORM_SEWER' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => false,
                        'HEADER' => false,
                        'FIELD_TYPE' => CheckboxWidget::TYPE_STRING
                    ],
                    'COORDS' => [
                        'WIDGET' => new TextAreaWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ]
                ]
            ]
        ];
    }

    public function helpers()
    {
        return array(
            PlanListHelper::class,
            PlanEditHelper::class
        );
    }
}
