<?php

if (!include_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/kelnik.admin_helper/admin/elfinder.php') {
    if (!include_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/kelnik.admin_helper/admin/elfinder.php') {
        include $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/404.php';
    }
}
