<?php

require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

// Запрос.
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

// Вывод заказа на печать.
$APPLICATION->IncludeComponent(
	"wolk:order.print",
	"personal",
	array(
		'OID'   => intval($request->get('ID')),
		'CHECK' => 'Y'
	)
);

require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");