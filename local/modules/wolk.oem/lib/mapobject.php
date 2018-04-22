<?php

namespace Wolk\OEM;

class MapObject extends \Wolk\Core\System\HLBlockModel
{
    const HBLOCK_ID = HLBLOCK_MAP_OBJECTS_ID;
    
    // Список полей.
    const FIELD_ID        = 'ID';
    const FIELD_NAME      = 'UF_NAME';
    const FIELD_TITLE_RU  = 'UF_LANG_TITLE_RU';
    const FIELD_TITLE_EN  = 'UF_LANG_TITLE_EN';
    const FIELD_TYPE      = 'UF_TYPE';
    const FIELD_COORD_LAT = 'UF_COORD_LAT';
    const FIELD_COORD_LNG = 'UF_COORD_LNG';
    const FIELD_PLACE     = 'UF_PLACE';
	
	const PREFIX_LANG_TITLE = 'UF_LANG_TITLE_';
    
	
	
	public function getName()
    {
        return $this->get(self::FIELD_NAME);
    }
	
	
	public function getTitle($lang = null)
	{
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
        return $this->get(self::PREFIX_LANG_TITLE . $lang);
    }

    
    public function getType()
    {
        return $this->get(self::FIELD_TYPE);
    }
	
	
	public function getTypeCode()
    {
		$types = self::getTypeList();
		
        return $types[$this->get(self::FIELD_TYPE)]['XML_ID'];
    }
	
	
	public function getCoordLat()
    {
        return $this->get(self::FIELD_COORD_LAT);
    }
    
    
    public function getCoordLng()
    {
        return $this->get(self::FIELD_COORD_LNG);
    }
    
    
    public function getPlaceID()
    {
        return $this->get(self::FIELD_PLACE);
    }
	
	
	public static function getTypeList()
	{
		$result	= \CUserFieldEnum::GetList([], ['USER_FIELD_ID' => HLBLOCK_PROPERTY_MAP_OBJECTS_TYPE_ID]);
		
		$items = [];
		while ($item = $result->fetch()) {
			$items[$item['ID']] = $item;
		}
		return $items;
	}
}