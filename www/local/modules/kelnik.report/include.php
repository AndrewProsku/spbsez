<?php
ini_set('max_execution_time', 900);
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
