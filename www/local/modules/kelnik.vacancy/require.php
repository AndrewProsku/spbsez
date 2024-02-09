<?php

/**
 * Массив зависимостей модулей
 *
 * @return array
 * [
 *      moduleName => moduleParams
 * ]
 *
 * moduleParams = [
 *      'excludeAdminSection' - @boolean Не подключать модуль в секции админа
 * ]
 */

return [
    'kelnik.admin_helper' => null,
    'bex.bbc' => [
        'excludeAdminSection' => true
    ],
    'kelnik.helpers' => null,
    'kelnik.refbook' => null
];
