<?php

namespace Wolk\Core\Helpers;

\Bitrix\Main\Loader::includeModule('iblock');

class IBlockElement
{	
	public static function getIDByCode($iblockID, $code)
	{
		$feilds = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => intval($iblockID), 'CODE' => strval($code)), false, array('nTopCount' => 1), array('ID'))->fetch();
        
		return $feilds['ID'];
	}
    
    
	public static function getByCode($iblockID, $code)
	{
		$result  = null;
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
	
    
     public static function getSectionTree($id, $sectionID = null)
    {
        if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			return;
		}
        $sections = array();
        
        if (is_null($sectionID)) {
            $element   = \CIBlockElement::getByID($id)->fetch();
            $sectionID = $element['IBLOCK_SECTION_ID'];
        }
        
        $result = \CIBlockSection::GetNavChain(false, $sectionID);
        while ($section = $result->fetch()) {
            $sections[$section['ID']]= $section;
        }
        
        return $sections;
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