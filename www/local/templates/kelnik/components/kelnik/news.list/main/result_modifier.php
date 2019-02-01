<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (empty($arResult['ELEMENTS'])) {
    return;
}

foreach ($arResult['ELEMENTS'] as &$v) {
    if ($v['DATE_SHOW'] instanceof \Bitrix\Main\Type\DateTime) {
        $v['DATE_SHOW_FORMAT'] = mb_strtolower(FormatDate('j F', $v['DATE_SHOW']));
    }
}
unset($v);

$arResult['ELEMENTS'] = \Kelnik\Helpers\BitrixHelper::prepareFileFields($arResult['ELEMENTS'], ['IMAGE_PREVIEW']);