<?php

namespace Kelnik\Multisites\Settings\AdminInterface;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\MultiWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\FileWidget;
use Kelnik\AdminHelper\Widget\VisualEditorWidget;
use Kelnik\Multisites\Settings\SitesTable;

Loc::loadMessages(__FILE__);
Loader::includeModule('kelnik.multisites');

/**
 * Описание интерфейса (табок и полей) админки новостей.
 *
 * {@inheritdoc}
 */
class SitesAdminInterface extends AdminInterface
{
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'MAIN' => [
                'NAME'   => Loc::getMessage('KELNIK_SITES_TAB_MAIN'),
                'FIELDS' => [
                    'ID'          => [
                        'WIDGET'           => new NumberWidget(),
                        'READONLY'         => true,
                        'FILTER'           => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'ACTIVE'      => [
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => true,
                    ],
                    'NAME'        => [
                        'WIDGET'    => new StringWidget(),
                        'SIZE'      => '60',
                        'FILTER'    => '%',
                        'EDIT_LINK' => true,
                        'REQUIRED'  => true,
                    ],
                    'DOMAIN'      => [
                        'WIDGET'   => new StringWidget(),
                        'FILTER'   => false,
                        'REQUIRED' => true,
                        'MULTIPLE' => true,
                        'HEADER'   => false,
                        'TITLE'    => Loc::getMessage('KELNIK_MULTISITE_DOMAIN')
                    ],
                    'DOMAIN_LIST' => [
                        'WIDGET'       => new MultiWidget(),
                        'TITLE'        => Loc::getMessage('KELNIK_MULTISITE_DOMAIN'),
                        'FILTER'       => false,
                        'VIRTUAL '     => true,
                        'READONLY'     => true,
                        'FORCE_SELECT' => true,
                        'SEPARATOR'    => ', '
                    ],

                    'PHONE'           => [
                        'WIDGET' => new StringWidget(),
                        'SIZE'   => '60',
                    ],
                    'ADDRESS'         => [
                        'WIDGET' => new StringWidget(),
                        'SIZE'   => '60',
                    ],
                    'SOCIAL_INST'     => [
                        'WIDGET' => new StringWidget(),
                        'SIZE'   => '60',
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'SOCIAL_FACEBOOK' => [
                        'WIDGET' => new StringWidget(),
                        'SIZE'   => '60',
                        'HEADER' => false,
                        'FILTER' => false
                    ],

                    'MAIN_VIDEO_MP4'  => [
                        'WIDGET' => new FileWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'MAIN_VIDEO_OGV'  => [
                        'WIDGET' => new FileWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'MAIN_VIDEO_WEBM' => [
                        'WIDGET' => new FileWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],

                    'PRESS_CONTACT'    => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'PRESS_CONTACT_EN' => [
                        'WIDGET' => new VisualEditorWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],

                    'TEMPLATE_ID' => [
                        'WIDGET'          => new ComboBoxWidget(),
                        'HEADER'          => true,
                        'VARIANTS'        => SitesTable::getTemplatesList(),
                        'DEFAULT_VARIANT' => null,
                    ],

//                    'SEO_TITLE' => array(
//                        'WIDGET' => new StringWidget(),
//                        'SIZE' => '80',
//                    ),
//                    'SEO_DESCRIPTION' => array(
//                        'WIDGET' => new StringWidget(),
//                        'SIZE' => '80',
//                    ),
//                    'SEO_KEYWORDS' => array(
//                        'WIDGET' => new StringWidget(),
//                        'SIZE' => '80',
//                    ),
                ]
            ],
            'SMTP' => [
                'NAME'   => Loc::getMessage('KELNIK_MULTISITE_SMTP_TAB'),
                'FIELDS' => [
                    'USE_SMTP' => [
                        'WIDGET' => new CheckboxWidget(),
                        'FIELD_TYPE' => CheckboxWidget::TYPE_STRING,
                        'FILTER' => false,
                        'HEADER' => false
                    ],
                    'SMTP_HOST' => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'SMTP_USER' => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'SMTP_PWD' => [
                        'WIDGET' => new StringWidget(),
                        'HEADER' => false,
                        'FILTER' => false
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
            'Kelnik\Multisites\Settings\AdminInterface\SitesListHelper',
            'Kelnik\Multisites\Settings\AdminInterface\SitesEditHelper'
        ];
    }
}
