<?php

namespace Wolk\OEM;


/**
 * Класс,отвечающий за контекст работы с ценами: тип и язык.
 */
class Context
{
    protected $type = null;
    protected $lang = null;
    
    const TYPE_STANDARD   = 'STANDARD';
    const TYPE_INDIVIDUAL = 'INDIVIDUAL';
    
    
    public function __construct($type, $lang)
    {
        $this->type = mb_strtoupper((string) $type);
        $this->lang = mb_strtoupper((string) $lang);
    }
    
    
    public function getType()
    {
        return $this->type;
    }
    
    
    public function getLang()
    {
        return $this->lang;
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
}