<?php 

namespace Wolk\OEM\Events;

class Sale
{
	public function OnOrderStatusSendEmail($ID, $val)
	{
		$order = \Bitrix\Sale\Order::load($ID);
		$userId = $order->getField('CREATED_BY');
		$user = \CUser::GetByID($userId)->Fetch();
		
		$eventIdPropValue = \Bitrix\Sale\Internals\OrderPropsValueTable::getRow([
			'filter' =>
				[
					'ORDER_ID' => $ID,
					'PROPERTY.CODE' => 'eventId'
				]
		]);

		$eventId = $eventIdPropValue['VALUE'];
		$obElement = \CIBlockElement::GetByID($eventId);
        $obEvent = $obElement->GetNextElement();
		$arEvent = $obEvent->GetFields();
		$arEvent['PROPS'] = $obEvent->GetProperties();
		$manager = \CUser::GetByID($arEvent['PROPS']['MANAGER']['VALUE'] ?: 1)->Fetch();
		$managerEmail = $manager['EMAIL'];

		$statuses = \Bitrix\Sale\Helpers\Admin\Blocks\OrderStatus::getStatusesList($GLOBALS['USER']->GetID());
		$status = $statuses[$val];
		$event = new \CEvent;
        $event->Send(
        	'SALE_NEW_ORDER_STATUS',
        	SITE_ID,
        	[
        		"ID" => $arFields['ORDER_ID'],
        		"STATUS" => $status,
        		"EMAIL" => $user['EMAIL'],
        		"MANAGER_EMAIL" => $managerEmail,
        	],
        	"N"
        );
	}
    
    
    /**
     * Сохранение заказа.
     */
    public function OnSaleOrderSaved(\Bitrix\Main\Event $event)
	{
        if (defined('ADMIN_SECTION')) {
            $order = $event->getParameter('ENTITY');
            
            if (is_object($order)) {
                $oemorder = new \Wolk\OEM\Order($order->getID());
                $oemorder->recalc();
            }
        }
    }
}