<?php

if (!$aMenuLinks || !$USER->IsAuthorized()) {
    return;
}

try {
    \Bitrix\Main\Loader::includeModule('kelnik.userdata');
    $aMenuLinks = \Kelnik\Userdata\Profile\Profile::getInstance($USER->GetID())->checkMenu($aMenuLinks);
} catch (Exception $e) {
    return;
}
