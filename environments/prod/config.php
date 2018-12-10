<?php
return array (
  'settings' => 
  array (
    'connections' => 
    array (
      'default' => 
      array (
        'host' => 'ksdev.ru',
        'database' => 'oez',
        'login' => 'oez',
        'password' => 'Jhu6a13#',
        'className' => '\\Bitrix\\Main\\DB\\MysqliConnection',
        'options' => 2,
      ),
    ),
  ),
  'maximaster' => 
  array (
    'value' => 
    array (
      'tools' => 
      array (
        'twig' => 
        array (
          'debug' => false,
          'charset' => 'utf-8',
          'cache' => '/bitrix/cache/maximaster/tools.twig',
          'auto_reload' => false,
          'autoescape' => false,
          'extract_result' => false,
        ),
      ),
    ),
  ),
);
