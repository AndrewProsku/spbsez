<?php

use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

IncludeModuleLangFile(__FILE__);

class kelnik_info extends CModule
{
    public $MODULE_ID = 'kelnik.info';
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
        $this->MODULE_NAME = Loc::getMessage('KELNIK_INFO_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('KELNIK_INFO_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('KELNIK_INFO_PARTNER_NAME');
        $this->PARTNER_URI = 'http://kelnik.ru';
    }

    public function DoInstall()
    {
        if (!$this->checkDependencies()) {
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);
        Loader::includeModule($this->MODULE_ID);

        $this->getConnection()->query("CREATE TABLE `" . \Kelnik\Info\Model\TypesTable::getTableName() . "` (
            `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `NAME` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`ID`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");

        $this->getConnection()->query("CREATE TABLE `" . \Kelnik\Info\Model\DocsTable::getTableName() . "` (
            `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `TYPE_ID` INT(11) UNSIGNED NOT NULL DEFAULT '0',
            `SORT` INT(11) NOT NULL DEFAULT '500',
            `FILE_ID` INT(11) UNSIGNED NULL DEFAULT '0',
            `DATE_SHOW` DATE NULL DEFAULT NULL,
            `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'N',
            `NAME` VARCHAR(255) NULL DEFAULT NULL,
            PRIMARY KEY (`ID`),
            INDEX `TYPE_ID` (`TYPE_ID`),
            INDEX `SORT` (`SORT`),
            INDEX `ACTIVE` (`ACTIVE`),
            INDEX `DATE_SHOW` (`DATE_SHOW`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB");

        $this->getConnection()->query("CREATE TABLE `" . \Kelnik\Info\Model\ProcTable::getTableName() . "` (
            `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `SORT` INT(11) NOT NULL DEFAULT '500',
            `DATE_SHOW` DATE NULL DEFAULT NULL,
            `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'N',
            `LINK` VARCHAR(255) NULL DEFAULT NULL,
            `NAME` TEXT NULL,
            PRIMARY KEY (`ID`),
            INDEX `SORT` (`SORT`),
            INDEX `ACTIVE` (`ACTIVE`),
            INDEX `DATE_SHOW` (`DATE_SHOW`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB");

        //$this->InstallFiles();
    }

    public function DoUninstall()
    {
        Loader::includeModule($this->MODULE_ID);

        $this->getConnection()->dropTable(\Kelnik\Info\Model\TypesTable::getTableName());
        $this->getConnection()->dropTable(\Kelnik\Info\Model\DocsTable::getTableName());
        $this->getConnection()->dropTable(\Kelnik\Info\Model\ProcTable::getTableName());

        //$this->UnInstallFiles();

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    protected function getConnection()
    {
        return Application::getInstance()->getConnection();
    }

    public function InstallFiles()
    {
    }

    public function UnInstallFiles()
    {
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
