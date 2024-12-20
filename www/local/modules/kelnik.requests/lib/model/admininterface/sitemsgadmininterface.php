<?php

namespace Kelnik\Requests\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\DateTimeWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\TextAreaWidget;

Loc::loadMessages(__FILE__);

class SiteMsgAdminInterface extends AdminInterface
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
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'DATE_MODIFIED' => [
                        'WIDGET' => new DateTimeWidget(),
                        'READONLY' => true,
                        'HIDE_WHEN_CREATE' => true,
                        'HEADER' => false
                    ],
                    'NAME' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'EDIT_LINK' => true,
                        'READONLY' => true
                    ],
                    'EMAIL' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 10,
                        'FILTER' => '%',
                        'READONLY' => true
                    ],
                    'PHONE' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 10,
                        'FILTER' => '%',
                        'READONLY' => true
                    ],
                    'BODY' => [
                        'WIDGET' => new TextAreaWidget(),
                        'FILTER' => '%',
                        'READONLY' => true
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
            SiteMsgListHelper::class => [
                'BUTTONS' => [
                    'LIST_CREATE_NEW' => [
                        'VISIBLE' => 'N'
                    ]
                ]
            ],
            SiteMsgEditHelper::class
        ];
    }
}
