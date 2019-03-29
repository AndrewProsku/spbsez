<?php

namespace Kelnik\Api;

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;

Loc::loadLanguageFile(__FILE__);

class Api
{
    protected static $instance;
    protected $event;
    protected $lang;
    protected $errors = [];
    protected $data = [];

    /**
     * События не требующие авторизации
     *
     * @var array
     */
    protected $freeEvents = [
        'login',
        'forgot',
        'changePassword',
        'vacancy',
        'service',
        'message',
        'news',
        'infoDocs',
        'infoProc'
    ];

    protected $requireModules = [
        'kelnik.userdata' => [
            'profile'
        ],
        'kelnik.vacancy' => [
            'vacancy'
        ],
        'kelnik.requests' => [
            'message',
            'service'
        ],
        'kelnik.messages' => [
            'messages'
        ],
        'kelnik.news' => [
            'news'
        ],
        'kelnik.report' => [
            'report',
            'profile'
        ],
        'kelnik.info' => [
            'infoDocs',
            'infoProc'
        ]
    ];

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
        $this->lang  = ArrayHelper::getValue($_REQUEST, 'lang', LANGUAGE_ID);

        if ($this->lang !== LANGUAGE_ID) {
            Context::getCurrent()->setLanguage($this->lang);
        }

        if (!$this->event) {
            $this->errors[] = Loc::getMessage('KELNIK_API_EVENT_REQUIRED');
        }

        if (!in_array($this->event, $this->freeEvents) && !$USER->IsAuthorized()) {
            $this->errors[] = Loc::getMessage('KELNIK_API_AUTH_REQUIRED');
            die($this->getResponse());
        }

        $classNamespace = '\Kelnik\Api\Process\ApiProcess' . ucfirst($this->event);

        if (!method_exists($classNamespace, 'execute')) {
            $this->errors[] = Loc::getMessage('KELNIK_API_EVENT_NOT_EXISTS');
        }

        if ($this->requireModules) {
            foreach ($this->requireModules as $module => $events) {
                if (!in_array($this->event, $events)) {
                    continue;
                }
                if (!\CModule::IncludeModule($module)) {
                    $this->errors[] = Loc::getMessage('KELNIK_API_INTERNAL_ERROR');
                }
            }
        }

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

    public static function getUserIp()
    {
        $fields = [
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ];

        $res = '';

        foreach ($fields as $v) {
            if ($res || empty($_SERVER[$v])) {
                continue;
            }

            $res = $_SERVER[$v];

            if (false !== strpos($res, ',')) {
                $res = array_shift(explode(',', $res));
            }
        }

        return $res;
    }

    public static function getUserHash()
    {
        return md5(
            implode(
                '|',
                [
                    Context::getCurrent()->getRequest()->getUserAgent(),
                    self::getUserIp()
                ]
            )
        );
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
