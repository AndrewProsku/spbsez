<?php

namespace Kelnik\Api;

use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;

Loc::loadLanguageFile(__FILE__);

class Api
{
    protected static $instance;
    protected $event;
    protected $errors = [];
    protected $data = [];

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function listen()
    {
        global $USER;

        header('Content-type:application/json; charset=UTF-8');

        $this->event = ArrayHelper::getValue($_REQUEST, 'event', false);

        if (!$this->event) {
            $this->errors[] = Loc::getMessage('KELNIK_API_EVENT_REQUIRED');
        }

        if ($this->event !== 'login' && !$USER->IsAuthorized()) {
            $this->errors[] = Loc::getMessage('KELNIK_API_AUTH_REQUIRED');
            die($this->getResponse());
        }

        if (in_array($this->event, ['search', 'visual'])) {
            \CModule::IncludeModule('kelnik.exchange');
        }

        $classNamespace = '\Kelnik\Api\Process\ApiProcess' . ucfirst($this->event);

        if (!method_exists($classNamespace, 'execute')) {
            $this->errors[] = Loc::getMessage('KELNIK_API_EVENT_NOT_EXISTS');
        }

//        $requiredModules = [];
//
//        if ($requiredModules) {
//            foreach ($requiredModules as $requiredModule) {
//                if (!\CModule::IncludeModule($requiredModule)) {
//                    $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
//                }
//            }
//        }

        if (!$this->errors) {
            try {
                $processClass = new $classNamespace();
                $processResult = $processClass->getResult($_REQUEST);
                $this->errors = $processResult['errors'];
                $this->data = $processResult['data'];
            } catch (\Exception $e) {
                $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
                $this->errors[] = $e->getMessage();
            }
        }

        die($this->getResponse());
    }

    protected static function getDefaultResponse(): array
    {
        return BitrixHelper::getDefaultJson();
    }

    protected function getResponse(): string
    {
        $json = self::getDefaultResponse();

        $json['request']['status'] = (int)empty($this->errors);

        if ($this->errors) {
            $json['request']['errors'] = $this->errors;
        }

        $json['data'] = $this->data;

        return json_encode($json);
    }
}
