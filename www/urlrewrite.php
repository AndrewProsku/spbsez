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
  3 => 
  array (
    'CONDITION' => '#^(/|/en/)media/articles/#',
    'RULE' => '',
    'ID' => 'kelnik:news',
    'PATH' => LANG_DIR . 'media/articles/index.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^(/|/en/)media/news/#',
    'RULE' => '',
    'ID' => 'kelnik:news',
    'PATH' => LANG_DIR . 'media/news/index.php',
    'SORT' => 100,
  ),
);
