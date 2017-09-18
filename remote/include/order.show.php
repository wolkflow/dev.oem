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


// Отображение данных заказа.
$APPLICATION->IncludeComponent(
    "wolk:order.detail",
    "popup-show",
    array(
        "OID"   => $request->get('oid'),
        "CHECK" => true,
    )		
);


