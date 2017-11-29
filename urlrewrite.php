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
		"CONDITION" => "#^/print/order/form-handing/([\\d]+)/([^\\/]+)/#",
		"RULE" => "ID=\$1&LANG=$2&",
		"ID" => "",
		"PATH" => "/print/order/form-handing/index.php",
	),
	array(
		"CONDITION" => "#^/print/order/personal/([\\d]+)/#",
		"RULE" => "ID=\$1&",
		"ID" => "",
		"PATH" => "/print/order/personal/index.php",
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
		"CONDITION" => "#^/events/([^\/]+)/([\\d]+)/#",
		"RULE" => "CODE=$1&STEP=$2&",
		"ID" => "",
		"PATH" => "/wizard/index.php",
	),
	array(
		"CONDITION" => "#^/events/([^\/]+)/#",
		"RULE" => "CODE=$1&",
		"ID" => "",
		"PATH" => "/wizard/index.php",
	),
	
	
    array(
		"CONDITION" => "#^/i/(.+?)/#",
		"RULE" => "src=$1&",
		"ID" => "",
		"PATH" => "/i.php",
	),
    array(
		"CONDITION" => "#^/i/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/i.php",
	),
);

?>