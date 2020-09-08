<?php

namespace Kelnik\Text;

use Bex\Bbc\Basis;
use Bitrix\Main\Localization\Loc;
use Kelnik\Text\Blocks\BlocksTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) {
    return false;
}

Loc::loadMessages(__FILE__);

class TextBlocks extends Basis
{
    protected $needModules = ['kelnik.text'];
    protected $checkParams = [
        'CODE' => ['type' => 'string']
    ];

    protected function executeProlog()
    {
        global $APPLICATION;

        if (!$APPLICATION->GetShowIncludeAreas()) {
            return;
        }

        $this->cacheTemplate = false;
        $this->arParams['CACHE_TYPE'] = 'N';
    }

    protected function executeMain()
    {
        global $APPLICATION;

        $this->arResult['BLOCK'] = BlocksTable::getRow([
            'filter' => [
                '=CODE' => $this->arParams['CODE']
            ]
        ]);

        $this->arResult['SHOW_NOTICE'] = $APPLICATION->GetShowIncludeAreas();

        if ($APPLICATION->GetShowIncludeAreas()) {
            if (empty($this->arResult['BLOCK']['ID'])) {
                $this->arResult['BLOCK']['ID'] = BlocksTable::createRowByCode($this->arParams['CODE']);
            }

            if (empty($this->arResult['BLOCK']['ID'])) {
                return;
            }

            $rowID = $this->arResult['BLOCK']['ID'];

            $this->AddIncludeAreaIcon([
                'URL' => 'javascript:'.$APPLICATION->GetPopupLink([
                        'URL' => '/bitrix/admin/admin_helper_route.php?lang=' . LANGUAGE_ID
                            . "&module=kelnik.text&view=blocks_edit&ID={$rowID}&entity=blocks&bxpublic=Y&site="
                            . SITE_ID . '&back_url=' . urlencode($_SERVER['REQUEST_URI']),
                        'PARAMS' => [
                            'width' => 850,
                            'height' => 620,
                            'min_width' => 770,
                            'min_height' => 570,
                            'resizable' => true,
                            'dialog_type' => 'EDITOR',
                        ]
                    ]),
                'DEFAULT'   => $APPLICATION->GetPublicShowMode() != 'configure',
                'ICON'      => "bx-context-toolbar-edit-icon",
                'TITLE'     => Loc::getMessage('KELNIK_TEXTBLOCKS_PUBLIC_EDIT'),
                'ALT'       => Loc::getMessage('KELNIK_TEXTBLOCKS_PUBLIC_EDIT')
            ]);
        }
    }
}
