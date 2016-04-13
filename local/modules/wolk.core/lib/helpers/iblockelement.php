<?php

namespace Wolk\Core\Helpers;


class IBlockElement
{	
	
	public static function getByCode($iblockID, $code)
	{
		$resutl  = null;
		$element = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => intval($iblockID), 'CODE' => strval($code)), false, array('nTopCount' => 1))->getNextElement();
				
		if ($element) {
			$result = $element->getFields();
			$result['PROPERTIES'] = $element->getProperties();
		}
		return $result;
	}
	
	
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