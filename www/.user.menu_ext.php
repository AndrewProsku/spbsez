<?php

if (!$aMenuLinks || !$USER->IsAuthorized()) {
    return;
}

try {
    \Bitrix\Main\Application::getInstance()->getTaggedCache()->registerTag('bitrix:menuPersonal');
    \Bitrix\Main\Application::getInstance()->getTaggedCache()->registerTag('bitrix:menuPersonal_' . $USER->GetID());

    \Bitrix\Main\Loader::includeModule('kelnik.userdata');
    $aMenuLinks = \Kelnik\Userdata\Profile\Profile::getInstance((int)$USER->GetID())->checkMenu($aMenuLinks);
} catch (Exception $e) {
    return;
}
