<?php

define('NO_KEEP_STATISTIC',  true);
define('PULL_AJAX_INIT',     true);
define('PUBLIC_AJAX_MODE',   true);
define('NO_AGENT_STATISTIC', true);
define('NO_AGENT_CHECK',     true);
define('DisableEventsCheck', true);

require ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');


// Запрос.
$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();


$index    = (int)    $request->get('index');
$pid      = (int)    $request->get('pid');
$eid      = (int)    $request->get('eid');
$code     = (string) $request->get('code');
$type     = (string) $request->get('type');
$kind     = (string) $request->get('kind');
$quantity = (float)  $request->get('quantity');
$params   = (array)  $request->get('params');


// Корзина.
$APPLICATION->IncludeComponent(
    "wolk:basket", 
    "side", 
    array(
        "EID"  => $eid,
        "CODE" => $code,
        "TYPE" => $type,
        "LANG" => \Bitrix\Main\Context::getCurrent()->getLanguage(),
    )
);
