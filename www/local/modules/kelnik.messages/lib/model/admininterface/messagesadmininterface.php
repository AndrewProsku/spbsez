<?php

namespace Kelnik\Messages\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\ChildWidget;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\FileWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\Messages\Model\MessageCompaniesTable;
use Kelnik\Userdata\Profile\Profile;

Loc::loadMessages(__FILE__);

class MessagesAdminInterface extends AdminInterface
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
                        'SIZE' => 60,
                        'REQUIRED' => true
                    ],
                    'ACTIVE' => [
                        'WIDGET' => new CheckboxWidget()
                    ],
                    'COMPANIES' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => Profile::getAdminCompanyList(),
                        'TITLE' => Loc::getMessage('KELNIK_MESSAGES_COMPANIES'),
                        'HEADER' => false,
                        'REQUIRED' => true,
                        'MULTIPLE' => true,
                        'EDIT_IN_LIST' => false,
                        'MULTIPLE_FIELDS' => [
                            'ID' => 'ID',
                            'ENTITY_ID' => 'MESSAGE_ID',
                            'VALUE' => 'USER_ID'
                        ]
                    ],
                    'FILES' => [
                        'WIDGET' => new FileWidget(),
                        'MULTIPLE' => true,
                        'HEADER' => false,
                        'TITLE' => Loc::getMessage('KELNIK_MESSAGES_FILES')
                    ],
                    'TEXT' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'REQUIRED' => true
                    ],
                    'USER_CNT' => [
                        'WIDGET' => new ChildWidget(),
                        'TITLE' => Loc::getMessage('KELNIK_MESSAGES_USERS'),
                        'VIRTUAL' => true,
                        'FORCE_SELECT' => true,
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
            MessagesListHelper::class,
            MessagesEditHelper::class
        ];
    }
}
