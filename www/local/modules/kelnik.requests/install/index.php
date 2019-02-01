<?php

use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Kelnik\Requests\Booking\BookingTable;
use Kelnik\Requests\Phone\PhoneTable;

IncludeModuleLangFile(__FILE__);

class kelnik_requests extends CModule
{
    public $MODULE_ID = 'kelnik.requests';
    public $MODULE_GROUP_RIGHTS = 'Y';

    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . '/version.php');

        if (
            is_array($arModuleVersion)
            && array_key_exists('VERSION', $arModuleVersion)
        ) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME        = Loc::getMessage('KELNIK_REQ_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('KELNIK_REQ_DESCRIPTION');
        $this->PARTNER_NAME       = Loc::getMessage('KELNIK_REQ_PARTNER_NAME');
        $this->PARTNER_URI        = 'http://kelnik.ru';
    }

    public function DoInstall()
    {
        try {
            ModuleManager::registerModule($this->MODULE_ID);
            Loader::includeModule($this->MODULE_ID);
        } catch (\Exception $e) {
            ShowError(Loc::getMessage('KELNIK_REQ_ERROR') . $e->getMessage());
            return false;
        }

        $this->InstallDB();
        $this->InstallFiles();
    }

    public function DoUninstall()
    {
        try {
            Loader::includeModule($this->MODULE_ID);

            $this->UnInstallDB();
            $this->UnInstallFiles();
        } catch (Exception $e) {
        }

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    protected function getConnection()
    {
        return Application::getInstance()->getConnection(BookingTable::getConnectionName());
    }

    public function InstallFiles()
    {
    }

    public function UnInstallFiles()
    {
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
            : [Loc::getMessage('KELNIK_REQ_FILE_NOT_FOUND', ['#FILE#' => $sqlFileName])];

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
