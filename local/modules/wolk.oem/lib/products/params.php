<?php

namespace Wolk\OEM\Prices;

class Params extends \Wolk\Core\System\HLBlockModel
{
    const HBLOCK_ID = HLBLOCK_PARAMS_PRODUCTS_ID;
    
    // Список полей.
    const FIELD_ID      = 'ID';
    const FIELD_EVENT   = 'UF_EVENT';
    const FIELD_PRODUCT = 'UF_PRODUCT';
    const FIELD_PROPS   = 'UF_PROPS';
    const FIELD_NOTE    = 'UF_NOTE';
    
    
    
    public function getEventID()
    {
        return $this->get(self::FIELD_EVENT);
    }
    
    
    public function getProductID()
    {
        return $this->get(self::FIELD_PRODUCT);
    }
    
    
    public function getProps()
    {
        return json_decode($this->get(self::FIELD_PROPS), true);
    }
    
	
	public function getNote()
    {
        return $this->get(self::FIELD_NOTE);
    }
}


