<?php

namespace Wolk\Core\System;

\Bitrix\Main\Loader::includeModule('iblock');

class IBlockSectionModel extends Model
{
	protected static $iblockID;
	
	
	/**
	 * Ïîëó÷åíèå ID èíôîáëîêà ğçäåëîâ.
	 */
	public static function getIBlockID()
	{
		return self::$iblockID;
	}
	
	
	/**
	 * Çàãğóçêà äàííûõ ıëåìåíòà.
	 *
	 * @param bool $force
	 * @return array
	 */
	public function load($force = false)
	{
		if (empty($this->data) || $force) {
			$element = \CIBlockSection::getList(
				array(), 
				array('IBLOCK_ID' => self::getIBlockID(), 'ID' => $this->getID()), 
				false, 
				array('UF_LANG_TITLE_RU', 'UF_LANG_TITLE_EN')
			)->getNext();
			
			if ($element) {
				$this->data = $element;
			}
		}
		return $this->data;
	}
}