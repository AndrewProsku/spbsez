<?php

namespace Kelnik\Questions\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\Questions\Model\QuestionsTypesTable;

Loc::loadMessages(__FILE__);

class QuestionsAdminInterface extends AdminInterface
{
    public function fields()
    {
        return [
            'MAIN' => [
                'NAME' => Loc::getMessage('KELNIK_QUESTIONS_TAB_MAIN'),
                'FIELDS' => [
                    'ID' => [
                        'WIDGET' => new NumberWidget(),
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true,
                    ],
                    'ACTIVE' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => true
                    ],
                    'SORT' => [
                        'WIDGET' => new NumberWidget(),
                        'FILTER' => false,
                        'DEFAULT' => 500
                    ],
                    'TYPE_ID' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'VARIANTS' => QuestionsTypesTable::getAdminAssocList(),
                        'DEFAULT' => 0
                    ],
                    'NAME' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '60',
                        'FILTER' => '%',
                        'REQUIRED' => true,
                        'EDIT_LINK' => true
                    ],
                    'URL' => [
                        'WIDGET' => new StringWidget(),
                    ],
                    'LANG' => [
                        'WIDGET' => new StringWidget(),
                        'DEFAULT' => LANGUAGE_ID
                    ],
                    'TEXT' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false
                    ]
                ]
            ]
        ];
    }

    public function helpers()
    {
        return array(
            '\Kelnik\Questions\Model\AdminInterface\QuestionsListHelper',
            '\Kelnik\Questions\Model\AdminInterface\QuestionsEditHelper'
        );
    }
}
