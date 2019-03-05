<?php
return [
    'settings' => [
        'connections' => [
            'default' => [
                'host' => 'ksdev.ru',
                'database' => 'oez-t',
                'login' => 'oez-t',
                'password' => 'px5Uv56$',
                'className' => '\\Bitrix\\Main\\DB\\MysqliConnection',
                'options' => 2,
            ],
        ],
    ],
    'composer' => [
        'value' => [
            'config_path' => realpath(__DIR__ . '/../../composer.json')
        ]
    ]
];
