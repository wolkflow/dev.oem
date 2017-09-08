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
        
        $basket = new Basket($event);
        
        // Загрузка продукта мз корзины.
        // Не распространяется на стенд.
        if (empty($this->data)) {
            //$this->load();
            $this->data = $basket->getData()[Basket::SESSCODE_PRODUCTS][$this->getID()];
        }
    }
    
    
    // Загрузка данных из сессии.
    public function load($data)
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
    
    
    public function isIncluded()
    {
        return $this->data['included'];
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
     * Проверка наличия дополнительного параметра по коду.
     */
    public function hasParam($code)
    {
        return (!empty($this->data['params'][strval($code)]));
    }

    
    /**
     * Получение дополнительного параметра по коду.
     */
    public function getParam($code)
    {
        return $this->data['params'][strval($code)];
    }
    
    
    /**
     * Получение дополнительных полей.
     */
    public function getFields()
    {
        return $this->data['fields'];
    }


    /**
     * Проверка наличия дополнительного поля по коду.
     */
    public function hasField($code)
    {
        return (!empty($this->data['fields'][strval($code)]));
    }

    
    /**
     * Получение дополнительного поля по коду.
     */
    public function getField($code)
    {
        return $this->data['fields'][strval($code)];
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
     * Установка цены.
     */
    public function setPrice($price)
    {
        $this->price = (float) $price;
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
        return ((float) $this->getPrice() * $this->getQuantity());
    }
    
}