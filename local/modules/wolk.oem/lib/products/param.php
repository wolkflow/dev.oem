<?php

namespace Wolk\OEM\Products;

class Param extends \Wolk\Core\System\HLBlockModel
{
    const HBLOCK_ID = HLBLOCK_PARAMS_PRODUCTS_ID;
    
    // Список полей.
    const FIELD_ID      = 'ID';
    const FIELD_EVENT   = 'UF_EVENT';
    const FIELD_SECTION = 'UF_SECTION';
	const FIELD_LANG    = 'UF_LANG';
    const FIELD_PROPS   = 'UF_PROPS';
	const FIELD_NAMES   = 'UF_NAMES';
    const FIELD_NOTE    = 'UF_NOTE';
	
    
    
    public function getEventID()
    {
        return $this->get(self::FIELD_EVENT);
    }
    
    
    public function getSectionID()
    {
        return $this->get(self::FIELD_SECTION);
    }
    
    
    public function getProps()
    {
        return json_decode($this->get(self::FIELD_PROPS), true);
    }
	
	
	public function getNames()
    {
        return json_decode($this->get(self::FIELD_NAMES), true);
    }
    
	
	public function getNote()
    {
        return $this->get(self::FIELD_NOTE);
    }
	
	
	/**
     * Удаление данных продукции с выбранным типом для выбранного языка.
     */
    public static function clear($event, $lang)
    {
        $class  = self::getEntityClassName();
        $entity = new $class();
        
        $connection = \Bitrix\Main\Application::getConnection();
        $connection->startTransaction();
        
        $query = "
            DELETE FROM `" . $entity->getTableName() . "`
            WHERE `" . self::FIELD_EVENT . "` = '" . strval($event) . "'
              AND `" . self::FIELD_LANG . "`  = '" . strval($lang) . "'
        ";
        
        $connection->query($query);
        $connection->commitTransaction();
    }
}


