<?php
$arUrlRewrite=array (
  2 => 
  array (
    'CONDITION' => '#^/print/order/form-handing/([\\d]+)/([\\d]+)/([^\\/]+)/#',
    'RULE' => 'OID=$1&BID=$2&LANG=$3&',
    'ID' => '',
    'PATH' => '/print/order/form-handing/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/print/basket/render/([^\\/]+)/([^\\/]+)/#',
    'RULE' => 'STID=$1&LANG=$2&',
    'ID' => '',
    'PATH' => '/print/basket/render/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/print/order/render/([\\d]+)/([^\\/]+)/#',
    'RULE' => 'ID=$1&LANG=$2&',
    'ID' => '',
    'PATH' => '/print/order/render/index.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/print/order/personal/([\\d]+)/#',
    'RULE' => 'ID=$1&',
    'ID' => '',
    'PATH' => '/print/order/personal/index.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/invoice/([\\d]+)/([^\\/]+)/#',
    'RULE' => 'ID=$1&TPL=$2',
    'ID' => '',
    'PATH' => '/invoice/print.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/events/([^\\/]+)/([\\d]+)/#',
    'RULE' => 'CODE=$1&STEP=$2&',
    'ID' => '',
    'PATH' => '/wizard/index.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/printsketch/([\\d]+)/#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/printsketch/print.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/printorder/([\\d]+)/#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/printorder/print.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/events/([^\\/]+)/#',
    'RULE' => 'CODE=$1&',
    'ID' => '',
    'PATH' => '/wizard/index.php',
    'SORT' => 100,
  ),
  10 => 
  array (
    'CONDITION' => '#^/i/(.+?)/#',
    'RULE' => 'src=$1&',
    'ID' => '',
    'PATH' => '/i.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/i/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/i.php',
    'SORT' => 100,
  ),
);
