<?php

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;

try {
    Loader::includeModule('kelnik.imageresizer');

    if ($image = Kelnik\ImageResizer\Resizer::getResizedImage($_SERVER['REQUEST_URI'])) {
        \Kelnik\ImageResizer\Resizer::fileForceDownload($image);
    }
} catch (Exception $e) {
}

$notFound = \Bitrix\Main\Application::getDocumentRoot() . DIRECTORY_SEPARATOR . '404.php';

if (file_exists($notFound)) {
    require $notFound;
    exit;
}

header('404 Not Found');
exit;
