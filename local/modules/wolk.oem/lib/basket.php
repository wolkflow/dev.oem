<?php

namespace Wolk\OEM;

use Wolk\OEM\BasketItem as BasketItem;
use Wolk\OEM\Stand as Stand;
use Wolk\OEM\Products\Base as Product;

class Basket
{
    const SESSCODE_EVENT    = 'OEMEVENTS';
    const SESSCODE_BASKET   = 'BASKET';
    const SESSCODE_STAND    = 'STAND';
    const SESSCODE_PRODUCTS = 'PRODUCTS';
    
    const KIND_STAND   = 'stand';
    const KIND_PRODUCT = 'product';
    
    protected $code = null;
    protected $data = array();
    
    
    public function __construct($event)
    {
        $this->code = mb_strtoupper((string) $event);
        $this->data = $this->getSession();
    }
    
    
    /**
     * ��������� ���� �����������.
     */
    public function getEventCode()
    {
        return $this->code;
    }
    
    
    /**
     * ��������� ������ �������.
     */
    public function getData()
    {
        return $this->data;
    }
    
    
    /**
     * ��������� ������ ��������� �������.
     */
    public function getStand()
    {
        $data = $this->getData()[self::SESSCODE_STAND];
        $item = null;
        if (!empty($data)) {
            $item = new BasketItem($this->getEventCode(), $data['id'], $data);
        }
        return $item;
    }
    
    
    /**
     * ��������� ������ ��������� �������.
     */
    public function getList()
    {
        $items = [];
        $data  = $this->getData()[self::SESSCODE_PRODUCTS];
        foreach ($data as $item) {
            $items[$item['id']] = new BasketItem($this->getEventCode(), $item['id'], $item);
        }
        return $items;
    }
    
    
    /**
     * ���������� ��������� � �������.
     */
    public function put($pid, $quantity, $kind, $params = [])
    {
        $quantity = (float) $quantity;
        $kind     = (string) $kind;
        $params   = (array) $params;
        
        $item = array(
            'id'       => uniqid(time()),
            'pid'      => $pid,
            'quantity' => $quantity,
            'kind'     => $kind,
            'params'   => $params,
        );
        
        switch ($kind) {
            case ('stand'):
                $this->data[self::SESSCODE_STAND] = $item;
                break;
            case ('product');
                $this->data[self::SESSCODE_PRODUCTS][$item['id']] = $item;
                break;
            default:
                return;
                break;
        }
        
        
        // ���������� � ������.
        $this->putSession();
    }
    
    
    /**
     * �������� ������ �� �������.
     */
    public function remove($bid)
    {
        
    }
    
    
    /**
     * ���������� ������ � �������.
     */
    public function putSession()
    {
        $_SESSION[self::SESSCODE_EVENT][$this->getEventCode()][self::SESSCODE_BASKET] = $this->getData();
    }
    
    
    /**
     * ��������� ������ �� �������.
     */
    public function getSession()
    {
        return $_SESSION[self::SESSCODE_EVENT][$this->getEventCode()][self::SESSCODE_BASKET];
    }
}