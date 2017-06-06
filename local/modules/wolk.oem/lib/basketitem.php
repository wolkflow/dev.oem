<?php

namespace Wolk\OEM;

use Wolk\OEM\Basket as Basket;
use Wolk\OEM\Context as Context;
use Wolk\OEM\Stand as Stand;
use Wolk\OEM\Products\Base as Product;

class BasketItem
{
    protected $id     = null;
    protected $data   = [];
    protected $basket = [];
    
    
    
    public function __construct($event, $id, $data = [])
    {    
        $this->id   = (string) $id;
        $this->data = (array) $data;
        
        $this->basket = new Basket($event);
        
        if (empty($this->data)) {
            $this->load();
        }
    }
    
    
    // Загрузка данных из сессии.
    public function load()
    {
        $this->data = ($this->getBasket()->getData()[$this->getID()]);
    }
    
    
    public function getID()
    {
        return $this->data['id'];
    }
    
    
    public function getProductID()
    {
        return $this->data['pid'];
    }
    
    
    /**
     * Получение элемента.
     */
    public function getElement()
    {
        $element = null;
        
        switch ($this->getKind()) {
            case (Basket::KIND_STAND):
                $element = new Stand($this->getProductID());
                break;
            case (Basket::KIND_PRODUCT):
                $element = new Product($this->getProductID());
                break;
        }
        return $element;
    }
    
    
    public function getParams()
    {
        return $this->data['params'];
    }
    
    
    public function getKind()
    {
        return $this->data['kind'];
    }
    
    
    public function getBasket()
    {
        return $this->basket;
    }
    
    
    public function getPrice()
    {
        
    }
    
    
    public function getCost()
    {
        
    }
    
}