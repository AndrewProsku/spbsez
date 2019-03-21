<?php

namespace Kelnik\Infrastructure\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\FileWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\Infrastructure\Model\PlatformTable;

Loc::loadMessages(__FILE__);

class PlatformAdminInterface extends AdminInterface
{
    public function fields()
    {
        $res =[
            'MAIN' => [
                'NAME' => Loc::getMessage('KELNIK_INFRASTRUCTURE_TAB_MAIN'),
                'FIELDS' => [
                    'ID' => [
                        'WIDGET' => new NumberWidget(),
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true,
                    ],
                    'ACTIVE' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => true,
                        'FIELD_TYPE' => CheckboxWidget::TYPE_STRING
                    ],
                    'SORT' => [
                        'WIDGET' => new NumberWidget(),
                        'FILTER' => false,
                        'DEFAULT' => PlatformTable::SORT_DEFAULT
                    ],
                    'NAME_RU' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'REQUIRED' => true,
                        'EDIT_LINK' => true
                    ],
                    'NAME_EN' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE' => 40,
                        'FILTER' => '%',
                        'REQUIRED' => true,
                        'EDIT_LINK' => true
                    ],
                    'MAP_COORDS_LAT' => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'MAP_COORDS_LNG' => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'MAP_COORDS_CENTER_LAT' => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'MAP_COORDS_CENTER_LNG' => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'PLANOPLAN' => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'IMAGE_ID' => [
                        'WIDGET' => new FileWidget(),
                        'IMAGE' => true,
                        'HEADER' => false
                    ],
                    'VIDEO_ID' => [
                        'WIDGET' => new FileWidget(),
                        'HEADER' => false
                    ]
                ]
            ]
        ];

        foreach (PlatformTable::getFields() as $field) {
            $res[$field] = [
                'NAME' => Loc::getMessage('KELNIK_INFRASTRUCTURE_TAB_' . $field),
                'FIELDS' => [],
            ];
        }

        foreach (PlatformTable::getLangs() as $fieldLang) {
            $res['TEXT_GALLERY']['FIELDS']['IMAGES_' . $fieldLang] = [
                'WIDGET' => new FileWidget(),
                'IMAGE' => true,
                'MULTIPLE' => true,
                'TITLE' => Loc::getMessage('KELNIK_INFRASTRUCTURE_IMAGES_' . $fieldLang)
            ];
        }

        foreach (PlatformTable::getFields() as $field) {
            foreach (PlatformTable::getLangs() as $fieldLang) {
                $res[$field]['FIELDS'][$field . '_' . $fieldLang] = [
                    'WIDGET' => new VisualEditorWidget(),
                    'HEADER' => false,
                    'FILTER' => false,
                ];
            }
        }

        return $res;
    }

    public function helpers()
    {
        return array(
            PlatformListHelper::class,
            PlatformEditHelper::class
        );
    }
}
