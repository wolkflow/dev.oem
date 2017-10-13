<?php

require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

// Запрос.
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();


// Языковая версия.
$lang = strval($request->get('LANG'));
if (empty($lang)) {
	$lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
}
$lang = strtolower($lang);


// Шаблон вывода.
$template = "form-handing." . $lang;

// Вывод заказа в PDF.
$APPLICATION->IncludeComponent(
	"wolk:order.print",
	$template,
	array(
		'OID'  => intval($request->get('ID')),
		'LANG' => $lang,
	)
);

require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");