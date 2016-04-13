<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/*
if (!$USER->IsAdmin()) {
	CHTTP::SetStatus('403 Forbidden');
	exit();
}
*/

// Вывод заказа в PDF
$APPLICATION->IncludeComponent(
	'wolk:print.sketch',
	'',
	array(
		'ORDER_ID' => (int) $_REQUEST['ID']
	)
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>