<?php

namespace Wolk\OEM\Prices;

class Product extends \Wolk\Core\System\HLBlockModel
{
    const IBLOCK_ID = HLBLOCK_PRICES_PRODUCTS_ID;
    
    // Список полей.
    const FIELD_ID       = 'ID';
    const FIELD_EVENT    = 'UF_EVENT';
    const FIELD_PRODUCT  = 'UF_PRODUCT';
    const FIELD_PRICE    = 'UF_PRICE';
    const FIELD_CURRENCY = 'UF_CURRENCY';
    const FIELD_LANG     = 'UF_LANG';
    const FIELD_TYPE     = 'UF_TYPE';
    
    // Типы.
    const TYPE_COMMON     = 'COMMON';
    const TYPE_INDIVIDUAL = 'INDIVIDUAL';
    
    
    
    public function getEventID()
    {
        return $this->get(self::FIELD_EVENT);
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
            self::TYPE_COMMON,
            self::TYPE_INDIVIDUAL
        ];
    }
    
}