<?php

require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

// Запрос.
$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

// Вывод заказа в PDF
$APPLICATION->IncludeComponent(
	"wolk:order.print",
	"form-handing",
	array(
		'OID'  => intval($request->get('ID')),
		'LANG' => strval($request->get('LANG'))
	)
);

require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");