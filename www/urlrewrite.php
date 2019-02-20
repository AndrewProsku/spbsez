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
    'CONDITION' => '#^/cabinet/messages/#',
    'RULE' => '',
    'ID' => 'kelnik:messages',
    'PATH' => '/cabinet/messages/index.php',
    'SORT' => 100,
  ),
);
