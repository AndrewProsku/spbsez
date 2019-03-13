<?php
return [
    'settings' => [
        'connections' => [
            'default' => [
                'host' => 'ksdev.ru',
                'database' => 'oez-d',
                'login' => 'oez-d',
                'password' => 'q3rSx#16',
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
