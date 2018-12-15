<?php

namespace Kelnik\Multisites\Settings;

use Bitrix\Main\Context;
use Bitrix\Main\Loader;

/**
 * Перехватчики событий.
 *
 */
class EventHandlers
{
    /**
     * Автоматическое подключение модуля в публичной части.
     *
     * Таки образом, исключаем необходимость прописывать в публичной части подключение модуля мультисайтовости
     *
     * @throws \Bitrix\Main\LoaderException
     */
    public static function onPageStart()
    {
        global $APPLICATION;

        if (PHP_SAPI === 'cli' || Context::getCurrent()->getRequest()->isAdminSection()) {
            return;
        }

        if (!Loader::includeModule('kelnik.multisites')) {
            return;
        }

        $CurrentSite = CurrentSite::getInstance();
        $siteInfo = $CurrentSite->getData();

        if (!$siteInfo) {
            die('Сайт не существует');
        }

        if (!defined('SITE_TEMPLATE_ID') && $siteInfo['TEMPLATE_ID']) {
            define('SITE_TEMPLATE_ID', $siteInfo['TEMPLATE_ID']);
        }

//            $APPLICATION->SetTitle("Page title");
        if ($CurrentSite->getField('SEO_TITLE')) {
            $APPLICATION->SetPageProperty('title', $CurrentSite->getField('SEO_TITLE'));
        }
        if ($CurrentSite->getField('SEO_DESCRIPTION')) {
            $APPLICATION->SetPageProperty('description', $CurrentSite->getField('SEO_DESCRIPTION'));
        }
        if ($CurrentSite->getField('SEO_KEYWORDS')) {
            $APPLICATION->SetPageProperty('keywords', $CurrentSite->getField('SEO_KEYWORDS'));
        }
    }
}
