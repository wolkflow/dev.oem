<?php

session_id($_REQUEST['SID']);
session_start();

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
$template = "render." . $lang;

// Вывод заказа в PDF.
$APPLICATION->IncludeComponent(
	"wolk:basket.print",
	$template,
	array(
		'CODE' => strval($request->get('CODE')),
		'LANG' => $lang
	)
);

require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");