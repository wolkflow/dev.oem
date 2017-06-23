<?php

namespace Wolk\OEM;

use Wolk\OEM\Basket as Basket;
use Wolk\OEM\Context as Context;
use Wolk\OEM\Stand as Stand;
use Wolk\OEM\Products\Base as Product;

class BasketItem
{
    protected $id      = null;
    protected $price   = null;
    protected $data    = [];
    
    protected $basket  = null;
    protected $element = null;
    
    
    
    public function __construct($event, $id, $data = [])
    {    
        $this->id   = (string) $id;
        $this->data = (array) $data;
        
        $this->basket = new Basket($event);
        
        // Загрузка продукта мз корзины.
        // Не распространяется на стенд.
        if (empty($this->data)) {
            $this->load();
        }
    }
    
    
    // Загрузка данных из сессии.
    public function load()
    {
        $this->data = ($this->getBasket()->getData()[Basket::SESSCODE_PRODUCTS][$this->getID()]);
    }
    
    
    public function getID()
    {
        return $this->id;
    }
    
    
    public function getProductID()
    {
        return $this->data['pid'];
    }
    
    
    public function getSectionID()
    {
        return $this->data['sid'];
    }
    
    
    /**
     * Получение элемента.
     */
    public function getElement()
    {
        if (empty($this->element)) {
            switch ($this->getKind()) {
                case (Basket::KIND_STAND):
                    $this->element = new Stand($this->getProductID());
                    break;
                case (Basket::KIND_PRODUCT):
                    $this->element = new Product($this->getProductID());
                    break;
            }
        }
        return $this->element;
    }
    
    
    /**
     * Получение количества.
     */
    public function getQuantity()
    {
        return $this->data['quantity'];
    }
    
    
    /**
     * Получение дополнительных параметров.
     */
    public function getParams()
    {
        return $this->data['params'];
    }
    
    
    /**
     * Получение дополнительного параметра по коду.
     */
    public function getParam($code)
    {
        return $this->data['params'][strval($code)];
    }
    
    
    /**
     * Полчение типа элемента корзины.
     */
    public function getKind()
    {
        return $this->data['kind'];
    }
    
    
    /**
     * Получение объекта корзины.
     */
    public function getBasket()
    {
        return $this->basket;
    }
    
    
    /**
     * Получение цены из контекста.
     */
    public function loadPrice(Context $context)
    {
        if (is_null($this->price)) {
            $element = $this->getElement();
            if (!empty($element)) {
                $this->price = $element->getContextPrice($context);
            }
        }
    }
    
    
    /**
     * Получение цены.
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    
    /**
     * Получение стоимости.
     */
    public function getCost()
    {
        return ($this->getPrice() * $this->getQuantity());
    }
    
}