<?php

$leftMenuPath = \Bitrix\Main\Application::getDocumentRoot() . '/infrastructure/.left.menu_ext.php';

if (file_exists($leftMenuPath)) {
    include_once $leftMenuPath;
}
