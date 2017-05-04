<?php

namespace Wolk\Core\Helpers;

class IBlock
{
	protected static $iblocks;
	
	
	/**
     * Получение инфоблока по коду.
     */
    public static function getByCode($code)
    {
        $code = (string) $code;
        
        if (!isset(self::$iblocks[$code])) {
    		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    			return;
    		}
    		$db = \CIBlock::GetList(array(), array('CODE' => strval($code)), true);
    		if ($iblock = $db->Fetch()) {
    			self::$iblocks[$code] = $iblock;
    		}
        }
        return self::$iblocks[$code];
    }
	
    
    /**
     * Получение ID по коду.
     */
	public static function getIdByCode($code)
	{
		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			return;
		}
		$iblock = self::getByCode($code);
		
		return intval($iblock['ID']);
	}
	
	
	/**
     * Получение кода по ID.
     */
	public static function getCodeById($id)
	{
		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			return;
		}
		$iblock = \CIBlock::getByID($id)->Fetch();
		
		return strval($iblock['CODE']);
	}
    
    
    /**
	 * Получение свойств.
	 */
	public static function getProps($id, $key = 'CODE') 
	{
		$db = \CIBlockProperty::GetList(array(), array('IBLOCK_ID' => $id));
		
		$props = array();
		while ($prop = $db->Fetch()) {
			$props[$prop[$key]] = $prop;
		}
		return $props;
	}
}