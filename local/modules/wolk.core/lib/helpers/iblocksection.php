<?php

namespace Wolk\Core\Helpers;

class IBlockSection
{
	protected static $sections = array();
	
	
	/**
	 * Получение списка секций по ID элемента.
	 *
	 * @param int $ID
	 */
	public static function GetSectionPathByElement($ID)
	{
		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			return;
		}
		$result = array();
	
		$element = \CIBlockElement::GetByID(intval($ID))->Fetch();
	
		if ($element['IBLOCK_SECTION_ID'] > 0) {
			$section = \CIBlockSection::GetByID($element['IBLOCK_SECTION_ID'])->Fetch();
				
			if ($section) {
				$sections[$section['ID']] = $section;
				while ($section = \CIBlockSection::GetByID($section['IBLOCK_SECTION_ID'])->Fetch()) {
					$sections[$section['ID']] = $section;
				}
			}
			$result []= $sections;
		}
		return $result;
	}
	
	
	/**
	 * Получение списка секций по ID раздела.
	 *
	 * @param int $ID
	 */
	public static function GetSectionPathByParentID($ID)
	{
		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			return;
		}
		$result = array();
	
		$ID = (int) $ID;
	
		if ($ID > 0) {
			$section = self::GetByID($ID);
				
			if ($section) {
				$sections[$section['ID']] = $section;
				while ($section = self::GetByID($section['IBLOCK_SECTION_ID'])) {
					$sections[$section['ID']] = $section;
				}
			}
			$result = $sections;
		}
		return $result;
	}
	
	
	/**
	 * Получение раздела по ID.
	 *
	 * @param int $ID
	 */
	public static function GetByID($ID)
	{
		$ID = (int) $ID;
	
		if (!self::$sections[$ID]) {
			self::$sections[$ID] = \CIBlockSection::GetByID($ID)->Fetch();
		}
		return self::$sections[$ID];
	}
}