<?php

namespace Wolk\OEM\Prices;

class Product extends \Wolk\Core\System\HLBlockModel
{
    const HBLOCK_ID = HLBLOCK_PRICES_PRODUCTS_ID;
    
    // Список полей.
    const FIELD_ID       = 'ID';
    const FIELD_EVENT    = 'UF_EVENT';
    const FIELD_PRODUCT  = 'UF_PRODUCT';
    const FIELD_PRICE    = 'UF_PRICE';
    const FIELD_CURRENCY = 'UF_CURRENCY';
    const FIELD_TYPE     = 'UF_TYPE';
    const FIELD_LANG     = 'UF_LANG';
    
    
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
    
    
    public function getProductID()
    {
        return $this->get(self::FIELD_PRODUCT);
    }
    
    
    public function getPrice()
    {
        return $this->get(self::FIELD_PRICE);
    }
    
    
    /**
     * Список типов.
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_STANDARD,
            self::TYPE_INDIVIDUAL
        ];
    }
    
    
    /**
     * ”даление цен меропри¤ти¤ с выбранным типом дл¤ выбранного ¤зыка.
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