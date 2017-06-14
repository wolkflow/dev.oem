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
        
        // �������� �������� �� �������.
        // �� ���������������� �� �����.
        if (empty($this->data)) {
            $this->load();
        }
    }
    
    
    // �������� ������ �� ������.
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
     * ��������� ��������.
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
     * ��������� ����������.
     */
    public function getQuantity()
    {
        return $this->data['quantity'];
    }
    
    
    /**
     * ��������� �������������� ����������.
     */
    public function getParams()
    {
        return $this->data['params'];
    }
    
    
    /**
     * ��������� ��������������� ��������� �� ����.
     */
    public function getParam($code)
    {
        return $this->data['params'][strval($code)];
    }
    
    
    /**
     * �������� ���� �������� �������.
     */
    public function getKind()
    {
        return $this->data['kind'];
    }
    
    
    /**
     * ��������� ������� �������.
     */
    public function getBasket()
    {
        return $this->basket;
    }
    
    
    /**
     * ��������� ���� �� ���������.
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
     * ��������� ����.
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    
    /**
     * ��������� ���������.
     */
    public function getCost()
    {
        return ($this->getPrice() * $this->getQuantity());
    }
    
}