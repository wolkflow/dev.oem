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
    const SESSCODE_ORDERID  = 'ORDERID';
    
    const KIND_STAND   = 'stand';
    const KIND_PRODUCT = 'product';
    
    const NOTE_PRODUCT_BASE     = 'PRODUCT.BASE';
    const NOTE_PRODUCT_SALE     = 'PRODUCT.SALE';
    const NOTE_STAND_STANDARD   = 'STAND.STANDARD';
    const NOTE_STAND_INDIVIDUAL = 'STAND.INDIVIDUAL';
    
    
    // Типы параметров.
    const PARAM_FILE                   = 'FILE';
    const PARAM_COMMENT                = 'COMMENT';
    const PARAM_LINK                   = 'LINK';
    const PARAM_COLOR                  = 'COLOR';
    const PARAM_FORM_HANGING_STRUCTURE = 'FORM.HANGING-STRUCTURE';
    
    
    
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
    
    
    public function setParam($key, $value)
    {
        $this->data[self::SESSCODE_PARAMS][strval($key)] = $value;
        
        // Сохранение в сесиию.
        $this->putSession();
    }
    
    
    public function getParam($key)
    {
        return ($this->getData()[self::SESSCODE_PARAMS][strval($key)]);
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
    
    
    public function setOrderID($oid)
    {
        $this->data[self::SESSCODE_ORDERID] = (int) $oid;
        
        // Сохранение в сесиию.
        $this->putSession();
    }


    public function getOrderID()
    {
        return ((int) $this->getData()[self::SESSCODE_ORDERID]);
    }
    
    
    /**
     * Получение списка элементов корзины.
     */
    public function getList($included = false)
    {
        $items = [];
        $data  = $this->getData()[self::SESSCODE_PRODUCTS];
        foreach ($data as $item) {
            if (!$included && $item['included']) {
                continue;
            }
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
    public function put($pid, $quantity, $kind, $params = [], $fields = [], $included = false)
    {
        $quantity = (float) $quantity;
        $kind     = (string) $kind;
        $params   = (array) $params;
        $fields   = (array) $fields;
        $included = (bool) $included;
        
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
            'fields'   => $fields,
            'included' => $included,
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
    public function update($bid, $pid, $quantity, $params, $fields)
    {
        $bid      = (string) $bid;
        $pid      = (int)   $pid;
        $quantity = (float) $quantity;
        $params   = (array) $params;
        $fields   = (array) $fields;
        
        if ($quantity <= 0) {
            return;
        }
        $this->data[self::SESSCODE_PRODUCTS][$bid]['pid']      = $pid;
        $this->data[self::SESSCODE_PRODUCTS][$bid]['quantity'] = $quantity;
        $this->data[self::SESSCODE_PRODUCTS][$bid]['params']   = $params;
        $this->data[self::SESSCODE_PRODUCTS][$bid]['fields']   = $fields;
        
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
    
    
    /**
     * Создать заказ.
     */
    public function order(Context $context)
    {
        // Текущая корзина.
        $items = $this->getList(true);
        
        // Мероприятие.
        $event = new Event($context->getEventID());
                
        // Валюта заказа.
        $currency = $event->getCurrencyStandsContext($context);
        
        // Итоговая сумма.
        $price = 0;
        
        // Очистка корзины.
        \CSaleBasket::DeleteAll(\CSaleBasket::GetBasketUserID());
        
        // Сохранение стенда.
        $item = $this->getStand();
        
        if (!empty($item)) {
            // Получение цены.
            $item->loadPrice($context);
            
            // Элемент.
            $stand = $item->getElement();
            
            if (!empty($stand)) {
                $data = [
                    'PRODUCT_ID'     => $item->getProductID(),
                    'QUANTITY'       => $item->getQuantity(),
                    'PRICE'          => $item->getPrice(),
                    'CURRENCY'       => $currency,
                    'LID'            => SITE_ID,
                    'NAME'           => $stand->getTitle(),
                    'SET_PARENT_ID'  => 0,
                    'TYPE'           => 0,
                    'FUSER_ID'       => \CSaleBasket::GetBasketUserID(),
                    'RECOMMENDATION' => $note,
                ];
                
                $price += $item->getCost();
            }
        }
        
        // Сохранение продукции.
        foreach ($items as $item) {
            // Элемент.
            $elem = $item->getElement();
            
            if (empty($elem)) {
                continue;
            }
            
            // Получение цены.
            $item->loadPrice($context);
            
            // Тип продукции.
            $kind = $item->getKind();
            
            if ($kind == self::KIND_STAND) {
                $note = 'STAND.' . strtoupper($context->getType());
            }
            if ($kind == self::KIND_PRODUCT) {
                $note = 'PRODUCT.' . (($item->isIncluded()) ? ('BASE') : ('SALE'));
            }
            
            $data = [
                'PRODUCT_ID'     => $item->getProductID(),
                'QUANTITY'       => $item->getQuantity(),
                'PRICE'          => $item->getPrice(),
                'CURRENCY'       => $currency,
                'LID'            => SITE_ID,
                'NAME'           => $elem->getTitle(),
                'SET_PARENT_ID'  => 0,
                'TYPE'           => 0,
                'FUSER_ID'       => \CSaleBasket::GetBasketUserID(),
                'RECOMMENDATION' => $note,
                'PROPS'          => []
            ];
            
            if ($item->isIncluded()) {
                $props = [[
                    'NAME'      => 'Стандартная комплектация',
                    'CODE'      => 'INCLUDING',
                    'VALUE'     => 'Да'
                ]];
                
                $data['PROPS'] = $props;
                
                // Добавление корзины.
                CSaleBasket::add($data);
            }
            
            // Суммирование цены.
            $price += $item->getCost();   
        }
        
        // Цены.
        $infoprices = Order::getFullPriceInfo($price, $event->getSurcharge(), $event->hasVAT());
        
        $data = [
            'LID'              => SITE_ID,
            'USER_ID'          => \Cuser::getID(),
            'PERSON_TYPE_ID'   => PERSON_TYPE_DETAULT,
            'DELIVERY_ID'      => DELIVERY_DETAULT,
            'PAYED'            => 'N',
            'CANCELED'         => 'N',
            'STATUS_ID'        => 'N',
            'DISCOUNT_VALUE'   => '',
            'PRICE'            => $infoprices['SUMMARY'],
            'TAX_VALUE'        => (!$event->hasVAT()) ? ($infoprices['VAT_PRICE']) : (0),
            'CURRENCY'         => $currency,
            'USER_DESCRIPTION' => $this->getParam('COMMENTS'),
        ];
        
        // Сохранение заказа.
        $oid = \CSaleOrder::add($data);
        
        if (!$oid) {
            throw new \Exception("Cant' create order.");
        }
        
        // Сохранение свофств заказа.
        $result = \CSaleOrderProps::GetList();
        $props = [];
        while ($prop = $result->Fetch()) {
            $props[$prop['CODE']] = $prop;
        }
        $dataprops = [];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['eventId']['ID'],
            'NAME'           => 'ID мероприятия',
            'CODE'           => 'eventId',
            'VALUE'          => $event->getID(),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['eventName']['ID'],
            'NAME'           => 'Название мероприятия',
            'CODE'           => 'eventName',
            'VALUE'          => $event->getTitle(),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['width']['ID'],
            'NAME'           => 'Ширина стенда',
            'CODE'           => 'width',
            'VALUE'          => $this->getParam('WIDTH'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['depth']['ID'],
            'NAME'           => 'Глубина стенда',
            'CODE'           => 'depth',
            'VALUE'          => $this->getParam('DEPTH'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SURCHARGE']['ID'],
            'NAME'           => 'Процент наценки',
            'CODE'           => 'SURCHARGE',
            'VALUE'          => $infoprices['SURCHARGE_PERCENT'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SURCHARGE_PRICE']['ID'],
            'NAME'           => 'Сумма наценки',
            'CODE'           => 'SURCHARGE_PRICE',
            'VALUE'          => $infoprices['SURCHARGE_PRICE'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['LANGUAGE']['ID'],
            'NAME'           => 'Язык заказа',
            'CODE'           => 'LANGUAGE',
            'VALUE'          => $context->getLang(),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['sketch']['ID'],
            'NAME'           => 'Скетч',
            'CODE'           => 'sketch',
            'VALUE'          => $this->getSketch()['SKETCH_SCENE'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SKETCH_IMAGE']['ID'],
            'NAME'           => 'Изображение скетча',
            'CODE'           => 'SKETCH_IMAGE',
            'VALUE'          => $this->getSketch()['SKETCH_IMAGE'],
        ];
        
        foreach ($dataprops as $dataprop) {
            \CSaleOrderPropsValue::add($dataprop);
        }
    }
}