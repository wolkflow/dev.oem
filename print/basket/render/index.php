<?php

require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

// Запрос.
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();


// Сессия.
$stid = (int) $request->get('STID');

// Хранилище.
$storage = new Wolk\OEM\TempRenderStorage($stid);

// Языковая версия.
$lang = $storage->getLang();
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
		'DATA' => $storage->getData(),
		'LANG' => $storage->getLang(),
	)
);

require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
