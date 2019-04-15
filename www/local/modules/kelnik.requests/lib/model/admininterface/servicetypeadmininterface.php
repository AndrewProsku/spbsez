<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\Requests\Model\TypeTable;

Loc::loadMessages(__FILE__);

class ServiceTypeAdminInterface extends AdminInterface
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
                    'NAME' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '40',
                        'FILTER' => '%',
                        'REQUIRED' => true,
                        'EDIT_LINK' => true
                    ],
                    'NAME_EN' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '40',
                        'FILTER' => '%',
                        'REQUIRED' => true,
                        'EDIT_LINK' => true
                    ],
                    'SORT' => [
                        'WIDGET' => new NumberWidget(),
                        'SIZE' => 10,
                        'HEADER' => false,
                        'FILTER' => false,
                        'DEFAULT' => TypeTable::SORT_DEFAULT
                    ],
                    'ACTIVE' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => false
                    ],
                    'EMAIL' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '40',
                        'FILTER' => '%',
                        'REQUIRED' => true
                    ],
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
            '\Kelnik\Requests\Model\AdminInterface\ServiceTypeListHelper',
            '\Kelnik\Requests\Model\AdminInterface\ServiceTypeEditHelper'
        ];
    }
}
