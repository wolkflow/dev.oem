<?php

namespace Wolk\OEM;

use Wolk\OEM\Products\Base as Product;

class Basket
{
    const SESSCODE_EVENT  = 'OEMEVENT';
    const SESSCODE_BASKET = 'BASKET';
    
    protected $code = null;
    protected $data = array();
    
    
    public function __construct($event)
    {
        $this->code = mb_strtoupper((string) $event);
        $this->data = $this->getSession();
    }
    
    
    /**
     * Получение кода мероприятия.
     */
    public function getEventCode()
    {
        return $this->code;
    }
    
    
    /**
     * Получение данных корзины.
     */
    public function getData()
    {
        return $this->data;
    }
    
    
    /**
     * Добавление продукции в корзину.
     */
    public function put($pid, $quantity, $kind = 'product', $params = [], Context $context)
    {
        $product = new Product($pid);
        
        $quantity = (float) $quantity;
        $price    = (float) $product->getContextprice($context);
        $cost     = $quantity * $price;
        $kind     = (string) $kind;
        $params   = (array) $params;
        
        $item = array(
            'bid'      => uniqid(time()),
            'pid'      => $product->getID(),
            'quantity' => $quantity,
            'price'    => $price,
            'cost'     => $cost,
            'kind'     => $kind,
            'params'   => $params,
        );
        
        $this->data[$item['bid']] = $item;
        
        // сохранение в сесиию.
        $this->putSession();
    }
    
    
    /**
     * Удаление товара из корзины.
     */
    public function remove($bid)
    {
        
    }
    
    
    /**
     * Получение общей цены.
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
     * Сохранение данных в сессиии.
     */
    public function putSession()
    {
        $_SESSION[self::SESSCODE_EVENT][$this->getEventCode()][self::SESSCODE_BASKET] = $this->getData();
    }
    
    
    /**
     * Получение данных из сессиии.
     */
    public function getSession()
    {
        return $_SESSION[self::SESSCODE_EVENT][$this->getEventCode()][self::SESSCODE_BASKET];
    }
}