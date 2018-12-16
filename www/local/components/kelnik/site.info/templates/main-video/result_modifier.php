<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$videos = [
    'MAIN_VIDEO_MP4', 'MAIN_VIDEO_OGV', 'MAIN_VIDEO_WEBM'
];

$arResult['NO_VIDEO'] = false;

foreach ($videos as $v) {
    if (empty($arResult[$v])) {
        $arResult['NO_VIDEO'] = true;
        return;
    }
}

$arResult = array_shift(
    \Kelnik\Helpers\BitrixHelper::prepareFileFields([$arResult], ['MAIN_VIDEO_*'])
);