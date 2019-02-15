<?php

namespace Kelnik\Messages\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\Messages\Model\MessageUsersTable;

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
                    'USERS' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => MessageUsersTable::getAdminAssocList(),
                        'TITLE' => Loc::getMessage('KELNIK_MESSAGES_USERS'),
                        'HEADER' => false,
                        'REQUIRED' => true,
                        'MULTIPLE' => true,
                        'EDIT_IN_LIST' => false,
                        'MULTIPLE_FIELDS' => [
                            'ENTITY_ID' => 'MESSAGE_ID',
                            'VALUE' => 'USER_ID'
                        ]
                    ],
                    'TEXT' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'REQUIRED' => true
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
