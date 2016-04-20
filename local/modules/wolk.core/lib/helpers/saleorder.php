<?php

namespace Wolk\Core\Helpers;

class SaleOrder
{
	public static function getProperties($id, $key = 'CODE')
	{
		if (!\Bitrix\Main\Loader::includeModule('sale')) {
			return;
		}
		
		$result = \CSaleOrderPropsValue::GetList(['SORT' => 'ASC'], ['ORDER_ID' => $id]);
		$items  = [];
		while ($item = $result->Fetch()) {
			$items[$item[$key]] = $item;
		}
		return $items;
	}
	
	
	public static function getProperty($id, $code, $key = 'CODE')
	{
		if (!\Bitrix\Main\Loader::includeModule('sale')) {
			return;
		}
		
		$result = \CSaleOrderPropsValue::GetList(['SORT' => 'ASC'], ['ORDER_ID' => $id, 'CODE' => $code]);
		$items  = [];
		if ($item = $result->Fetch()) {
			return $item;
		}
		return;
	}
	
	
	public static function saveProperty($id, $code, $value)
	{
		if (!\Bitrix\Main\Loader::includeModule('sale')) {
			return;
		}
		
		if ($prop = \CSaleOrderProps::GetList(array(), array('CODE' => $code))->Fetch()) {
			
			if ($propval = \CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $id, 'CODE' => $prop['CODE']))->Fetch()) {
				return \CSaleOrderPropsValue::Update($propval['ID'], array(
				   'NAME' 			=> $prop['NAME'],
				   'CODE' 			=> $prop['CODE'],
				   'ORDER_PROPS_ID' => $prop['ID'],
				   'ORDER_ID' 		=> $id,
				   'VALUE' 			=> $value,
				));
			} else {
				return \CSaleOrderPropsValue::Add(array(
				   'NAME' 			=> $prop['NAME'],
				   'CODE' 			=> $prop['CODE'],
				   'ORDER_PROPS_ID' => $prop['ID'],
				   'ORDER_ID' 		=> $id,
				   'VALUE' 			=> $value,
				));
			}
		}
	}
	
	
	public static function getBaskets($id, $withprops = true)
	{
		if (!\Bitrix\Main\Loader::includeModule('sale')) {
			return;
		}
		
		$result = \CSaleBasket::GetList(['SORT' => 'ASC'], ['ORDER_ID' => $id]);
		$items  = [];
		while ($item = $result->Fetch()) {
			if ($withprops) {
				$item['PROPS'] = SaleBasket::getProperties($item['ID']);
			}
			$item['SUMMARY_PRICE'] = $item['PRICE'] * $item['QUANTITY'];
			
			$items []= $item;
		}
		return $items;
	}
	
	
	public static function getStatuses()
	{
		if (!\Bitrix\Main\Loader::includeModule('sale')) {
			return;
		}
		
		$result = \CSaleStatus::GetList(['SORT' => 'ASC']);
		$items  = [];
		while ($item = $result->Fetch()) {
			$items[$item['ID']] = $item;
		}
		return $items;
	}
}