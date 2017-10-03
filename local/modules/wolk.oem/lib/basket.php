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
		
		$order = null;
		
		// Редактирование текущего заказа.
		$oid = $this->getOrderID();
		
		if ($oid > 0) {
			$order = new Order($oid);
			
			$obasket = \Bitrix\Sale\Order::load($order->getID())->getBasket();
            $bresult = \Bitrix\Sale\Internals\BasketTable::getList(['filter' => ['ORDER_ID' => $order->getID()]]);
            
            while ($basket = $bresult->fetch()) {
                $obasket->getItemById($basket['ID'])->delete();
            }
            $obasket->save();
			
			unset($basket, $obasket, $bresult);
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
                    'LID'            => SITE_DEFAULT,
                    'NAME'           => $stand->getTitle(),
                    'SET_PARENT_ID'  => 0,
                    'TYPE'           => 0,
                    'FUSER_ID'       => \CSaleBasket::GetBasketUserID(),
                    'RECOMMENDATION' => $note,
                ];
                
                $props = [[
                    'NAME'  => 'Стенд',
                    'CODE'  => 'STAND',
                    'VALUE' => 'Y'
                ]];
                
                // Добавление корзины.
                $result = \Bitrix\Sale\Internals\BasketTable::add($fields);
                
                if (is_object($result)) {
                    // Корзина пользователя.
                    $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
                       \Bitrix\Sale\Fuser::getId(), 
                       \Bitrix\Main\Context::getCurrent()->getSite()
                    );
                    
                    $basket_item = $basket->getItemByID($result->getID());
                    $basket_prop = $basket_item->getPropertyCollection();
                    $basket_prop->setProperty($props);
                    $basket_prop->save();
                }
                
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
            
            $fields = [
                'PRODUCT_ID'     => $item->getProductID(),
                'QUANTITY'       => $item->getQuantity(),
                'PRICE'          => $item->getPrice(),
                'CUSTOM_PRICE'   => 'Y',
                'CURRENCY'       => $currency,
                'LID'            => SITE_DEFAULT,
                'NAME'           => $prod->getTitle(),
                'SET_PARENT_ID'  => 0,
                'TYPE'           => 0,
                'FUSER_ID'       => \CSaleBasket::GetBasketUserID(),
                'RECOMMENDATION' => $note,
            ];
            
            $props = [];
			
			// ID корзины.
            $props []= [
                'NAME'  => 'ID корзины',
                'CODE'  => 'BID',
                'VALUE' => $item->getID()
            ];
            
            // Поля заказа продукции.
            $props []= [
                'NAME'  => 'Поля заказа продукции',
                'CODE'  => 'FIELDS',
                'VALUE' => json_encode((array) $item->getFields())
            ];
            
            // Свойства продукции.
            $props []= [
                'NAME'  => 'Свойства продукции',
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
            $result = \Bitrix\Sale\Internals\BasketTable::add($fields);
            
			// Корзина пользователя.
            if (is_object($result)) {
                $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
                   \Bitrix\Sale\Fuser::getId(), 
                   \Bitrix\Main\Context::getCurrent()->getSite()
                );
                
                $basket_item = $basket->getItemByID($result->getID());
                $basket_prop = $basket_item->getPropertyCollection();
                $basket_prop->setProperty($props);
                $basket_prop->save();
            }
            
            // Суммирование цены.
			if (!$item->isIncluded()) {
				$price += $item->getCost(); 
			}
        }
        
        
        // Цены.
        $infoprices = Order::getFullPriceInfo($price, $event->getSurcharge(), $event->hasVAT());
        
        $fields = [
            'LID'              => SITE_DEFAULT,
            'USER_ID'          => \CUser::getID(),
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
        
        // Созданеи заказа.
        if (empty($oid)) {
            $oid = \CSaleOrder::add($fields);
        } else {
            $oid = \CSaleOrder::update($oid, $fields);
            
            if ($oid) {
                \CSaleOrderPropsValue::deleteByOrder($oid);
            }
        }
		
		if (!$oid) {
            throw new \Exception("Can't create order.");
        }
        
        
        // Сохранение свойств заказа.
        $result = \CSaleOrderProps::GetList();
        $props  = [];
        while ($prop = $result->Fetch()) {
            $props[$prop['CODE']] = $prop;
        }
        
		$dataprops = [];
		        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['EVENT_ID']['ID'],
            'NAME'           => 'ID мероприятия',
            'CODE'           => $props['EVENT_ID']['CODE'],
            'VALUE'          => $event->getID(),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['EVENT_NAME']['ID'],
            'NAME'           => 'Название мероприятия',
            'CODE'           => $props['EVENT_NAME']['CODE'],
            'VALUE'          => $event->getTitle(),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SFORM']['ID'],
            'NAME'           => 'Тип стенда',
            'CODE'           => $props['SFORM']['CODE'],
            'VALUE'          => $this->getParam('SFORM'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['WIDTH']['ID'],
            'NAME'           => 'Ширина стенда',
            'CODE'           => $props['WIDTH']['CODE'],
            'VALUE'          => $this->getParam('WIDTH'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['DEPTH']['ID'],
            'NAME'           => 'Глубина стенда',
            'CODE'           => $props['DEPTH']['CODE'],
            'VALUE'          => $this->getParam('DEPTH'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SURCHARGE']['ID'],
            'NAME'           => 'Процент наценки',
            'CODE'           => $props['SURCHARGE']['CODE'],
            'VALUE'          => $infoprices['SURCHARGE_PERCENT'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SURCHARGE_PRICE']['ID'],
            'NAME'           => 'Сумма наценки',
            'CODE'           => $props['SURCHARGE_PRICE']['CODE'],
            'VALUE'          => $infoprices['SURCHARGE_PRICE'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['LANGUAGE']['ID'],
            'NAME'           => 'Язык заказа',
            'CODE'           => $props['LANGUAGE']['CODE'],
            'VALUE'          => $context->getLang(),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SKETCH_SCENE']['ID'],
            'NAME'           => 'Скетч',
            'CODE'           => $props['SKETCH_SCENE']['CODE'],
            'VALUE'          => $this->getSketch()['SKETCH_SCENE'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SKETCH_IMAGE']['ID'],
            'NAME'           => 'Изображение скетча',
            'CODE'           => $props['SKETCH_IMAGE']['CODE'],
            'VALUE'          => $this->getSketch()['SKETCH_IMAGE'],
        ];
		
		$dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SKETCH_FILE']['ID'],
            'NAME'           => 'Файл скетча',
            'CODE'           => $props['SKETCH_FILE']['CODE'],
            'VALUE'          => \CFile::SaveFile(array(
				'name'    	  => 'sketch-'.$oid.'.jpg',
				'description' => 'Изображение скетча для заказа №'.$oid,
				'content'     => base64_decode($this->getSketch()['SKETCH_IMAGE'])
			), 'sketches')
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['STANDNUM']['ID'],
            'NAME'           => 'Номер стенда',
            'CODE'           => $props['STANDNUM']['CODE'],
            'VALUE'          => $this->getParam('STANDNUM'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['PAVILION']['ID'],
            'NAME'           => 'Павильон',
            'CODE'           =>  $props['PAVILION']['CODE'],
            'VALUE'          => $this->getParam('PAVILION'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['TYPE']['ID'],
            'NAME'           => 'Тип заказа',
            'CODE'           => $props['TYPE']['CODE'],
            'VALUE'          => 'COMMON',
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['TYPESTAND']['ID'],
            'NAME'           => 'Тип застройки',
            'CODE'           => $props['TYPESTAND']['CODE'],
            'VALUE'          => $context->getType(),
        ];
		
		
		$dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['RENDERS']['ID'],
            'NAME'           => 'Рендеры',
            'CODE'           => $props['RENDERS']['CODE'],
            'VALUE'          => [],
        ];
        
        
        foreach ($dataprops as $dataprop) {
            \CSaleOrderPropsValue::add($dataprop);
        }
        
        // Привязка корзин к заказу.
        $baskets = \Bitrix\Sale\Internals\BasketTable::getList([
            'filter' => ['FUSER_ID' => \CSaleBasket::GetBasketUserID(), 'ORDER_ID' => null]
        ])->fetchAll();
        
        foreach ($baskets as $basket) {
            $result = \Bitrix\Sale\Internals\BasketTable::update($basket['ID'], ['ORDER_ID' => $oid]);
        }
        
        // Очистка данных.
        if (empty($data)) {
            $this->clear();
        } else {
            $this->data = $dump;
        }
    }
	
	
	
	/**
	 * Загрузка корзины из заказа.
	 */
	public function load(\Wolk\OEM\Order $order)
	{
		$data = $order->getFullData();
		
		// Данные для импорта заказа в текущую корзину.
		$fields = array(
			'PARAMS' => array(
				'WIDTH'    => $data['ORDER']['PROPS']['WIDTH']['VALUE'],
				'DEPTH'    => $data['ORDER']['PROPS']['DEPTH']['VALUE'],
				'SFORM'    => $data['ORDER']['PROPS']['SFORM']['VALUE'],
				'STANDNUM' => $data['ORDER']['PROPS']['STANDNUM']['VALUE'],
				'PAVILION' => $data['ORDER']['PROPS']['PAVILION']['VALUE'],
				'COMMENTS' => $data['ORDER']['USER_DESCRIPTION'],
			),
			'SKETCH' => array(
				'SKETCH_SCENE' => $data['ORDER']['PROPS']['SKETCH_SCENE']['VALUE'],
				'SKETCH_IMAGE' => $data['ORDER']['PROPS']['SKETCH_IMAGE']['VALUE'],
			),
			'ORDERID' => $order->getID(),
		);
		
		
		// Позиции в корзине.
		foreach ($data['BASKETS'] as $basket) {
			$bid = ($basket['PROPS']['BID']['VALUE']) ?: (uniqid(time()));
			
			$item = array(
				'id'  => $bid,
				'pid' => $basket['PRODUCT_ID'],
				'sid' => 0,
				'quantity' => $basket['QUANTITY'],
				'kind'     => '',
				'params'   => (array) json_decode($basket['PROPS']['PARAMS']['VALUE'], true),
				'fields'   => (array) json_decode($basket['PROPS']['FIELDS']['VALUE'], true),
				'included' => ($basket['PROPS']['INCLUDING']['VALUE'] == 'Y'),
			);
			
			if ($basket['PROPS']['STAND']['VALUE'] == 'Y') {
				$item['kind'] = self::KIND_STAND;
				
				$fields['STAND'] = $item;
			} else {
				$product = new Product($item['pid']);
				
				$item['sid']  = $product->getSectionID();
				$item['kind'] = self::KIND_PRODUCT;
				
				$fields['PRODUCTS'][$item['id']] = $item;
			}
		}
		
		// Сохранение данных в сессии.
		$this->setData($fields);
		$this->putSession();
	}
	
}