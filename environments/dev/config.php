<?php
return [
    'settings' => [
        'connections' => [
            'default' => [
                'host' => 'dev.ksdev.ru',
                'database' => 'c3oez_d',
                'login' => 'c3oez_d',
                'password' => 'rct@S8SsUJ4',
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
