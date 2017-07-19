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
    const SESSCODE_PARAMS   = 'PARAMS';
    const SESSCODE_SKETCH   = 'SKETCH';
    const SESSCODE_RENDERS  = 'RENDERS';
    
    const KIND_STAND   = 'stand';
    const KIND_PRODUCT = 'product';

    // Типы параметров.
    const PARAM_FILE                   = 'FILE';
    const PARAM_COMMENT                = 'COMMENT';
    const PARAM_LINK                   = 'LINK';
    const PARAM_COLOR                  = 'COLOR';
    const PARAM_FORM_HANGING_STRUCTURE = 'FORM_HANGING_STRUCTURE';
    
    protected $code = null;
    protected $data = array();
    
    
    public function __construct($event)
    {
        $this->code = mb_strtoupper((string) $event);
        $this->data = $this->getSession();
    }
    
    
    public function clear()
    {
        $this->data = [];
        
        // Сохранение в сесиию.
        $this->putSession();
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
     * Получение списка элементов корзины.
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
    
    
    public function setParams($data)
    {
        $this->data[self::SESSCODE_PARAMS] = (array) $data;
        
        // Сохранение в сесиию.
        $this->putSession();
    }


    public function getParams()
    {
        return ((array) $this->getData()[self::SESSCODE_PARAMS]);
    }


    public function setSketch($data)
    {
        $this->data[self::SESSCODE_SKETCH] = (array) $data;
        
        // Сохранение в сесиию.
        $this->putSession();
    }


    public function getSketch()
    {
        return ((array) $this->getData()[self::SESSCODE_SKETCH]);
    }
    
    
    public function setRenders($data)
    {
        $this->data[self::SESSCODE_RENDERS] = (array) $data;
        
        // Сохранение в сесиию.
        $this->putSession();
    }


    public function getRenders()
    {
        return ((array) $this->getData()[self::SESSCODE_RENDERS]);
    }
    
    
    /**
     * Получение списка элементов корзины.
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
     * Получение списка ID продукции в корзине.
     */
    public function getPIDs()
    {
        $items = $this->getList();
        $pids  = [];
        foreach ($items as $item) {
            $pids = (int) $item->getProductID();
        }
        $pids = array_unique($pids);
        
        return $pids;
    }
    
    
    /**
     * Получение продукции, сгруппированной по разделам.
     */
    public function getSectionGroups()
    {
        $items = $this->getList();
        
        $sections = [];
        foreach ($items as $item) {
            $sections[intval($item->getSectionID())][$item->getID()] = $item;
        }
        return $sections;
    }
    
    
    /**
     * Добавление продукции в корзину.
     */
    public function put($pid, $quantity, $kind, $params = [])
    {
        $quantity = (float) $quantity;
        $kind     = (string) $kind;
        $params   = (array) $params;
        
        $sid = 0;
        if ($kind == self::KIND_PRODUCT) {
            $sid = (new Product($pid))->getSectionID();
        }


        // Данные для добавления в корзину.
        $item = array(
            'id'       => uniqid(time()),
            'pid'      => $pid,
            'sid'      => $sid,
            'quantity' => $quantity,
            'kind'     => $kind,
            'params'   => $params,
        );
        
        switch ($kind) {
            case (self::KIND_STAND):
                $this->data[self::SESSCODE_STAND] = $item;
                break;
            case (self::KIND_PRODUCT);
                $this->data[self::SESSCODE_PRODUCTS][$item['id']] = $item;
                break;
            default:
                return;
                break;
        }
        
        // Сохранение в сесиию.
        $this->putSession();
        
        return $item;
    }
    
    
    /**
     * Обновление количества товара.
     */
    public function update($bid, $pid, $quantity, $params)
    {
        $bid      = (string) $bid;
        $quantity = (float) $quantity;
        $params   = (array) $params;
        
        if ($quantity <= 0) {
            return;
        }
        $this->data[self::SESSCODE_PRODUCTS][$bid]['quantity'] = $quantity;
        $this->data[self::SESSCODE_PRODUCTS][$bid]['params']   = $params;

        // Сохранение в сесиию.
        $this->putSession();
    }
    
    
    /**
     * Удаление товара из корзины.
     */
    public function remove($bid)
    {
        unset($this->data[self::SESSCODE_PRODUCTS][strval($bid)]);
        
        // Сохранение в сесиию.
        $this->putSession();
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