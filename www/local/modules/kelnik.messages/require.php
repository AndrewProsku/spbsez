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
    'kelnik.userdata' => null,
    'kelnik.requests' => [
        'excludeAdminSection' => true
    ],
    'bex.bbc' => [
        'excludeAdminSection' => true
    ]
];
