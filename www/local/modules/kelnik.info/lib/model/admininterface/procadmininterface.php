<?php

namespace Kelnik\Info\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\DateTimeWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\TextAreaWidget;
use Kelnik\AdminHelper\Widget\UrlWidget;
use Kelnik\Info\Model\ProcTable;

Loc::loadMessages(__FILE__);

class ProcAdminInterface extends AdminInterface
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
                        'DEFAULT'  => ProcTable::YES
                    ],
                    'NAME'     => [
                        'WIDGET'   => new TextAreaWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => true
                    ],
                    'LINK' => [
                        'WIDGET' => new UrlWidget(),
                        'SIZE' => 40,
                        'REQUIRED' => true
                    ],
                    'DATE_SHOW' => [
                        'WIDGET' => new DateTimeWidget()
                    ],
                    'SORT'     => [
                        'WIDGET'  => new NumberWidget(),
                        'DEFAULT' => 500,
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
            ProcListHelper::class,
            ProcEditHelper::class
        ];
    }
}
