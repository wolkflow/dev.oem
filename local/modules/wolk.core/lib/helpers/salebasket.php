<?php

namespace Wolk\Core\Helpers;

class SaleBasket
{	
	public static function getProperties($id, $code = 'CODE')
	{
		if (!\Bitrix\Main\Loader::includeModule('sale')) {
			return;
		}
		
		$result = \CSaleBasket::GetPropsList(['SORT' => 'ASC'], ['BASKET_ID' => intval($id)]);
		$items  = [];
		while ($item = $result->Fetch()) {
		   $items[$item[$code]] = $item;
		}
		return $items;
	}

}