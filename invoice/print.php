<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

// ����� ����� � PDF
$APPLICATION->IncludeComponent(
	'wolk:invoice',
	'',
	array(
		'ORDER_ID' => (int) $_REQUEST['ID'],
		'TEMPLATE' => (string) $_REQUEST['TPL']
	)
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>