<?php

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;
use Bitrix\Main\ArgumentException;

use Wolk\OEM\API\Engine;

global $APPLICATION;

if (!Loader::includeModule('wolk.oem')) {
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Retry-After: 1800');
    die();
}

try {
    $engine = new Engine($_REQUEST);
} catch (Exception $e) {
    Engine::response(false, array('error' => $e->getMessage(), 'code' => $e->getCode()));
}

try {
    $result = $engine->exec();
} catch (Exception $e) {
    Engine::response(false, array('error' => $e->getMessage(), 'code' => $e->getCode()));
}

Engine::response(true, array('data' => $result));

//print_r($_REQUEST);

/*
$login  = 'test';
$secret = '62a11257d24e7b577b87936eaf7931a0f15bc7b4e52f2c37507b7792c38b6d0c';
$method = 'test';

$data = array(
    'login'  => $login,
    'method' => $method,
    'data'   => array()
);

// Добавление подписи.
$signature = md5($method . json_encode($data) . $secret);

$data['sign'] = $signature;
*/

