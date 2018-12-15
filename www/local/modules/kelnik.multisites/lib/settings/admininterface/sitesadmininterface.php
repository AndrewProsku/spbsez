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
        return array(
            'MAIN' => array(
                'NAME' => Loc::getMessage('KELNIK_SITES_TAB_MAIN'),
                'FIELDS' => array(
                    'ID' => array(
                        'WIDGET' => new NumberWidget(),
                        'READONLY' => true,
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true
                    ),
                    'ACTIVE' => array(
                        'WIDGET' => new CheckboxWidget(),
                        'FILTER' => true,
                    ),
                    'NAME' => array(
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '60',
                        'FILTER' => '%',
                        'EDIT_LINK' => true,
                        'REQUIRED' => true,
                    ),
                    'DOMAIN' => array(
                        'WIDGET' => new StringWidget(),
                        'FILTER' => false,
                        'REQUIRED' => true,
                        'MULTIPLE' => true,
                        'HEADER' => false,
                        'TITLE' => Loc::getMessage('KELNIK_MULTISITE_DOMAIN')
                    ),
                    'DOMAIN_LIST' => [
                        'WIDGET' => new MultiWidget(),
                        'TITLE' => Loc::getMessage('KELNIK_MULTISITE_DOMAIN'),
                        'FILTER' => false,
                        'VIRTUAL '=> true,
                        'READONLY' => true,
                        'FORCE_SELECT' => true,
                        'SEPARATOR' => ', '
                    ],

                    'PHONE' => array(
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '60',
                    ),
                    'ADDRESS' => array(
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '60',
                    ),
                    'SOCIAL_INST' => array(
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '60',
                        'HEADER' => false,
                        'FILTER' => false
                    ),
                    'SOCIAL_FACEBOOK' => array(
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '60',
                        'HEADER' => false,
                        'FILTER' => false
                    ),

                    'MAIN_VIDEO_MP4' => [
                        'WIDGET' => new FileWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'MAIN_VIDEO_OGV' => [
                        'WIDGET' => new FileWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],
                    'MAIN_VIDEO_WEBM' => [
                        'WIDGET' => new FileWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ],

                    'TEMPLATE_ID' => array(
                        'WIDGET' => new ComboBoxWidget(),
                        'HEADER' => true,
                        'VARIANTS' => SitesTable::getTemplatesList(),
                        'DEFAULT_VARIANT' => null,
                    ),

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
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function helpers()
    {
        return array(
            'Kelnik\Multisites\Settings\AdminInterface\SitesListHelper',
            'Kelnik\Multisites\Settings\AdminInterface\SitesEditHelper'
        );
    }
}
