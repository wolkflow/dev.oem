<?php

namespace Wolk\OEM\Prices;

class Stand extends \Wolk\Core\System\HLBlockModel
{
    const HBLOCK_ID = HLBLOCK_PRICES_STANDS_ID;
    
    // Список полей.
    const FIELD_ID       = 'ID';
    const FIELD_EVENT    = 'UF_EVENT';
    const FIELD_STAND    = 'UF_STAND';
    const FIELD_PRICE    = 'UF_PRICE';
    const FIELD_CURRENCY = 'UF_CURRENCY';
    const FIELD_LANG     = 'UF_LANG';
    const FIELD_TYPE     = 'UF_TYPE';
    
    // Типы.
    const TYPE_STANDARD   = 'STANDARD';
    const TYPE_INDIVIDUAL = 'INDIVIDUAL';
	
	
	
    public function getEventID()
    {
        return $this->get(self::FIELD_EVENT);
    }
    
    
    public function getType()
    {
        return $this->get(self::FIELD_TYPE);
    }
	
	
	public function getCurrency()
    {
        return $this->get(self::FIELD_CURRENCY);
    }
    
    
    public function getLang()
    {
        return $this->get(self::FIELD_LANG);
    }
    
    
    public function getStandID()
    {
        return $this->get(self::FIELD_STAND);
    }
    
    
    public function getPrice()
    {
        return $this->get(self::FIELD_PRICE);
    }
    
    
    /**
     * —писок типов.
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_STANDARD,
            self::TYPE_INDIVIDUAL
        ];
    }
    
    
    /**
     * Удаление цен меропри¤ти¤ с выбранным типом дл¤ выбранного ¤зыка.
     */
    public static function clear($event, $type, $lang)
    {
        $class  = self::getEntityClassName();
        $entity = new $class();
        
        $connection = \Bitrix\Main\Application::getConnection();
        $connection->startTransaction();
        
        $query = "
            DELETE FROM `" . $entity->getTableName() . "`
            WHERE `" . self::FIELD_EVENT . "` = '" . strval($event) . "'
              AND `" . self::FIELD_TYPE . "`  = '" . strval($type) . "'
              AND `" . self::FIELD_LANG . "`  = '" . strval($lang) . "'
        ";
        
        $connection->query($query);
        $connection->commitTransaction();
    }
}