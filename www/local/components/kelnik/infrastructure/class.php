<?php

namespace Kelnik\Infrastructure\Component;

use Bex\Bbc\BasisRouter;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

class InfrastructureRouter extends BasisRouter
{
    protected $defaultSefPage = 'index';
    protected $cacheTemplate = false;

    protected function setSefDefaultParams()
    {
        $this->defaultUrlTemplates404 = [
            'index' => '',
            'section' => '',
            'map' => 'map/',
            'detail' => '#ELEMENT_CODE#/'
        ];

        $this->componentVariables = [
            'ELEMENT_ID', 'ELEMENT_CODE'
        ];
    }

    public function return404($notifier = false, \Exception $exception = null)
    {
        try {
            Loader::includeModule('iblock');
            Tools::process404(
                'Not Found',
                true,
                true,
                true
            );
        } catch (\Exception $e) {
            parent::return404($notifier, $exception);
        }
    }
}
