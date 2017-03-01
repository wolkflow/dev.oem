<?php

namespace Wolk\OEM\Wizard;

use Wolk\Oem\Users\User;
use Wolk\OEM\Event;
use Wolk\OEM\Stand;

class Wizard
{
    const SESSION = 'EVENTS';
    
    protected $user     = null;
    protected $event    = null;
    protected $stand    = null;
    protected $steps    = array();
    protected $products = array();
    
    
    /**
     * Визард создания зкаказа.
     */
    public function __construct(Event $event, User $user)
    {
        $this->user  = $user;
        $this->event = $event;
    }
    
    
    public function getUser()
    {
        return $this->user;
    }
    
    
    public function setUser(User $user)
    {
        $this->user = $user;
        
        return $this;
    }
    
    
    public function getEvent()
    {
        return $this->event;
    }
    
    
    public function setEvent(Event $event)
    {
        $this->user = $user;
        
        return $this;
    }
    
    
    public function getStand()
    {
        return $this->stand;
    }
    
    
    public function setStand(Stand $stand)
    {
        $this->stand = $stand;
        
        return $this;
    }
    
    
    /**
     * Сохранение данных в сессию.
     */
    public function putSession()
    {
        $_SESSION[self::SESSION][$this->getEvent()->getCode()] = [
            ''
        ];
    }
    
    
    /**
     * Получение данных из сессии.
     */
    public function getSession()
    {
        
    }
}