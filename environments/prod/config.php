<?php
return [
    'settings' => [
        'connections' => [
            'default' => [
                'host' => 'localhost',
                'database' => 'spbsez',
                'login' => 'spbsez',
                'password' => 'a4e178c1!$90',
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
