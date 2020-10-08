<?php

namespace Kelnik\Questions\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\Questions\Model\QuestionsTable;

Loc::loadMessages(__FILE__);

class QuestionsTypesAdminInterface extends AdminInterface
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
                        'DEFAULT'  => 'Y'
                    ],
                    'NAME'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => true
                    ],
                    'NAME_EN'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => true
                    ],
                    'SORT'     => [
                        'WIDGET'  => new NumberWidget(),
                        'DEFAULT' => 500,
                        'FILTER' => false,
                        'SIZE' => 5
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
            '\Kelnik\Questions\Model\AdminInterface\QuestionsTypesListHelper',
            '\Kelnik\Questions\Model\AdminInterface\QuestionsTypesEditHelper',
        ];
    }
}
