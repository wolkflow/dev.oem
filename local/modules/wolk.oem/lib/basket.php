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
    const PARAM_FORM_HANGING_STRUCTURE = 'FORM-HANGING-STRUCTURE';
    
    
    
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
     * Проставление данных корзины.
     */
    public function setData($data)
    {
        $this->data = (array) $data;
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
     *
     * $context - контекст.
     * $data    - данные для заказа.
     */
    public function order(Context $context, $data = [])
    {   
        // Замены данных корзины текущими данными для заказа.
        $dump = $this->getData();
        if (!empty($data)) {
            $this->data = $data;
        }
        
        
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
        
        
        // Корзина пользователя.
        $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
           \Bitrix\Sale\Fuser::getId(), 
           \Bitrix\Main\Context::getCurrent()->getSite()
        );
        
        
        if (!empty($item)) {
            // Получение цены.
            $item->loadPrice($context);
            
            // Элемент.
            $stand = $item->getElement();
            
            if (!empty($stand)) {
                // Тип продукции.
                $note = 'STAND.' . strtoupper($context->getType());
                
                $fields = [
                    'PRODUCT_ID'     => $item->getProductID(),
                    'QUANTITY'       => $item->getQuantity(),
                    'PRICE'          => $item->getPrice(),
                    'CUSTOM_PRICE'   => 'Y',
                    'CURRENCY'       => $currency,
                    'LID'            => SITE_ID,
                    'NAME'           => $stand->getTitle(),
                    'SET_PARENT_ID'  => 0,
                    'TYPE'           => 0,
                    'FUSER_ID'       => \CSaleBasket::GetBasketUserID(),
                    'RECOMMENDATION' => $note,
                    'IGNORE_CALLBACK_FUNC' => 'Y',
                ];
                
                $props = [[
                    'NAME'  => 'Стенд',
                    'CODE'  => 'STAND',
                    'VALUE' => 'Y'
                ]];
                
                // Добавление корзины.
                // \CSaleBasket::add($fields);
                // \Bitrix\Sale\Internals\BasketTable::add($fields);
                
                $basket_item = $basket->createItem('catalog', $item->getProductID());
                $basket_item->setFields([
                    'QUANTITY'             => $item->getQuantity(),
                    'PRICE'                => $item->getPrice(),
                    'CUSTOM_PRICE'         => 'Y',
                    'CURRENCY'             => $currency,
                    'NAME'                 => $stand->getTitle(),
                    'IGNORE_CALLBACK_FUNC' => 'Y',
                ]);
                
                $basket_props = $basket_item->getPropertyCollection();
                $basket_props->setProperty($props);
                $basket_props->save();
                
                $basket->save();
                
                // Общая стоимость продукции.
                $price += $item->getCost();
            }
        }
        
        
        // Сохранение продукции.
        foreach ($items as $item) {
            
            // Продукиця.
            $prod = $item->getElement();
            
            if (empty($prod)) {
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
            
            /*
            $fields = [
                'PRODUCT_ID'     => $item->getProductID(),
                'QUANTITY'       => $item->getQuantity(),
                'PRICE'          => $item->getPrice(),
                'CUSTOM_PRICE'   => 'Y',
                'CURRENCY'       => $currency,
                'LID'            => SITE_ID,
                'NAME'           => $prod->getTitle(),
                'SET_PARENT_ID'  => 0,
                'TYPE'           => 0,
                'FUSER_ID'       => \CSaleBasket::GetBasketUserID(),
                'RECOMMENDATION' => $note,
                'PROPS'          => [],
                'IGNORE_CALLBACK_FUNC' => 'Y',
            ];
            */
            $props = [];
            
            foreach ($item->getFields() as $fkey => $fval) {
                $props []= [
                    'NAME'  => $fkey,
                    'CODE'  => strtoupper($fkey),
                    'VALUE' => $fval
                ];
            }
            
            // Свойства и параметры продукции.
            $props []= [
                'NAME'  => 'Свойства и параметры',
                'CODE'  => 'PARAMS',
                'VALUE' => json_encode((array) $item->getParams())
            ];
            
            // Продукция включена в стенд.
            if ($item->isIncluded()) {
                $props []= [
                    'NAME'  => 'Стандартная комплектация',
                    'CODE'  => 'INCLUDING',
                    'VALUE' => 'Y'
                ];
            }
            
            // Добавление корзины.
            //\CSaleBasket::add($fields);
            // \Bitrix\Sale\Internals\BasketTable::add($fields);
            
            $basket_item = $basket->createItem('catalog', $item->getProductID());
            $basket_item->setFields([
                'QUANTITY'             => $item->getQuantity(),
                'PRICE'                => $item->getPrice(),
                'CUSTOM_PRICE'         => 'Y',
                'CURRENCY'             => $currency,
                'NAME'                 => $prod->getTitle(),
                'IGNORE_CALLBACK_FUNC' => 'Y',
            ]);
            
            $basket_props = $basket_item->getPropertyCollection();
            $basket_props->setProperty($props);
            $basket_props->save();
            
            $basket->save();
            
            // Суммирование цены.
            $price += $item->getCost();   
        }
        
        
        // Цены.
        $infoprices = Order::getFullPriceInfo($price, $event->getSurcharge(), $event->hasVAT());
        
        $fields = [
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
        
        // Создание заказа.
        $order = \Bitrix\Sale\Order::create(\Bitrix\Main\Context::getCurrent()->getSite(), \CUser::getID());
        
        
        
        $order->setPersonTypeId(PERSON_TYPE_DETAULT);
        // $order->setField('CURRENCY', $currency);
        $order->setField('PRICE', $infoprices['SUMMARY']);
        $order->setField('TAX_VALUE', (!$event->hasVAT()) ? ($infoprices['VAT_PRICE']) : (0));
        $order->setField('USER_DESCRIPTION', $this->getParam('COMMENTS'));
        
        // Сохранение позиций заказа.
        $order->setBasket($basket);
        
        $order_props = $order->getPropertyCollection();
        
        //$oid = \CSaleOrder::add($fields);
        
        
        
        // Сохранение свойств заказа.
        $result = \CSaleOrderProps::GetList();
        $props = [];
        while ($prop = $result->Fetch()) {
            $props[$prop['CODE']] = $prop;
        }
        $dataprops = [];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['EVENT_ID']['ID'],
            'NAME'           => 'ID мероприятия',
            'CODE'           => 'EVENT_ID',
            'VALUE'          => $event->getID(),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['EVENT_NAME']['ID'],
            'NAME'           => 'Название мероприятия',
            'CODE'           => 'EVENT_NAME',
            'VALUE'          => $event->getTitle(),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['STAND_TYPE']['ID'],
            'NAME'           => 'Тип стенда',
            'CODE'           => 'STAND_TYPE',
            'VALUE'          => $this->getParam('STAND_TYPE'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['WIDTH']['ID'],
            'NAME'           => 'Ширина стенда',
            'CODE'           => 'WIDTH',
            'VALUE'          => $this->getParam('WIDTH'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['DEPTH']['ID'],
            'NAME'           => 'Глубина стенда',
            'CODE'           => 'DEPTH',
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
            'ORDER_PROPS_ID' => $props['SKETCH']['ID'],
            'NAME'           => 'Скетч',
            'CODE'           => 'SKETCH',
            'VALUE'          => $this->getSketch()['SKETCH_SCENE'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SKETCH_IMAGE']['ID'],
            'NAME'           => 'Изображение скетча',
            'CODE'           => 'SKETCH_IMAGE',
            'VALUE'          => $this->getSketch()['SKETCH_IMAGE'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['STANDNUM']['ID'],
            'NAME'           => 'Номер стенда',
            'CODE'           => 'STANDNUM',
            'VALUE'          => $this->getParam('STANDNUM'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['PAVILION']['ID'],
            'NAME'           => 'Павильон',
            'CODE'           => 'PAVILION',
            'VALUE'          => $this->getParam('PAVILION'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['TYPE']['ID'],
            'NAME'           => 'Тип заказа',
            'CODE'           => 'TYPE',
            'VALUE'          => 'COMMON',
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['TYPESTAND']['ID'],
            'NAME'           => 'Тип застройки',
            'CODE'           => 'TYPESTAND',
            'VALUE'          => $context->getType(),
        ];
        
        foreach ($dataprops as $dataprop) {
            $order_props->createItem($dataprop);
            //\CSaleOrderPropsValue::add($dataprop);
        }
        
        
        // Сохранение заказа.
        $result = $order->save();
        
        if (!$result || !$result->getID()) {
            throw new \Exception("Cant' create order.");
        }
        
        
        //\CSaleBasket::OrderBasket($oid, \CSaleBasket::GetBasketUserID());
        
        /*
        $baskets = \Bitrix\Sale\Internals\BasketTable::getList([
            'filter' =>
                [
                    'FUSER_ID' => \CSaleBasket::GetBasketUserID(),
                    'ORDER_ID' => null,
                ]
        ])->fetchAll();
        
        foreach ($baskets as $basket) {
            $result = \Bitrix\Sale\Internals\BasketTable::update($basket['ID'], ['ORDER_ID' => $oid]);
        }
        */
        
        // Очистка данных.
        if (empty($data)) {
            $this->clear();
        } else {
            $this->data = $dump;
        }
    }
}