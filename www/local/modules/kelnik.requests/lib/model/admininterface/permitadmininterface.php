<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\DateTimeWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\UserWidget;
use Kelnik\Requests\Model\StatusTable;
use Kelnik\Requests\Model\TypeTable;
use Kelnik\Requests\Widget\passwidget;

Loc::loadMessages(__FILE__);

class PermitAdminInterface extends AdminInterface
{
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'MAIN' => [
                'NAME' => Loc::getMessage('KELNIK_REQ_TAB_MAIN'),
                'FIELDS' => [
                    'ID' => [
                        'WIDGET' => new NumberWidget(),
                        'READONLY' => true,
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true,
                    ],
                    'DATE_CREATED' => [
                        'WIDGET' => new DateTimeWidget(),
                        'READONLY' => true,
                        'HEADER' => false,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'DATE_MODIFIED' => [
                        'WIDGET' => new DateTimeWidget(),
                        'READONLY' => true,
                        'HEADER' => false,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'TYPE_ID' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => TypeTable::getAdminAssocList(),
                        'READONLY' => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'STATUS_ID' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => StatusTable::getAdminAssocList(),
                        'REQUIRED' => true
                    ],
                    'USER_ID' => [
                        'WIDGET' => new UserWidget(),
                        'READONLY' => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'CODE' => [
                        'WIDGET' => new StringWidget(),
                        'FILTER' => '%',
                        'EDIT_LINK' => true,
                        'READONLY' => true
                    ],
                    'NAME' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'EDIT_LINK' => true,
                        'READONLY' => true
                    ],
                    'DATE_START' => [
                        'WIDGET' => new DateTimeWidget(),
                        'READONLY' => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'DATE_FINISH' => [
                        'WIDGET' => new DateTimeWidget(),
                        'READONLY' => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'TARGET' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'READONLY' => true
                    ],
                    'EXECUTIVE_COMPANY' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'HEADER' => false,
                        'READONLY' => true
                    ],
                    'EXECUTIVE_VISIT' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'HEADER' => false,
                        'READONLY' => true
                    ],
                    'PHONE' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'HEADER' => false,
                        'READONLY' => true
                    ],
                ]
            ],
            'PASSES' => [
                'NAME' => Loc::getMessage('KELNIK_REQ_TAB_PASSES'),
                'FIELDS' => [
                    'PASS' => [
                        'WIDGET' => new passwidget(),
                        'TITLE' => Loc::getMessage('KELNIK_REQ_FIELD_PASSES'),
                        'MULTIPLE' => true,
                        'MULTIPLE_FIELDS' => [
                            'ENTITY_ID' => 'PERMIT_ID',
                            'VALUE' => 'FIO'
                        ],
                        'READONLY' => true,
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
            PermitListHelper::class => [
                'BUTTONS' => [
                    'LIST_CREATE_NEW' => [
                        'VISIBLE' => 'N'
                    ]
                ]
            ],
            PermitEditHelper::class
        ];
    }
}
