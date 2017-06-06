<?php

namespace Wolk\OEM;

use Wolk\OEM\Stand as Stand;
use Wolk\OEM\Products\Base as Product;

class Basket
{
    const SESSCODE_EVENT  = 'OEMEVENTS';
    const SESSCODE_BASKET = 'BASKET';
    
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
     * ���������� ��������� � �������.
     */
    public function put($pid, $quantity, $kind, $params = [], Context $context)
    {
        switch ($kind) {
            case ('stand'):
                $element = new Stand($pid);
                break;
            case ('product');
                $element = new Product($pid);
                break;
            default:
                return;
                break;
        }
        
        $quantity = (float) $quantity;
        //$price    = (float) $element->getContextPrice($context);
        //$cost     = $quantity * $price;
        $kind     = (string) $kind;
        $params   = (array) $params;
        
        $item = array(
            'id'       => uniqid(time()),
            'pid'      => $element->getID(),
            'quantity' => $quantity,
            //'price'    => $price,
            //'cost'     => $cost,
            'kind'     => $kind,
            'params'   => $params,
        );
        
        $this->data[$item['id']] = $item;
        
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
     * ��������� ����� ����.
     */
    public function getPrice()
    {
        $items = $this->getData();
        
        $price = 0;
        foreach ($items as $item) {
            $price += (float) $item['cost'];
        }
        return $price;
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