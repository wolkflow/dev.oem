<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("System stand");
?><? $APPLICATION->IncludeComponent(
	"wolk:event.detail",
	"",
	[
		"EVENT_ID" => isset($_REQUEST['EVENT_ID']) ? intval($_REQUEST['EVENT_ID']) : false,
		"WIDTH"    => isset($_REQUEST['WIDTH']) ? intval($_REQUEST['WIDTH']) : null,
		"DEPTH"    => isset($_REQUEST['DEPTH']) ? intval($_REQUEST['DEPTH']) : null,
		"TYPE"    => isset($_REQUEST['TYPE']) ? intval($_REQUEST['TYPE']) : null,
	]
); ?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>