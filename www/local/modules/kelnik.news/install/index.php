<?php

use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Kelnik\News\News\NewsTable;
use Kelnik\News\News\TagsTable;
use Kelnik\News\Categories\CategoriesTable;
use Kelnik\News\News\ImageToNewsTable;
use Kelnik\News\News\TagToNewsTable;

IncludeModuleLangFile(__FILE__);

class kelnik_news extends CModule
{
    public $MODULE_ID = 'kelnik.news';
    public $MODULE_GROUP_RIGHTS = 'Y';

    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . DIRECTORY_SEPARATOR . 'version.php');

        if (
            is_array($arModuleVersion)
            && array_key_exists('VERSION', $arModuleVersion)
        ) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('KELNIK_NEWS_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('KELNIK_NEWS_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('KELNIK_NEWS_PARTNER_NAME');
        $this->PARTNER_URI = 'http://kelnik.ru';
    }

    public function DoInstall()
    {
        if (!$this->checkDependencies()) {
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);
        Loader::includeModule($this->MODULE_ID);

        $connection = $this->getConnection();
        $connection->query(
            "CREATE TABLE `" . CategoriesTable::getTableName() . "` (
                `ID` INT(11) NOT NULL AUTO_INCREMENT,
                `CREATED_BY` INT(11) NOT NULL,
                `MODIFIED_BY` INT(11) NOT NULL,
                `DATE_MODIFY` DATETIME NOT NULL,
                `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'N',
                `CODE` VARCHAR(255) NOT NULL,
                `NAME` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`ID`),
                INDEX `ACTIVE` (`ACTIVE`),
                INDEX `CODE` (`CODE`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
        );
        $connection->query(
            "CREATE TABLE `" . NewsTable::getTableName() . "` (
                `ID` INT(11) NOT NULL AUTO_INCREMENT,
                `CAT_ID` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `OBJECT_ID` INT(11) UNSIGNED NULL DEFAULT '0',
                `CREATED_BY` INT(11) NOT NULL,
                `MODIFIED_BY` INT(11) NOT NULL,
                `IMAGE` INT(11) UNSIGNED NULL DEFAULT '0',
                `IMAGE_PREVIEW` INT(11) UNSIGNED NULL DEFAULT '0',
                `DATE_CREATE` DATETIME NOT NULL,
                `DATE_SHOW` DATETIME NULL DEFAULT NULL,
                `DATE_ACTION_START` DATETIME NULL DEFAULT NULL,
                `DATE_ACTION_FINISH` DATETIME NULL DEFAULT NULL,
                `DATE_MODIFY` DATETIME NULL DEFAULT NULL,
                `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'Y',
                `CODE` VARCHAR(255) NOT NULL,
                `NAME` VARCHAR(255) NOT NULL,
                `TEXT_PREVIEW_TEXT_TYPE` VARCHAR(255) NOT NULL,
                `TEXT_TEXT_TYPE` VARCHAR(255) NOT NULL,
                `TEXT_PREVIEW` TEXT NULL,
                `TEXT` TEXT NULL,
                PRIMARY KEY (`ID`),
                INDEX `CAT_ID` (`CAT_ID`),
                INDEX `OBJECT_ID` (`OBJECT_ID`),
                INDEX `ACTIVE` (`ACTIVE`),
                INDEX `CODE` (`CODE`),
                INDEX `DATE_ACTION_START` (`DATE_ACTION_START`),
                INDEX `DATE_ACTION_FINISH` (`DATE_ACTION_FINISH`),
                INDEX `DATE_SHOW` (`DATE_SHOW`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
        );

        $connection->query(
            "CREATE TABLE `" . TagsTable::getTableName() . "` (
                `ID` INT(11) NOT NULL AUTO_INCREMENT,
                `SORT` INT(11) NOT NULL DEFAULT '500',
                `ACTIVE` ENUM('Y','N') NOT NULL DEFAULT 'Y',
                `NAME` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`ID`),
                INDEX `ACTIVE` (`ACTIVE`),
                INDEX `SORT` (`SORT`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB"
        );

        $connection->query(
            "CREATE TABLE `" . ImageToNewsTable::getTableName() . "` (
                `ID` INT(11) NOT NULL AUTO_INCREMENT,
                `ENTITY_ID` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `VALUE` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                PRIMARY KEY (`ID`),
                INDEX `ENTITY_ID` (`ENTITY_ID`),
                INDEX `VALUE` (`VALUE`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
        );

        $connection->query(
            "CREATE TABLE `" . TagToNewsTable::getTableName() . "` (
                `ID` INT(11) NOT NULL AUTO_INCREMENT,
                `ENTITY_ID` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `VALUE` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                PRIMARY KEY (`ID`),
                INDEX `ENTITY_ID` (`ENTITY_ID`),
                INDEX `VALUE` (`VALUE`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
        );

        //$this->InstallFiles();
    }

    public function DoUninstall()
    {
        Loader::includeModule($this->MODULE_ID);

        $this->getConnection()->dropTable(NewsTable::getTableName());
        $this->getConnection()->dropTable(TagsTable::getTableName());
        $this->getConnection()->dropTable(CategoriesTable::getTableName());
        $this->getConnection()->dropTable(ImageToNewsTable::getTableName());
        $this->getConnection()->dropTable(TagToNewsTable::getTableName());

        //$this->UnInstallFiles();

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    protected function getConnection()
    {
        return Application::getInstance()->getConnection();
    }

    public function InstallFiles()
    {
        return CopyDirFiles(
            __DIR__ . DIRECTORY_SEPARATOR . 'components',
            implode(
                DIRECTORY_SEPARATOR,
                [
                    Application::getDocumentRoot(),
                    'bitrix',
                    'components'
                ]
            ),
            true,
            true
        );
    }

    public function UnInstallFiles()
    {
        $dirs = $this->getPathComponents();

        if (!$dirs) {
            return true;
        }

        foreach ($dirs as $v) {
            DeleteDirFilesEx(
                implode(
                    DIRECTORY_SEPARATOR,
                    [
                        Application::getDocumentRoot(),
                        'bitrix',
                        'components',
                        'kelnik',
                        $v
                    ]
                )
            );
        }

        return true;
    }

    protected function getPathComponents()
    {
        $io = new \Bitrix\Main\IO\Directory(
            implode(
                DIRECTORY_SEPARATOR,
                [
                    __DIR__,
                    'components',
                    'kelnik'
                ]
            )
        );

        $res = [];

        if (!$child = $io->getChildren()) {
            return $res;
        }

        foreach ($child as $v) {
            $res[] = $v->getName();
        }

        return $res;
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
