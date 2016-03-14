<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/*
if (!$USER->IsAdmin()) {
	CHTTP::SetStatus('403 Forbidden');
	exit();
}
*/

// Вывод счета в PDF.
$APPLICATION->IncludeComponent(
	'wolk:print.invoice',
	'',
	array(
		'ORDER_ID' => (int) $_REQUEST['ID'],
		'TEMPLATE' => (string) $_REQUEST['TPL']
	)
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>