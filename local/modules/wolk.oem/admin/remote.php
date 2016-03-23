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
		
		if (!\Bitrix\Main\Loader::includeModule('sale')) {
			jsonresponse(false, 'Ошибка выполнения: модуль не установлен');
		}
		
		if (!\Bitrix\Main\Loader::includeModule('wolk.oem')) {
			jsonresponse(false, 'Ошибка выполнения: модуль не установлен');
		}
		
		$order    = CSaleOrder::getByID($order_id);
		$customer = CUser::getByID($order['USER_ID'])->Fetch();
		
		$invoice = new Wolk\OEM\Invoice($order_id, $template, $customer['WORK_COMPANY'], $customer['UF_CLIENT_NUMBER']);
		
		// Печать счета.
		$result = $invoice->make();
		
		if ($result !== 0) {
			jsonresponse(false, 'Ошибка создания счета');
		}
		
		$invoices = \Wolk\Core\Helpers\SaleOrder::getProperty($order_id, 'INVOICES');
		$invoices = $invoices['VALUE_ORIG'];
		$invoices []= CFile::MakeFileArray($invoice->getInvoice());
		
		\Wolk\Core\Helpers\SaleOrder::saveProperty($order_id, 'INVOICES', $invoices);
		
		jsonresponse(true, '', ['link' => $invoice->getInvoice(), 'name' => $invoice->getFileName()]);
		break;
	
	
	/*
	 * Печать заказа.
	 */
	case ('order-print'):
		$order_id = (int) $_REQUEST['oid'];
		
		if (!\Bitrix\Main\Loader::includeModule('wolk.oem')) {
			jsonresponse(false, 'Ошибка выполнения: модуль не установлен');
		}
		
		$orderprint = new Wolk\OEM\OrderPrint($order_id);
		
		// Печать заказа.
		$result = $orderprint->make();
		
		if ($result !== 0) {
			jsonresponse(false, 'Ошибка создания документа заказа');
		}
				
		jsonresponse(true, '', ['link' => $orderprint->getOrderPrint()]);
		break;	
}




