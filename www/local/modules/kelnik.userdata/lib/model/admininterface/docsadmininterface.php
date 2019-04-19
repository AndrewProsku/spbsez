<?php

namespace Kelnik\UserData\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\DateTimeWidget;
use Kelnik\AdminHelper\Widget\FileWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\UserOrmWidget;
use Kelnik\Userdata\Profile\Profile;

Loc::loadMessages(__FILE__);

class DocsAdminInterface extends AdminInterface
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
                        'HIDE_WHEN_CREATE' => true,
                        'TITLE' => Loc::getMessage('KELNIK_USER_DATA_COMPANY')
                    ],
                    'USER_ID' => [
                        'WIDGET' => new UserOrmWidget(),
                        'REQUIRED' => true,
                        'FILTER' => true,
                        'SIZE' => 2
                    ],
                    'FILE_ID' => [
                        'WIDGET' => new FileWidget(),
                        'FILTER' => true,
                        'REQUIRED' => true
                    ],
                    'DATE_CREATED' => [
                        'WIDGET' => new DateTimeWidget(),
                        'READONLY' => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'DATE_MODIFIED' => [
                        'WIDGET' => new DateTimeWidget(),
                        'READONLY' => true,
                        'HIDE_WHEN_CREATE' => true
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
            DocsListHelper::class,
            DocsEditHelper::class,
            DocsTreeHelper::class
        ];
    }
}
