<?php

namespace Wolk\Core\Helpers;


class IBlockElement
{	
	
	/**
	 * Получение свойств.
	 */
	public static function getProps($iblockID, $id, $codes) 
	{
		$db = \CIBlockElement::GetProperty($iblockID, $id, array('CODE' => (array) $codes));
		
		$props = array();
		while ($prop = $db->Fetch()) {
			$props[$prop['CODE']] = $prop;
		}
		return $props;
	}
	
	
	/**
	 * Получение ссылки.
	 */
	public static function getDetailPage($ID)
	{
		$result = \CIBlockElement::getByID($ID)->GetNext();

		return $result['DETAIL_PAGE_URL'];
	}
}