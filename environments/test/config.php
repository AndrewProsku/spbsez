<?php
return [
    'settings' => [
        'connections' => [
            'default' => [
                'host' => 'dev.ksdev.ru',
                'database' => 'c3oez_t',
                'login' => 'c3oez_t',
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
