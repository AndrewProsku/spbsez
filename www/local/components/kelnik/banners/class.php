<?php
namespace Sportsoft\Components;

use Bex\Bbc;
use Sportsoft\Banner\Model\BannerTable;
use Sportsoft\Banner\Model\BannerPositionTable;

if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();

if (!\Bitrix\Main\Loader::includeModule('bex.bbc')) return false;

class Banners extends Bbc\Basis
{
    use Bbc\Traits\Elements;

    protected $needModules = ['iblock', 'sportsoft.banner'];

    protected $checkParams = [];

    public function onPrepareComponentParams($params)
    {
        return parent::onPrepareComponentParams($params);
    }

    protected function executeMain()
    {
        $this->arResult = [];
        
        $bannerPosition = BannerPositionTable::getList([
            'filter' => ['CODE' => $this->arParams['POSITION'], 'ACTIVE' => 'Y']
        ])->fetch();
        $positionId = $bannerPosition['ID'];

        $banner = BannerTable::getList([
            'filter' => ['POSITION_ID' => $positionId, 'ACTIVE' => 'Y']
        ])->fetch();
        $banner['IMAGE_PATH'] = \CFile::GetPath($banner['IMAGE']);

        if (SITE_DIR == '/en/') {
            $banner['NAME'] = $banner['NAME_EN'];
            $banner['SUBTITLE'] = $banner['SUBTITLE_EN'];
        }

        if (strlen($banner['NAME']) > 0) {
            $this->arResult = $banner;
        }        
        
        $this->setResultCacheKeys(['NAV_CACHED_DATA']);
    }
   
}