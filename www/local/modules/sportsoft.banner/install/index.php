<?php

use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadLanguageFile(__FILE__);

class sportsoft_banner extends CModule
{
    public $MODULE_ID = 'sportsoft.banner';
    public $MODULE_GROUP_RIGHTS = 'Y';

    public function __construct()
    {
        $arModuleVersion = [];

        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion)
            && array_key_exists('VERSION', $arModuleVersion)
        ) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('SPORTSOFT_BANNER_COMPLETE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('SPORTSOFT_BANNER_COMPLETE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('SPORTSOFT_BANNER_PARTNER_NAME');
        $this->PARTNER_URI = 'https://sportsoft.ru';
    }

    public function DoInstall()
    {
        global $DB, $APPLICATION, $step;

        if (!$this->checkDependencies()) {
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);
        Loader::includeModule($this->MODULE_ID);

        $this->InstallDB();
    }

    public function DoUninstall()
    {
        Loader::includeModule($this->MODULE_ID);

        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);

        $this->UnInstallDB();
    }

    public function InstallDB()
    {
        $this->runSql('install.sql');
    }

    public function UnInstallDB()
    {
        $this->runSql('uninstall.sql');
    }

    protected function runSql($sqlFileName)
    {
        global $DB;

        $sqlPath = $this->getSqlPath($sqlFileName);

        $errors = file_exists($sqlPath)
            ? $DB->RunSQLBatch($sqlPath)
            : [Loc::getMessage('REQ_FILE_NOT_FOUND', ['#FILE#' => $sqlFileName])];

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

    public function InstallFiles()
    {
    }

    public function UnInstallFiles()
    {
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
                'SPORTSOFT_BANNER_MODULES',
                [
                    '#MODULES#' => implode(', ', $result)
                ]
            )
        ));

        return false;
    }
}
