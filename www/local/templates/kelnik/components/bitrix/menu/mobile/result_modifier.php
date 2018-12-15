<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (empty($arResult)) {
    return;
}

$res = [];
$i = 0;
foreach ($arResult as $k => $v) {
    if ($v['IS_PARENT']) {
        $i = $k;
    }

    if ((int)$v['DEPTH_LEVEL'] === 1) {
        $res[$k] = $v;
        continue;
    }

    $res[$i]['CHILD'][$k] = $v;
}

$arResult = $res;