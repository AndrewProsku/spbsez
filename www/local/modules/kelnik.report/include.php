<?php

$requiredModules = include 'require.php';


if (!$requiredModules) {
    return;
}

$isAdminSection = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAdminSection();

foreach ($requiredModules as $moduleName => $moduleParams) {
    if (isset($moduleParams['excludeAdminSection'])
        && $moduleParams['excludeAdminSection'] === true
        && $isAdminSection
    ) {
        continue;
    }
    \Bitrix\Main\Loader::includeModule($moduleName);
}
