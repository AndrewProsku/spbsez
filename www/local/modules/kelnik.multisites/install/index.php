<?php

use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Kelnik\Multisites\Settings\DomainsTable;
use Kelnik\Multisites\Settings\SitesTable;

Loc::loadMessages(__FILE__);

class kelnik_multisites extends CModule
{
    public $MODULE_ID = 'kelnik.multisites';
    public $MODULE_GROUP_RIGHTS = 'Y';

    public function __construct()
    {
        $arModuleVersion = array();

        include(__DIR__ . '/version.php');

        if (
            is_array($arModuleVersion)
            && array_key_exists('VERSION', $arModuleVersion)
        ) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('KELNIK_MULTISITES_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('KELNIK_MULTISITES_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('KELNIK_MULTISITES_PARTNER_NAME');
        $this->PARTNER_URI = '';
    }

    public function DoInstall()
    {
        if (!$this->checkDependencies()) {
            return false;
        }
        
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallDB();

        // Подписываемся на событие
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->registerEventHandler(
            'main',
            'OnPageStart',
            $this->MODULE_ID,
            '\Kelnik\Multisites\Settings\EventHandlers',
            'onPageStart'
        );
    }

    public function InstallDB()
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            return;
        }

        $this->getConnection()->query("CREATE TABLE `" . SitesTable::getTableName() . "` (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'N' COLLATE 'utf8_unicode_ci',
            `NAME` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
            `PHONE` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
            `ADDRESS` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
            `TEMPLATE_ID` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
            `SEO_TITLE` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
            `SEO_DESCRIPTION` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
            `SEO_KEYWORDS` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
            PRIMARY KEY (`ID`),
            INDEX `ACTIVE` (`ACTIVE`)
        ) COLLATE='utf8_unicode_ci' ENGINE=InnoDB;");

        $this->getConnection()->query("CREATE TABLE `" . DomainsTable::getTableName() . "` (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `ENTITY_ID` INT(11) NOT NULL DEFAULT '0',
            `VALUE` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`ID`),
            INDEX `ENTITY_ID` (`ENTITY_ID`),
            INDEX `VALUE` (`VALUE`)
        ) COLLATE='utf8_unicode_ci' ENGINE=InnoDB;");
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);

        // Отписываемся от события
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->unRegisterEventHandler(
            'main',
            'OnPageStart',
            $this->MODULE_ID,
            '\Kelnik\Multisites\Settings\EventHandlers',
            'onPageStart'
        );
    }

    public function UnInstallDB()
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            return;
        }

        $connection = $this->getConnection();
        $connection->dropTable(SitesTable::getTableName());
        $connection->dropTable(DomainsTable::getTableName());
    }

    protected function getConnection()
    {
        return Application::getInstance()->getConnection();
    }

    protected function checkDependencies()
    {
        global $APPLICATION;

        $requireModules = include implode(
            DIRECTORY_SEPARATOR,
            [
                __DIR__,
                '..',
                'require.php'
            ]
        );

        if (!$requireModules) {
            return true;
        }

        $result = [];

        foreach ($requireModules as $moduleName => $moduleParams) {
            if (ModuleManager::isModuleInstalled($moduleName)) {
                continue;
            }

            $result[] = $moduleName;
        }

        if (empty($result)) {
            return true;
        }

        $APPLICATION->ThrowException(new CApplicationException(
            Loc::getMessage(
                'KELNIK_REQUIRE_MODULES',
                [
                    '#MODULES#' => implode(', ', $result)
                ]
            )
        ));

        return false;
    }
}
