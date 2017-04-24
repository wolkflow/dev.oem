<?php

namespace Wolk\OEM;

class OrderSketch extends \Wolk\Core\System\HLBlockModel
{
    const IBLOCK_ID = HLBLOCK_ORDER_SKETCHES_ID;
    
    // Список полей.
    const FIELD_ID       = 'ID';
    const FIELD_ORDER_ID = 'UF_ORDER_ID';
    const FIELD_SKETCH   = 'UF_SKETCH';
    const FIELD_IMAGE    = 'UF_IMAGE';
    
    
    
    public function getOrderID()
    {
        return $this->get(self::FIELD_ORDER_ID);
    }
    
    
    public function getSketch()
    {
        return $this->get(self::FIELD_ORDER_ID);
    }
    
    
    public function getImageID()
    {
        return $this->get(self::FIELD_IMAGE);
    }
    
    
    public function getImagePath()
    {
        return (\CFile::getPath($this->getImageID()));
    }
}