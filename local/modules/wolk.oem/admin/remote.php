<?php

use Bitrix\Sale\Internals\BasketTable;
use Bitrix\Sale\Internals\BasketPropertyTable;
use Bitrix\Sale\Internals\OrderTable;
use Bitrix\Sale\Internals\OrderPropsValueTable;

use Wolk\Core\Helpers\IBlockElement as IBlockElementHelper;

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
		
	
	
	// Создание заказа.
	case ('order-make'):
		$eventID    = intval($_REQUEST['EVENT']);
		$userID     = intval($_REQUEST['USER']);
		$standnum   = strval($_REQUEST['STANDNUM']);
		$pavilion   = strval($_REQUEST['PAVILLION']);
		$type       = strval($_REQUEST['TYPE']);
		$currency   = strval($_REQUEST['CURRENCY']);
		$language   = strval($_REQUEST['LANGUAGE']);
		$surcharge  = floatval($_REQUEST['SURCHARGE']);
		$includevat = intval($_REQUEST['VAT']);
		$products   = (array) $_REQUEST['PRODUCTS'];
        $standID    = intval($_REQUEST['STAND']);
        $standprice = floatval($_REQUEST['STANDPRICE']);
        $standwidth = intval($_REQUEST['STANDWIDTH']);
        $standdepth = intval($_REQUEST['STANDDEPTH']);
		
        
		\Bitrix\Main\Loader::includeModule('iblock');
		\Bitrix\Main\Loader::includeModule('sale');
		
        if (!\Bitrix\Main\Loader::includeModule('wolk.oem')) {
			jsonresponse(false, 'Ошибка выполнения: модуль не установлен');
		}
        
		$errors = [];
		
		if (empty($eventID)) {
			$errors['EVENT'] = 'Не укаазна выставка';
		}
		if (empty($userID)) {
			$errors['USER'] = 'Не укаазн участник';
		}
		if (empty($currency)) {
			$errors['LANGUAGE'] = 'Не укаазна валюта';
		}
		if (empty($language)) {
			$errors['SURCHARGE'] = 'Не укаазн язык';
		}
        if ($type != 'QUICK') {
            if (empty($standID)) {
                $errors['STAND'] = 'Не укаазн стенд';
            }
        }
		
		if (!empty($errors)) {
			jsonresponse(false, implode('<br/>', $errors), ['errors' => $errors]);
		}
		
		// Выставка.
		$event = CIBlockElement::GetByID($eventID)->fetch();
		
		if (!$event) {
			jsonresponse(false, 'Выставка не найдена');
		}
		
		if (!empty($products['IDS'])) {
			
			// Корзина.
			$basket = new CSaleBasket();
			
			// Удаление старых корзин.
            $basket->DeleteAll($userID);
			
			// Общая цена товаров.
			$summprice = 0;
            
            // Стенд.
            if ($type != 'QUICK') {
                $stand = CIBlockElement::GetByID($standID)->fetch();
                
                if ($stand) {
                    $result = BasketTable::add([
                        'PRODUCT_ID'    => $standID,
                        'PRICE'         => $standprice,
                        'QUANTITY'      => 1,
                        'CURRENCY'      => $currency,
                        'LID'           => SITE_DEFAULT,
                        'NAME'          => $stand['NAME'],
                        'SET_PARENT_ID' => 0,
                        'TYPE'          => CSaleBasket::TYPE_SET,
                        'FUSER_ID'      => $userID
                    ]);
                    
                    if (!$result->isSuccess()) {
                        $errors['BASKET'] [] = $result->getErrorMessages();
                    } else {
						$summprice = $standprice;
					}
                } else {
                    // jsonresponse(false, 'Стенд не найден');
                }
            } else {
                $standID = 0;
            }
            
			foreach ($products['IDS'] as $productID) {
				$title    = (string) $products['TITLE'][$productID];
				$price    = (float)  $products['PRICE'][$productID];
				$quantity = (float)  $products['QUANTITY'][$productID];
				$comment  = (string) $products['COMMENTS'][$productID];
				
				$summprice += ($price * $quantity);
				
				// Товарная позиция.
				$element = CIBlockElement::GetByID($productID)->getNextElement();
                $product = $element->getFields();
                $product['PROPS'] = $element->getProperties();
                
				unset($element);
                
                // Родительские раздел.
                $parent = reset(IBlockElementHelper::getSectionTree($product['ID'], $product['IBLOCK_SECTION_ID']));
                
                $title = $product['PROPS']['LANG_TITLE_'.strtoupper($language)]['VALUE'];
                
				$result = BasketTable::add([
					'PRODUCT_ID'    => $productID,
					'PRICE'         => $price,
					'QUANTITY'      => ($quantity) ?: 1,
					'CURRENCY'      => $currency,
					'LID'           => SITE_DEFAULT,
					'NAME'          => (!empty($title)) ? ($title) : ($product['NAME']),
					'SET_PARENT_ID' => $standID,
					'TYPE'          => ($parent['ID'] == ADDITIONAL_EQUIPMENT_SECTION_ID) ? (CSaleBasket::TYPE_SET) : (0),
					'FUSER_ID'      => $userID
				]);

				if (!$result->isSuccess()) {
					$errors['BASKET'] [] = $result->getErrorMessages();
				} else {
					if (!empty($comment)) {
						BasketPropertyTable::add([
                            'BASKET_ID' => $result->getID(),
                            'NAME'      => 'Комментарий',
                            'CODE'      => 'COMMENT',
                            'VALUE'     => $comment
                        ]);
					}
				}
			}
			
			if (!empty($errors)) {
				jsonresponse(false, implode('<br/>', $errors), ['errors' => $errors]);
			}
			
			$totalprice = $summprice;
			$surchprice = 0;
			
			// Наценка.
			if ($surcharge > 0) {
				$surchprice = $totalprice * $surcharge / 100;
				$totalprice = $totalprice + $surchprice;
			}
			
			// Налоги.
			if ($vat) {
				$vatprice = 0;
			} else {
				$vatprice   = $totalprice * VAT_DEFAULT / 100;
				$totalprice = $totalprice + $vatprice;
			}
			
            
			// Добавление заказа.
			$orderID = CSaleOrder::Add([
				'LID'              => SITE_DEFAULT,
				'PERSON_TYPE_ID'   => PERSON_TYPE_DETAULT,
				'PAYED'            => 'N',
				'CANCELED'         => 'N',
				'STATUS_ID'        => 'N',
				'DISCOUNT_VALUE'   => '',
				'USER_DESCRIPTION' => '',
				'PRICE'            => $totalprice,
				'CURRENCY'         => $currency,
				'USER_ID'          => $userID,
				'DELIVERY_ID'      => DELIVERY_DETAULT,
				'TAX_VALUE'        => $vatprice,
			]);
			
			if ($orderID > 0) {
				 $res = CSaleOrderProps::GetList([], [
                    'CODE' => ['eventId', 'eventName', 'LANGUAGE', 'pavillion', 'standNum', 'width', 'depth', 'SURCHARGE', 'SURCHARGE_PRICE', 'TYPE']
                ]);
				$orderprops = [];
                while ($orderprop = $res->Fetch()) {
                    $orderprops[$orderprop['CODE']] = $orderprop;
                }
				unset($res, $orderprop);
				
				// Свойства заказа.
				$props = [
					[
						'ORDER_ID'       => $orderID,
                        'ORDER_PROPS_ID' => $orderprops['TYPE']['ID'],
                        'NAME'           => $orderprops['TYPE']['NAME'] ?: 'Типа заказа',
                        'CODE'           => 'TYPE',
                        'VALUE'          => $type
					],
					[
						'ORDER_ID'       => $orderID,
                        'ORDER_PROPS_ID' => $orderprops['eventId']['ID'],
                        'NAME'           => $orderprops['eventId']['NAME'] ?: 'ID выставки',
                        'CODE'           => 'eventId',
                        'VALUE'          => $event['ID']
					],
					[
						'ORDER_ID'       => $orderID,
                        'ORDER_PROPS_ID' => $orderprops['eventName']['ID'],
                        'NAME'           => $orderprops['eventName']['NAME'] ?: 'Название выставки',
                        'CODE'           => 'eventName',
                        'VALUE'          => $event['NAME']
					],
					[
						'ORDER_ID'       => $orderID,
                        'ORDER_PROPS_ID' => $orderprops['LANGUAGE']['ID'],
                        'NAME'           => $orderprops['LANGUAGE']['NAME'] ?: 'Язык',
                        'CODE'           => 'LANGUAGE',
                        'VALUE'          => $language
					],
					[
						'ORDER_ID'       => $orderID,
                        'ORDER_PROPS_ID' => $orderprops['pavillion']['ID'],
                        'NAME'           => $orderprops['pavillion']['NAME'] ?: 'Павильон',
                        'CODE'           => 'pavillion',
                        'VALUE'          => $pavilion
					],
					[
						'ORDER_ID'       => $orderID,
                        'ORDER_PROPS_ID' => $orderprops['standNum']['ID'],
                        'NAME'           => $orderprops['standNum']['NAME'] ?: 'Номер стенда',
                        'CODE'           => 'standNum',
                        'VALUE'          => $standnum
					],
					[
						'ORDER_ID'       => $orderID,
                        'ORDER_PROPS_ID' => $orderprops['SURCHARGE']['ID'],
                        'NAME'           => $orderprops['SURCHARGE']['NAME'] ?: 'Наценка',
                        'CODE'           => 'SURCHARGE',
                        'VALUE'          => $surcharge
					],
					[
						'ORDER_ID'       => $orderID,
                        'ORDER_PROPS_ID' => $orderprops['SURCHARGE_PRICE']['ID'],
                        'NAME'           => $orderprops['SURCHARGE_PRICE']['NAME'] ?: 'Сумма наценки',
                        'CODE'           => 'SURCHARGE_PRICE',
                        'VALUE'          => $surchprice
					]
				];
                
                // НДС включен в стоимость заказа.
                if ($vat) {
                    $props [] = [
						'ORDER_ID'       => $orderID,
                        'ORDER_PROPS_ID' => $orderprops['INCLUDE_VAT']['ID'],
                        'NAME'           => $orderprops['INCLUDE_VAT']['NAME'] ?: 'НДС включен',
                        'CODE'           => 'INCLUDE_VAT',
                        'VALUE'          => 'Y'
					];
                }
                
                
                if (!empty($stand)) {
                    $props [] = [
						'ORDER_ID'       => $orderID,
                        'ORDER_PROPS_ID' => $orderprops['width']['ID'],
                        'NAME'           => $orderprops['width']['NAME'] ?: 'Ширина',
                        'CODE'           => 'width',
                        'VALUE'          => $standwidth
					];
                    
                    $props [] = [
						'ORDER_ID'       => $orderID,
                        'ORDER_PROPS_ID' => $orderprops['depth']['ID'],
                        'NAME'           => $orderprops['depth']['NAME'] ?: 'Шлубина',
                        'CODE'           => 'depth',
                        'VALUE'          => $standdepth
					];
                }
				
				
				// Добавление свойств заказа.
				foreach ($props as $prop) {
					OrderPropsValueTable::add($prop);
				}
				unset($props, $prop);
				
				
				// Привязка корзин к заказу.
				$baskets = BasketTable::getList([
					'filter' =>
						[
							'FUSER_ID' => $userID,
							'ORDER_ID' => null
						]
				])->fetchAll();
				
				foreach ($baskets as $basket) {
					$result = BasketTable::update($basket['ID'], ['ORDER_ID' => $orderID]);
					if (!$result->isSuccess()) {
						$errors['ORDERBASKET'] []= 'не удалось привязать корзину к заказу';
					}
				}
			} else {
				jsonresponse(false, 'Не удалось создать заказ');
			}
			
			if (!empty($errors)) {
				jsonresponse(false, implode('<br/>', $errors), ['errors' => $errors]);
			}
		}
		
		jsonresponse(true, '', ['ID' => $orderID]);
		break;
}




