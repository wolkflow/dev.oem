<?php

namespace Wolk\OEM\Prices;

class Stand extends \Wolk\Core\System\HLBlockModel
{
    const IBLOCK_ID = HLBLOCK_PRICES_STANDS_ID;
    
    // Список полей.
    const FIELD_ID       = 'ID';
    const FIELD_EVENT    = 'UF_EVENT';
    const FIELD_STAND    = 'UF_STAND';
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
    
    
    public function getStandID()
    {
        return $this->get(self::FIELD_STAND);
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