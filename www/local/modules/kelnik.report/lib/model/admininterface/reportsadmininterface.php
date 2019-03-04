<?php

namespace Kelnik\Report\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\DateTimeWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Report\Model\StatusTable;

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
                    'NAME' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'REQUIRED' => true,
                        'EDIT_LINK' => true
                    ],
                    'NAME_RESIDENT' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'REQUIRED' => true
                    ],
                    'YEAR' => [
                        'WIDGET' => new NumberWidget(),
                        'READONLY' => true,
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'QUARTER' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => ReportsTable::getQuarters(),
                        'READONLY' => true,
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'STATUS_ID' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => StatusTable::getAdminAssocList(),
                        'READONLY' => true,
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true
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
            ReportsListHelper::class,
            ReportsEditHelper::class,
            ReportsTreeHelper::class
        ];
    }
}
