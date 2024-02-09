<?php

namespace Kelnik\Vacancy\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\OrmElementWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\Refbook\Model\AdminInterface\ResidentEditHelper;
use Kelnik\Refbook\Model\AdminInterface\ResidentListHelper;
use Kelnik\Vacancy\Model\VacancyResidentsTable;

Loc::loadMessages(__FILE__);

class VacancyResidentsAdminInterface extends AdminInterface
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
                        'DEFAULT'  => VacancyResidentsTable::YES
                    ],
                    'SORT'     => [
                        'WIDGET'  => new NumberWidget(),
                        'DEFAULT' => 500,
                        'SIZE' => 5
                    ],
                    'NAME'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => true
                    ],
                    'RESIDENT_ID' => [
                        'WIDGET' => new OrmElementWidget(),
                        'HELPER' => ResidentListHelper::class,
                        'EDIT_HELPER' => ResidentEditHelper::class,
                        'TITLE_FIELD_NAME' => 'NAME'
                    ],
                    'PRICE_MIN'     => [
                        'WIDGET'   => new NumberWidget(),
                        'SIZE'     => 10,
                        'FILTER'   => false,
                        'HEADER' => false
                    ],
                    'PRICE_MAX'     => [
                        'WIDGET'   => new NumberWidget(),
                        'SIZE'     => 10,
                        'FILTER'   => false,
                        'HEADER' => false
                    ],
                    'PRICE_TEXT'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'HEADER' => false
                    ],
                    'EXPERIENCE'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%'
                    ],
                    'EMPLOYMENT'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%'
                    ],
                    'CONTACTS'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => false
                    ],
                    'DESCR' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                ],
            ],
            'DUTIES' => [
                'NAME' => Loc::getMessage('KELNIK_FIELDS_DUTIES'),
                'FIELDS' => [
                    'DUTIES' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ]
                ]
            ],
            'REQUIREMENTS' => [
                'NAME' => Loc::getMessage('KELNIK_FIELDS_REQUIREMENTS'),
                'FIELDS' => [
                    'REQUIREMENTS' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ]
                ]
            ],
            'CONDITIONS' => [
                'NAME' => Loc::getMessage('KELNIK_FIELDS_CONDITIONS'),
                'FIELDS' => [
                    'CONDITIONS' => [
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
            VacancyResidentsListHelper::class,
            VacancyResidentsEditHelper::class
        ];
    }
}
