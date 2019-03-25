<?php

namespace Kelnik\Userdata\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\DateTimeWidget;
use Kelnik\AdminHelper\Widget\FileWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\UserWidget;
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
                        'WIDGET' =>  new ComboBoxWidget(),
                        'VARIANTS' => Profile::getAdminCompanyList(),
                        'READONLY' => true,
                        'FILTER' => true,
                        'VIRTUAL' => true,
                        'FORCE_SELECT' => true,
                        'TITLE' => Loc::getMessage('KELNIK_USER_DATA_COMPANY')
                    ],
                    'USER_ID' => [
                        'WIDGET' => new UserWidget(),
                        'REQUIRED' => true,
                        'FILTER' => true,
                        'SIZE' => 2
                    ],
                    'FILE_ID' => [
                        'WIDGET' => new FileWidget(),
                        'FILTER' => true
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
            '\Kelnik\Userdata\Model\AdminInterface\DocsListHelper',
            '\Kelnik\Userdata\Model\AdminInterface\DocsEditHelper',
        ];
    }
}
