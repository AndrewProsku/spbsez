<?php

if (!$aMenuLinks || !$USER->IsAuthorized()) {
    return;
}

try {
    \Bitrix\Main\Loader::includeModule('kelnik.userdata');
    \Bitrix\Main\Loader::includeModule('kelnik.messages');
    \Bitrix\Main\Loader::includeModule('kelnik.requests');
    $aMenuLinks = \Kelnik\Userdata\Profile\Profile::getInstance($USER->GetID())->checkMenu($aMenuLinks);
} catch (Exception $e) {
    return;
}
