<?php

namespace Wolk\OEM\API;

\Bitrix\Main\Loader::includeModule('wolk.core');

/**
 * Команда.
 */
class User extends \Wolk\Core\System\HLBlockModel
{
    static protected $hlblockID = HLBLOCK_USERS_API_ID;
    
    const FIELD_LOGIN  = 'UF_LOGIN';
    const FIELD_KEY    = 'UF_KEY';
    const FIELD_NOTE   = 'UF_NOTE';
    const FIELD_EVENTS = 'UF_EVENTS';
    
    
    public function getKey()
    {
        return $this->get(self::FIELD_KEY);
    }
    
    
    public static function getByLogin($login)
    {
        $items = self::getList(array(
            'filter' => array(self::FIELD_LOGIN => (string) $login), 
            'order'  => array('ID' => 'ASC'),
            'limit'  => 1
        ));
        
        if (count($items) > 0) {
            return reset($items);
        }
        return null;
    }
    
}