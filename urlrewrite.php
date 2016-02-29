<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket/index.php",
	),
	array(
		"CONDITION" => "#^/events/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/events/index.php",
	),
	array(
		"CONDITION" => "#^/invoice/([\d]+)/([^\/]+)/#",
		"RULE" => "ID=$1&TPL=$2",
		"ID" => "",
		"PATH" => "/invoice/print.php",
	),
);

?>