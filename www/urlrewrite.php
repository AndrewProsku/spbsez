<?php
$arUrlRewrite=array (
  1 => 
  array (
    'CONDITION' => '#^/upload/resize_cache/resized/(.*)/?#',
    'RULE' => 'VIRTUAL_PATH=$0',
    'ID' => '',
    'PATH' => '/resizeimage.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^(/|/en/)cabinet/messages/#',
    'RULE' => '',
    'ID' => 'kelnik:messages',
    'PATH' => '/cabinet/messages/index.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^(/|/en/)cabinet/report/#',
    'RULE' => '',
    'ID' => 'kelnik:report',
    'PATH' => '/cabinet/report/index.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/en/media/articles/#',
    'RULE' => '',
    'ID' => 'kelnik:news',
    'PATH' => '/en/media/articles/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/media/articles/#',
    'RULE' => '',
    'ID' => 'kelnik:news',
    'PATH' => '/media/articles/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/infrastructure/#',
    'RULE' => '',
    'ID' => 'kelnik:infrastructure',
    'PATH' => '/infrastructure/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/en/media/news/#',
    'RULE' => '',
    'ID' => 'kelnik:news',
    'PATH' => '/en/media/news/index.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/media/news/#',
    'RULE' => '',
    'ID' => 'kelnik:news',
    'PATH' => '/media/news/index.php',
    'SORT' => 100,
  ),
);
