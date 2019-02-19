<?php

use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

IncludeModuleLangFile(__FILE__);

class kelnik_refbook extends CModule
{
    public $MODULE_ID = 'kelnik.refbook';
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
        $this->MODULE_NAME = Loc::getMessage('KELNIK_REFBOOK_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('KELNIK_REFBOOK_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('KELNIK_REFBOOK_PARTNER_NAME');
        $this->PARTNER_URI = 'http://kelnik.ru';
    }

    public function DoInstall()
    {
        if (!$this->checkDependencies()) {
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);
        Loader::includeModule($this->MODULE_ID);

        $this->getConnection()->query("CREATE TABLE `" . \Kelnik\Refbook\Model\PartnerTable::getTableName() . "` (
            `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `SORT` INT(11) NOT NULL DEFAULT '500',
            `IMAGE_ID` INT(11) UNSIGNED NULL DEFAULT '0',
            `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'N',
            `NAME` VARCHAR(255) NOT NULL,
            `TEXT_TEXT_TYPE` VARCHAR(4) NULL DEFAULT 'html',
            `TEXT` TEXT NULL,
            PRIMARY KEY (`ID`),
            INDEX `SORT` (`SORT`),
            INDEX `ACTIVE` (`ACTIVE`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");

        $this->getConnection()->query("CREATE TABLE `" . \Kelnik\Refbook\Model\ResidentTable::getTableName() . "` (
            `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `TYPE_ID` INT(11) UNSIGNED NOT NULL DEFAULT '0',
            `PLACE` INT(3) UNSIGNED NOT NULL DEFAULT '0',
            `SORT` INT(11) NOT NULL DEFAULT '500',
            `IMAGE_ID` INT(11) UNSIGNED NULL DEFAULT '0',
            `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'N',
            `NAME` VARCHAR(255) NOT NULL,
            `SITE` VARCHAR(255) NULL DEFAULT NULL,
            `TEXT_TEXT_TYPE` VARCHAR(4) NULL DEFAULT 'html',
            `TEXT` TEXT NULL,
            PRIMARY KEY (`ID`),
            INDEX `SORT` (`SORT`),
            INDEX `ACTIVE` (`ACTIVE`),
            INDEX `TYPE_ID` (`TYPE_ID`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");

        $this->getConnection()->query("CREATE TABLE `" . \Kelnik\Refbook\Model\ResidentTypesTable::getTableName() . "` (
            `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `SORT` INT(11) NOT NULL DEFAULT '500',
            `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'N',
            `NAME` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`ID`),
            INDEX `SORT` (`SORT`),
            INDEX `ACTIVE` (`ACTIVE`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");

        $this->getConnection()->query("CREATE TABLE `" . \Kelnik\Refbook\Model\ReviewTable::getTableName() . "` (
            `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `SORT` INT(11) NOT NULL DEFAULT '500',
            `IMAGE_ID` INT(11) UNSIGNED NULL DEFAULT '0',
            `IMAGE_BG_ID` INT(11) UNSIGNED NULL DEFAULT '0',
            `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'N',
            `ALIAS` VARCHAR(255) NULL DEFAULT NULL,
            `NAME` VARCHAR(255) NOT NULL,
            `BODY_TEXT_TYPE` VARCHAR(4) NULL DEFAULT 'html',
            `PREVIEW_TEXT_TYPE` VARCHAR(4) NULL DEFAULT 'html',
            `COMMENT` VARCHAR(255) NULL DEFAULT NULL,
            `BODY` TEXT NULL,
            `PREVIEW` TEXT NULL,
            PRIMARY KEY (`ID`),
            INDEX `SORT` (`SORT`),
            INDEX `ACTIVE` (`ACTIVE`),
            INDEX `ALIAS` (`ALIAS`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");

        $this->getConnection()->query("CREATE TABLE `kelnik_refbook_team` (
            `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `SORT` INT(11) NOT NULL DEFAULT '500',
            `IMAGE_ID` INT(11) UNSIGNED NULL DEFAULT '0',
            `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'N',
            `NAME` VARCHAR(255) NULL DEFAULT NULL,
            `TEXT` VARCHAR(255) NULL DEFAULT NULL,
            PRIMARY KEY (`ID`),
            INDEX `SORT` (`SORT`),
            INDEX `ACTIVE` (`ACTIVE`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB");

        $this->getConnection()->query("CREATE TABLE `kelnik_refbook_docs` (
            `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `SORT` INT(11) NOT NULL DEFAULT '500',
            `FILE_ID` INT(11) UNSIGNED NULL DEFAULT '0',
            `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'N',
            `NAME` VARCHAR(255) NULL DEFAULT NULL,
            PRIMARY KEY (`ID`),
            INDEX `SORT` (`SORT`),
            INDEX `ACTIVE` (`ACTIVE`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB");

        //$this->InstallFiles();
    }

    public function DoUninstall()
    {
        Loader::includeModule($this->MODULE_ID);

        $this->getConnection()->dropTable(\Kelnik\Refbook\Model\PartnerTable::getTableName());
        $this->getConnection()->dropTable(\Kelnik\Refbook\Model\ResidentTable::getTableName());
        $this->getConnection()->dropTable(\Kelnik\Refbook\Model\ResidentTypesTable::getTableName());
        $this->getConnection()->dropTable(\Kelnik\Refbook\Model\ReviewTable::getTableName());
        $this->getConnection()->dropTable(\Kelnik\Refbook\Model\TeamTable::getTableName());
        $this->getConnection()->dropTable(\Kelnik\Refbook\Model\DocsTable::getTableName());

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
