<?php

include($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

/**
 * Ответ в формате JSON.
 */
function jsonresponse($status, $message = '', $data = null, $type = 'html')
{
	$result = array(
		'status'  => (bool) $status,
		'message' => (string) $message,
		'data' 	  => $data
	);
	
	header('Content-Type: application/json');
	echo json_encode($result);
	exit();
}


/*
 * Данные запроса.
 */
$action = (string) $_REQUEST['action'];

switch ($action) {
	
	/*
	 * Печать счета.
	 */
	case ('invoice-print'):
		$order_id = (int) $_REQUEST['oid'];
		$template = (string) $_REQUEST['tpl'];
		
		if (!\Bitrix\Main\Loader::includeModule('wolk.oem')) {
			jsonresponse(false, 'Ошибка выполнения: модуль не установлен');
		}
		
		$invoice = new Wolk\OEM\Invoice($order_id, $template);
		
		// Печать счета.
		$result = $invoice->make();
		
		if ($result !== 0) {
			jsonresponse(false, 'Ошибка создания счета');
		}
		
		jsonresponse(true, '', ['link' => $invoice->getInvoice()]);
		
		break;
	
}