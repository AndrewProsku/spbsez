<?php

namespace Kelnik\Report\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\DateTimeWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\UserOrmWidget;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Report\Model\StatusTable;
use Kelnik\Userdata\Profile\Profile;

Loc::loadMessages(__FILE__);

class ReportsAdminInterface extends AdminInterface
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
                    'COMPANY_ID' => [
                        'WIDGET' =>  new UserOrmWidget(),
                        'TITLE_FIELD_NAME' => Profile::COMPANY_NAME_FIELD,
                        'READONLY' => true,
                        'FILTER' => true,
                        'VIRTUAL' => true,
                        'FORCE_SELECT' => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'NAME' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'READONLY' => true,
                        'EDIT_LINK' => true
                    ],
                    'NAME_SEZ' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'READONLY' => true,
                    ],
                    'YEAR' => [
                        'WIDGET' => new NumberWidget(),
                        'READONLY' => true,
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'TYPE' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => ReportsTable::getTypes(),
                        'READONLY' => true,
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'STATUS_ID' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => StatusTable::getAdminAssocList(),
                        'FILTER' => true
                    ],
                    'DATE_CREATED' => [
                        'WIDGET' => new DateTimeWidget(),
                        'READONLY'         => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'DATE_MODIFIED' => [
                        'WIDGET' => new DateTimeWidget(),
                        'READONLY'         => true,
                        'HIDE_WHEN_CREATE' => true
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
            ReportsListHelper::class => [
                'BUTTONS' => [
                    'LIST_CREATE_NEW' => [
                        'VISIBLE' => 'N'
                    ]
                ]
            ],
            ReportsEditHelper::class,
            ReportsTreeHelper::class,
            ReportsExportHelper::class
        ];
    }
}
