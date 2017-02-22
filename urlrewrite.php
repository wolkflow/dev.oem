<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/invoice/([\\d]+)/([^\\/]+)/#",
		"RULE" => "ID=\$1&TPL=\$2",
		"ID" => "",
		"PATH" => "/invoice/print.php",
	),
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket/index.php",
	),
	array(
		"CONDITION" => "#^/printsketch/([\\d]+)/#",
		"RULE" => "ID=\$1",
		"ID" => "",
		"PATH" => "/printsketch/print.php",
	),
	array(
		"CONDITION" => "#^/printorder/([\\d]+)/#",
		"RULE" => "ID=\$1",
		"ID" => "",
		"PATH" => "/printorder/print.php",
	),
	array(
		"CONDITION" => "#^/events/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/events/index.php",
	),
);

?>