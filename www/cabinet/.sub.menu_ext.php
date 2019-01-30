<?php

if (!$aMenuLinks
    || !$USER->IsAuthorized()
) {
    return;
}

try {
    \Bitrix\Main\Loader::includeModule('kelnik.userdata');
} catch (Exception $e) {
    return;
}

$aMenuLinks = \Kelnik\Userdata\Admin::checkMenu($USER, $aMenuLinks);
