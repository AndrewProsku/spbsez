<?php
if (PHP_SAPI !== 'cli') {
    exit;
}

$docRoot  = realpath(__DIR__ . '/../../../');

if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = $docRoot;
}

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_STATISTIC', true);
define('STOP_STATISTICS', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/bitrixmanagedcache.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/sitemapgenerator.php';

ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

$siteMap = new SiteMapGenerator();

$siteMap->run();
echo $siteMap->getMap();
exit;