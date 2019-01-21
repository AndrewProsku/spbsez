<?php

namespace Kelnik\Vacancy\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\ChildWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\Vacancy\Model\VacancyTable;

Loc::loadMessages(__FILE__);

class VacancyAdminInterface extends AdminInterface
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
                        'DEFAULT'  => VacancyTable::YES
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

                    'DESCR' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],

                    'RESPONSE_CNT' => [
                        'WIDGET' => new ChildWidget(),
                        'LIST_HELPER' => ResponseListHelper::class,
                        'PARENT_FIELD' => 'VACANCY_ID',
                        'TITLE' => Loc::getMessage('KELNIK_VACANCY_RESPONSE_CNT'),
                        'VIRTUAL' => true,
                        'READONLY' => true,
                        'FORCE_SELECT' => true
                    ]
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
            VacancyListHelper::class,
            VacancyEditHelper::class
        ];
    }
}
