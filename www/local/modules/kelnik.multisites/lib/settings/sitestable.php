<?php

namespace Kelnik\Multisites\Settings;

use Bitrix\Main\Application;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\Path;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Context;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

/**
 * Модель сайтов для модуля мультисайтовости.
 */
class SitesTable extends DataManager
{
    /**
     * {@inheritdoc}
     */
    public static function getTableName()
    {
        return 'kelnik_multisites';
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'primary'      => true,
                'autocomplete' => true,
            ]),
            new IntegerField(
                'MAIN_VIDEO_MP4',
                [
                    'title' => Loc::getMessage('KELNIK_MULTISITE_VIDEO_MP4')
                ]
            ),
            new IntegerField(
                'MAIN_VIDEO_OGV',
                [
                    'title' => Loc::getMessage('KELNIK_MULTISITE_VIDEO_OGV')
                ]
            ),
            new IntegerField(
                'MAIN_VIDEO_WEBM',
                [
                    'title' => Loc::getMessage('KELNIK_MULTISITE_VIDEO_WEBM')
                ]
            ),
            new StringField(
                'ACTIVE', [
                    'values' => [self::YES, self::NO],
                    'title'  => Loc::getMessage('KELNIK_MULTISITE_ACTIVE')
                ]
            ),
            new StringField(
                'USE_SMTP', [
                    'values' => [self::YES, self::NO],
                    'title'  => Loc::getMessage('KELNIK_MULTISITE_SMTP_USE')
                ]
            ),
            new StringField('NAME', [
                'title'    => Loc::getMessage('KELNIK_MULTISITE_TITLE'),
                'required' => true,
            ]),
            new StringField('PHONE', [
                'title' => Loc::getMessage('KELNIK_MULTISITE_PHONE')
            ]),
            new StringField('ADDRESS', [
                'title' => Loc::getMessage('KELNIK_MULTISITE_ADDRESS')
            ]),
            new StringField('SOCIAL_INST', [
                'title' => Loc::getMessage('KELNIK_MULTISITE_SOCIAL_INST')
            ]),
            new StringField('SOCIAL_FACEBOOK', [
                'title' => Loc::getMessage('KELNIK_MULTISITE_SOCIAL_FACEBOOK')
            ]),
            new StringField('SOCIAL_YOUTUBE', [
                'title' => Loc::getMessage('KELNIK_MULTISITE_SOCIAL_YOUTUBE')
            ]),
            new StringField('TEMPLATE_ID', [
                'title' => Loc::getMessage('KELNIK_MULTISITE_TEMPLATE_ID'),
            ]),
            new StringField('SMTP_HOST', [
                'title' => Loc::getMessage('KELNIK_MULTISITE_SMTP_HOST'),
            ]),
            new StringField('SMTP_USER', [
                'title' => Loc::getMessage('KELNIK_MULTISITE_SMTP_USER'),
            ]),
            new StringField('SMTP_PWD', [
                'title' => Loc::getMessage('KELNIK_MULTISITE_SMTP_PWD'),
            ]),
            new TextField(
                'PRESS_CONTACT',
                [
                    'title' => Loc::getMessage('KELNIK_MULTISITE_PRESS_CONTACT')
                ]
            ),
            new TextField(
                'PRESS_CONTACT_EN',
                [
                    'title' => Loc::getMessage('KELNIK_MULTISITE_PRESS_CONTACT_EN')
                ]
            ),

            // SEO
//            new StringField('SEO_TITLE', [
//                'title' => Loc::getMessage('KELNIK_MULTISITE_SEO_TITLE')
//            ]),
//            new StringField('SEO_DESCRIPTION', [
//                'title' => Loc::getMessage('KELNIK_MULTISITE_SEO_DESCRIPTION')
//            ]),
//            new StringField('SEO_KEYWORDS', [
//                'title' => Loc::getMessage('KELNIK_MULTISITE_SEO_KEYWORDS')
//            ]),

            new ReferenceField(
                'DOMAIN',
                DomainsTable::class,
                [
                    '=this.ID' => 'ref.ENTITY_ID'
                ],
                [
                    'join' => 'LEFT'
                ]
            ),
            new ExpressionField(
                'DOMAIN_LIST',
                '(SELECT GROUP_CONCAT(DISTINCT CONCAT(`VALUE`) SEPARATOR ", ") FROM `' . DomainsTable::getTableName() . '` WHERE `ENTITY_ID` = %s)',
                'ID'
            )
        ];
    }

    public static function getFilePath()
    {
        return __FILE__;
    }

    // Определяем текущий сайт
    public static function getCurrentSite()
    {
        $host = Context::getCurrent()->getServer()->getHttpHost();
        $arSites = self::getAssoc([
            'select' => [
                '*',
                'HOST' => 'DOMAIN.VALUE'
            ],
            'filter' => [
                '=ACTIVE' => DataManager::YES
            ]
        ], 'HOST');

        return isset($arSites[$host]) ? $arSites[$host] : false;
    }

    // Получаем список шаблонов сайта
    public static function getTemplatesList()
    {
        $path2templates = [];
        $path2templates[] = Path::combine(
            Application::getDocumentRoot(),
            Application::getPersonalRoot(),
            'templates'
        );

        $path2templates[] = Path::combine(
            Application::getDocumentRoot(),
            'local',
            'templates'
        );

        $arTemplates = [];
        foreach ($path2templates as $path) {
            if (!Directory::isDirectoryExists($path)) {
                continue;
            }
            $dirTemplates = new Directory($path);
            foreach ($dirTemplates->getChildren() as &$template) {
                $key = $template->getName();

                $arTemplate = [];
                include $template->getPath() . DIRECTORY_SEPARATOR . 'description.php';

                $arTemplates[$key] = !empty($arTemplate['NAME'])
                    ? $arTemplate['NAME'] . ' (' . $key . ')'
                    : $key;
            }
        }

        return array_unique($arTemplates);
    }
}
