<?php

$jediEnv = [];
$envDir  = 'environments';

$jediConfig = is_readable('.jedi.php')
    ? include '.jedi.php'
    : false;

if (isset($jediConfig['env-dir'])) {
    $envDir = $jediConfig['env-dir'];
}

$envConfig = is_readable($envDir . DIRECTORY_SEPARATOR . 'index.php')
    ? include $envDir . DIRECTORY_SEPARATOR . 'index.php'
    : false;

$envConfig = $envConfig === false || !is_array($envConfig)
    ? ['dev', 'prod']
    : array_keys($envConfig);

foreach ($envConfig as $type) {
    $jediEnv[$type] = [];
    $path = implode(
        DIRECTORY_SEPARATOR,
        [
            $envDir,
            $type,
            'config.php'
        ]
    );

    if (!is_readable($path)) {
        continue;
    }

    $jediEnv[$type] = include $path;
    $jediEnv[$type] = $jediEnv[$type]['settings']['connections']['default'];
}

$res = [
    'paths' => [
        'migrations' => 'migrations',
        'seeds' => 'seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev'
    ],
    'version_order' => 'creation'
];

if (is_readable('env.lock') && $envName = file_get_contents('env.lock')) {
    $res['environments']['default_database'] = $envName;
}

foreach ($jediEnv as $type => $conf) {
    $res['environments'][$type] = [
        'adapter' => 'mysql',
        'host' => $conf['host'],
        'name' => $conf['database'],
        'user' => $conf['login'],
        'pass' => $conf['password'],
        'port' => 3306,
        'charset' => 'utf8'
    ];
}

return $res;
