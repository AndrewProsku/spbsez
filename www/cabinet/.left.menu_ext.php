<?php

if (!$aMenuLinks || !$USER->IsAuthorized()) {
    return;
}

try {
    \Bitrix\Main\Loader::includeModule('kelnik.userdata');
    $profile = new \Kelnik\Userdata\Profile\ProfileModel($USER->GetID());
    $aMenuLinks = $profile->checkMenu($aMenuLinks);
} catch (Exception $e) {
    return;
}
