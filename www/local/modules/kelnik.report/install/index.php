<?php

use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

IncludeModuleLangFile(__FILE__);

class kelnik_report extends CModule
{
    public $MODULE_ID = 'kelnik.report';
    public $MODULE_GROUP_RIGHTS = 'Y';

    public function __construct()
    {
        $arModuleVersion = array();

        include(__DIR__ . DIRECTORY_SEPARATOR . 'version.php');

        if (
            is_array($arModuleVersion)
            && array_key_exists('VERSION', $arModuleVersion)
        ) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_NAME = Loc::getMessage('KELNIK_REPORT_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('KELNIK_REPORT_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('KELNIK_REPORT_PARTNER_NAME');
        $this->PARTNER_URI = 'http://kelnik.ru';
    }

    public function DoInstall()
    {
        if (!$this->checkDependencies()) {
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);
        Loader::includeModule($this->MODULE_ID);

        self::InstallFiles();
        self::InstallDB();
    }

    public function DoUninstall()
    {
        Loader::includeModule($this->MODULE_ID);

        self::UnInstallDB();
        self::UnInstallFiles();

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    protected function getConnection()
    {
        return Application::getInstance()->getConnection();
    }

    public function InstallFiles()
    {
        foreach (['js', 'css'] as $folder) {
            CopyDirFiles(
                __DIR__ . DIRECTORY_SEPARATOR . $folder,
                implode(
                    DIRECTORY_SEPARATOR,
                    [
                        Application::getDocumentRoot(),
                        'bitrix',
                        $folder,
                        $this->MODULE_ID
                    ]
                ),
                true,
                true
            );
        }
    }

    public function UnInstallFiles()
    {
        foreach (['js', 'css'] as $folder) {
            DeleteDirFilesEx(
                implode(
                    DIRECTORY_SEPARATOR,
                    [
                        Application::getDocumentRoot(),
                        'bitrix',
                        $folder,
                        $this->MODULE_ID
                    ]
                )
            );
        }
    }

    public function InstallDB()
    {
        $this->runSql('install.sql');
    }

    public function UnInstallDB()
    {
        $this->runSql('uninstall.sql');
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

    protected function runSql($sqlFileName)
    {
        global $DB;

        $sqlPath = $this->getSqlPath($sqlFileName);

        $errors = file_exists($sqlPath)
            ? $DB->RunSQLBatch($sqlPath)
            : [Loc::getMessage('KELNIK_REPORT_FILE_NOT_FOUND', ['#FILE#' => $sqlFileName])];

        if ($errors !== false) {
            try {
                throw new \Bitrix\Main\SystemException(implode('', $errors));
            } catch (\Bitrix\Main\SystemException $e) {
                echo $e->getMessage();
            }

            return false;
        }
    }

    protected function getSqlPath($sqlFileName)
    {
        global $DB;

        if (!$sqlFileName) {
            return '';
        }

        return implode(
            DIRECTORY_SEPARATOR,
            [
                realpath(__DIR__),
                'db',
                $DB->type,
                $sqlFileName
            ]
        );
    }
}
