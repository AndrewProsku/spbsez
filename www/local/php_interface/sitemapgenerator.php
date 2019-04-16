<?php

class SiteMapGenerator
{
    private $modules = [
        'kelnik.news',
        'kelnik.infrastructure'
    ];

    private $host = '';

    private $protocol = 'https';

    public function __construct($host, $protocol = 'https')
    {
        if (!$host) {
            throw new Exception('hostname required');
        }

        if (!$protocol || !in_array($protocol, ['http', 'https'])) {
            throw new Exception('protocol incorrect');
        }

        foreach ($this->modules as $module) {
            if (!\Bitrix\Main\Loader::includeModule($module)) {
               throw new \Bitrix\Main\LoaderException('Can\'t load module ' . $module);
            }
        }

        $this->host = $host;
        $this->protocol = $protocol;
    }

    protected function getBaseUrl()
    {
        return $this->protocol . '://' . $this->host . '/';
    }
}
