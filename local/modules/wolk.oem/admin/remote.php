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
		
		$order    = new Wolk\OEM\Order($order_id);
		$customer = $order->getUser();
		$invoice  = new Wolk\OEM\Invoice($order_id, $template, $customer['WORK_COMPANY'], $customer['UF_CLIENT_NUMBER']);
		
		// Печать счета.
		$result = $invoice->make();
		
		if ($result !== 0) {
			jsonresponse(false, 'Ошибка создания счета');
		}
		$invoiceID = CFile::SaveFile(CFile::MakeFileArray($invoice->getInvoice()));
		
		\Wolk\Core\Helpers\SaleOrder::saveProperty($order_id, 'INVOICE', $invoiceID);
		
		if (strtotime($order->getInvoiceDate()) <= 0)  {
			\Wolk\Core\Helpers\SaleOrder::saveProperty($order_id, 'INVOICE_DATE', date('d.m.Y'));
		}
		
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
        
        
    // Выбор пользователя.
    case ('select-user'):
        $query = (string) $_REQUEST['query'];
        
        $users = [];
        
        $result = CUser::getList(
            ($b = 'ID'),
            ($o = 'DESC'), 
            ['WORK_COMPANY' => '%'.$query.'%'],
            //[['LOGIC' => 'OR', ['NAME' => '%'.$query.'%'], ['LAST_NAME' => '%'.$query.'%'], ['WORK_COMPANY' => '%'.$query.'%']]],
            ['FIELDS' => ['ID', 'EMAIL', 'NAME', 'LAST_NAME', 'WORK_COMPANY'], 'NAV_PARAMS' => ['nTopCount' => 15]]
        );
        while ($user = $result->fetch()) {
            $user['FULLNAME'] = trim($user['WORK_COMPANY'] . ' | ' . $user['NAME'] . ' ' . $user['LAST_NAME']);
            
            $users[$user['ID']] = $user;
        }
        unset($result);
        
        jsonresponse(true, '', ['users' => $users]);
        break;
}




