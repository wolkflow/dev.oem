<?php

namespace Wolk\OEM;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

use Wolk\OEM\Products\Base as Product;
use Wolk\OEM\OrderSketch as OrderSketch;

\Bitrix\Main\Loader::includeModule('sale');

class Order
{
	const PROP_SKETCH          = 'SKETCH';
    const PROP_SKETCH_SCENE    = 'SKETCH_SCENE'; // Deprecated
	const PROP_SKETCH_IMAGE    = 'SKETCH_IMAGE'; // Deprecated
	const PROP_SKETCH_FILE     = 'SKETCH_FILE';  // Deprecated
	const PROP_PAVILLION       = 'PAVILION';
	const PROP_STANDNUM		   = 'STANDNUM';
	const PROP_LANGUAGE        = 'LANGUAGE';
	const PROP_INVOICE		   = 'INVOICE';
	const PROP_INVOICE_DATE    = 'INVOICE_DATE';
    const PROP_SURCHARGE       = 'SURCHARGE';
	const PROP_SURCHARGE_PRICE = 'SURCHARGE_PRICE';
	const PROP_RATE            = 'RATE';
	const PROP_RATE_CURRENCY   = 'RATE_CURRENCY';
	const PROP_RENDERS         = 'RENDERS';
	const PROP_FILEPDF         = 'FILEPDF';
	
	const STATUS_NOT_APPROVAL = 'N';
	const STATUS_APPROVAL     = 'A';
	const STATUS_CANCELED     = 'C';
	const STATUS_EDITED       = 'E';
	
	protected $id;
	protected $data;
	protected $baskets;
	protected $individual;
	
	
	
	
    public function __construct($id = null, $data = [])
    {
		$this->id   = (int) $id;
		$this->data = (array) $data;
    }
	
	
	/**
     * Получение ID.
     */
    public function getID()
    {
        return $this->id;
    }
	
	
    /**
     * Получение данных в виде массива.
     */
    public function getData()
    {
        $this->load();

        return $this->data;
    }
	
	
	/**
	 * Загрузка данных заказа.
	 *
	 * @param bool $force
	 * @return array
	 */
	public function load($force = false)
	{
		if (empty($this->data) || $force) {
			$this->data = \CSaleOrder::GetByID($this->getID());
			$this->data['PROPS'] = \Wolk\Core\Helpers\SaleOrder::getProperties($this->getID());
		}
		return $this->data;
	}
	
	
	public function getDateCreated()
	{
		$this->load();
		
		return $this->data['DATE_INSERT'];
	}
	
	
	public function getPrice()
	{
		$this->load();
		
		return floatval($this->data['PRICE']);
	}
	
	
	public function getTAX()
	{
		$this->load();
		
		return floatval($this->data['TAX_VALUE']);
	}
	
	
	public function getSurcharge()
	{
		$this->load();
		
		return floatval($this->data['PROPS']['SURCHARGE']['VALUE']);
	}
	
	
	public function getCurrency()
	{
		$this->load();
		
		return $this->data['CURRENCY'];
	}
	
	
	/**
	 * Загрузка позиций заказа.
	 *
	 * @param bool $force
	 * @return array
	 */
	public function getBaskets($force = false)
	{
		if (empty($this->baskets) || $force) {
			$this->baskets = \Wolk\Core\Helpers\SaleOrder::getBaskets($this->getID());
		}
		return $this->baskets;
	}
	
	
	public function getUserID()
	{
		$this->load();
		
		return intval($this->data['USER_ID']);
	}
	
	
	public function getUser()
	{
		$this->load();
		
		return (\CUser::getByID($this->getUserID())->Fetch());
	}
	
	
	public function getStandNumber()
	{
		$this->load();
		
		return $this->data['PROPS'][self::PROP_STANDNUM]['VALUE'];
	}
	
	
	public function getPavilion()
	{
		$this->load();
		
		return $this->data['PROPS'][self::PROP_PAVILLION]['VALUE'];
	}
	
	
	public function getStatus()
	{
		$this->load();
		
		return $this->data['STATUS_ID'];
	}
	
	
	public function canEdit()
	{
		return ($this->getStatus() == self::STATUS_NOT_APPROVAL);
	}
	
	
	public function getLanguage()
	{
		$this->load();
		
		return $this->data['PROPS'][self::PROP_LANGUAGE]['VALUE_ORIG'];
	}
	
	
    public function getSurchargePercent()
	{
		$this->load();
		
		return floatval($this->data['PROPS'][self::PROP_SURCHARGE]['VALUE_ORIG']);
	}
    
    
	public function getSurchargePrice()
	{
		$this->load();
		
		return floatval($this->data['PROPS'][self::PROP_SURCHARGE_PRICE]['VALUE_ORIG']);
	}
	
	
	public function getRawRate()
	{
		$this->load();
		
		return floatval($this->data['PROPS'][self::PROP_RATE]['VALUE_ORIG']);
	}
	
	
	public function getRate()
	{
		$rate = $this->getRawRate();
		if (empty($rate)) {
			$rate = 1;
		}
		return floatval($rate);
	}
	
	
	public function getRawRateCurrency()
	{
		$this->load();
		
		return strval($this->data['PROPS'][self::PROP_RATE_CURRENCY]['VALUE']);
	}
	
	
	public function getRateCurrency()
	{
		$currency = $this->getRawRateCurrency();
		if (empty($currency)) {
			$currency = $this->getCurrency();
		}
		return strval($currency);
	}
	
	
	public function getBillNumber()
	{
		$this->load();
		
		return strval($this->data['PROPS']['BILL']['VALUE']);
	}
	
	
	public function getAdminComments()
	{
		$this->load();
		
		return strval($this->data['COMMENTS']);
	}
	
	
	public function getComments()
	{
		$this->load();
		
		return strval($this->data['USER_DESCRIPTION']);
	}
		
	
	public function getEvent($asobject = false)
	{
		$data = $this->getData();
		
        if ($asobject) {
            return (new Event($data['PROPS']['EVENT_ID']['VALUE']));
        }
        
		$event = \CIBlockElement::getByID($data['PROPS']['EVENT_ID']['VALUE'])->GetNextElement();
		
		if ($event) {
			$result = $event->getFields();
			$result['PROPS'] = $event->getProperties();
		}
		return $result;
	}
    
	
	/**
     * Получение ширины скетча.
     */
    public function getSketchWidth()
    {
        $this->load();
		
		return floatval($this->data['PROPS']['WIDTH']['VALUE']);
    }
	
	
	/**
     * Получение глубины скетча.
     */
    public function getSketchDepth()
    {
        $this->load();
		
		return floatval($this->data['PROPS']['DEPTH']['VALUE']);
    }
	
    
    /**
     * Получение объекта скетча.
     */
    public function getSketch()
    {
        $sketch = reset(OrderSketch::getList([
			'filter' => [OrderSketch::FIELD_ORDER_ID => $this->getID()],
			'limit'  => 1
		]));
		
        return $sketch;
    }
	
	
	/**
	 * Отправка изображения скетча в бразуер.
	 */
	public function showSketchJPG()
	{
		$this->load();
		
		$data  = base64_decode($this->data['PROPS'][self::PROP_SKETCH_IMAGE]['VALUE']);
		$image = imagecreatefromstring($data);
		if ($image !== false) {
			header('Content-Type: image/jpeg');
			header('Content-Disposition: attachment; filename="sketch-'.$this->getID().'.jpg"'); 
			header('Content-Length: ' . strlen($image));
			
			imagejpeg($image);
			imagedestroy($image);
		}
	}
	
	
	/**
	 * Получение счета.
	 */ 
	public function getInvoice()
	{
		$this->load();
		
		return intval($this->data['PROPS'][self::PROP_INVOICE]['VALUE']);
	}
	
	
	/**
	 * Получение пути к счету.
	 */ 
	public function getInvoiceLink()
	{
		$this->load();
		
		return \CFile::getPath($this->getInvoice());
	}
	
	
	/**
	 * Получение даты выставления счета.
	 */ 
	public function getInvoiceDate()
	{
		$this->load();
		
		return intval($this->data['PROPS'][self::PROP_INVOICE_DATE]['VALUE']);
	}
	
	
	/**
	 * Индивидуальный стенд.
	 */
	public function isIndividual()
	{
		if (!isset($this->individual)) {
			//return $this->individual;
		}
		$this->individual = true;
		
		$baskets = $this->getBaskets();
		
		foreach ($baskets as $basket) {
			if ($basket['PRODUCT_ID'] > 0) {
				$element = \CIBlockElement::getByID($basket['PRODUCT_ID'])->Fetch();

				// Элемент не существует.
				if (!$element) {
					continue;
				}
				
				if ($element['IBLOCK_ID'] == STANDS_IBLOCK_ID) {
					$this->individual = false;
					break;
				}
			}
		}
		return $this->individual;
	}
	
	
	/**
	 * Получение названия статуса с учтом языка
	 */
	public function getStatusLangTitle($lang = null)
	{
		if (is_null($lang)) {
			$lang = $this->getLanguage();
		}
		$this->load();
		
		$title = self::getStatusLangTitleStatic($this->data['STATUS_ID'], $lang); 
		
		return $title;
	}
    
    
    /**
	 * Получение названия статуса с учтом языка
	 */
	public static function getStatusLangTitleStatic($status, $lang = null)
	{
		if (is_null($lang)) {
			$lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
		}
		$title = Loc::getMessage('ORDER_STATUS_'.$status, Loc::loadLanguageFile(__FILE__, $lang), $lang); 
		
		return $title;
	}
	
	
	/**
	 * Ссылка на редактирование заказа.
	 */
	public function getLinkEdit($step = 1)
	{
		$link = '/events/#EVENT#/?OID=#OID#';
		$data = $this->getFullData();
		
		$event = strtolower($data['EVENT']['CODE']);
		$step  = intval($step);
		$type  = strtolower($data['ORDER']['PROPS']['TYPESTAND']['VALUE']);
		$width = $data['ORDER']['PROPS']['WIDTH']['VALUE'];
		$depth = $data['ORDER']['PROPS']['DEPTH']['VALUE'];
		$sform = strtolower($data['ORDER']['PROPS']['SFORM']['VALUE']);
		$oid   = $this->getID();
		
		if (empty($stand)) {
			$stand = 'row';
		}
		
		$link = str_replace(
			['#EVENT#', '#OID#'],
			[$event, $oid],
			$link
		);
		
		return $link;
	}
	
	
	/** 
	 * Получение полных данных о заказе.
	 */
	public function getFullData()
	{
		$data = array();
		
		$data['ORDER']   = $this->getData();
		$data['BASKETS'] = $this->getBaskets();
		$data['USER']    = $this->getUser();
		$data['EVENT']   = $this->getEvent();
		$data['PRICES']  = $this->getPriceInfo();
		
		return $data;
	}
	
	
	/**
	 * Получение информации о цене заказа.
	 */
	public function getPriceInfo()
	{
		$summary = 0;
		foreach ($this->getBaskets() as $basket) {
			// Исключение продукции входящей в стоимость.
			if ($basket['PROPS']['INCLUDING']['VALUE'] == 'Y') {
				continue;
			}
			$summary += (float) $basket['SUMMARY_PRICE'];
		}
		
		$surcharge = (float) $this->getSurchargePrice();
		
		$prices = [
			'BASKET'         => (float) $summary,
			'TAX'            => (float) $this->data['TAX_VALUE'],
			'SURCHARGE'      => (float) $surcharge,
            'TOTAL_WITH_SUR' => (float) $summary + $surcharge,
			'TOTAL_WITH_VAT' => (float) $this->data['PRICE'] - $surcharge,
			'TOTAL'          => (float) $this->data['PRICE'],
		];
		
		return $prices;
	}
    
    
    /**
     * Пересчет цен заказа.
     */ 
    public function recalc($surcharge = null)
    {
        global $DB;
        
        $event  = $this->getEvent(true);
        $prices = $this->getPriceInfo();
        
        // Цена товаров и НДС.
        $vat   = 0;
        $price = $prices['BASKET'];
        
        // Наценка.
        if (is_null($surcharge)) {
            $surcharge = $this->getSurchargePercent();
        }
        $surcharge = (float) $surcharge;
        $surcprice = 0;
        if ($surcharge > 0) {
            $surcprice = $price * $surcharge / 100;
            $price = $price + $surcprice;
        }
        
        // НДС.
        if (!$event->hasVAT()) {
            $vat   = $price * VAT_DEFAULT / 100;
            $price = $price + $vat;
        }
        
        // Данные для обновления заказа.
        $fields = [
            'PRICE'     => $price,
            'TAX_VALUE' => $vat
        ];
        
        
        // Свойства наценки.
        $props = [
            [
                'ORDER_ID'       => $this->getID(),
                'ORDER_PROPS_ID' => $this->data['PROPS'][self::PROP_SURCHARGE]['ORDER_PROPS_ID'],
                'NAME'           => $this->data['PROPS'][self::PROP_SURCHARGE]['NAME'] ?: 'Наценка',
                'CODE'           => self::PROP_SURCHARGE,
                'VALUE'          => $surcharge
            ],
            [
                'ORDER_ID'       => $this->getID(),
                'ORDER_PROPS_ID' => $this->data['PROPS'][self::PROP_SURCHARGE_PRICE]['ORDER_PROPS_ID'],
                'NAME'           => $this->data['PROPS'][self::PROP_SURCHARGE_PRICE]['NAME'] ?: 'Сумма наценки',
                'CODE'           => self::PROP_SURCHARGE_PRICE,
                'VALUE'          => $surcprice
            ]
        ];
        
        
        $DB->StartTransaction();
        
        // Добавление свойств заказа.
        foreach ($props as $prop) {
            \Bitrix\Sale\Internals\OrderPropsValueTable::update($this->data['PROPS'][$prop['CODE']]['ID'], $prop);
        }
        unset($props, $prop);
        
        // Сохранение цен заказа.
        $result = \Bitrix\Sale\Internals\OrderTable::update($this->getID(), $fields);
        
        if ($result && $result->isSuccess()) {
            $DB->Commit();
            return true;
        } else {
            $DB->Rollback();
            return false;
        }
    }
	
	
	/**
	 * Создание рендеров заказа.
	 */
	public function makeRenders($save = false)
	{
		$renders = \Wolk\OEM\Render::order($this);
		
		if ($save) {
			if (!empty($renders)) {
				\Wolk\Core\Helpers\SaleOrder::saveProperty($this->getID(), self::PROP_RENDERS, $renders);
			}
		}
		return $renders;
	}
	
	
	/**
	 * Получение списка рендеров заказа.
	 */
	public function getRenders()
	{
		$data = $this->getData();
		
		$renders = array_filter((array) unserialize($data['PROPS']['RENDERS']['VALUE']));
		
		if (empty($renders)) {
			$renders = $this->makeRenders(true);
		}
		return $renders;
	}
	
	
	/**
	 * Создание файла PDF с рендерами.
	 */
	public function makeFilePDF($save = false)
	{
		// Печать
		$print = new \Wolk\OEM\Prints\Render($this->getID(), $this->getLanguage());
		$print->make();
		
		// Путь к файлу PDF.
		$file = $print->getPath();
		
		if ($save) {
			if (is_readable($_SERVER['DOCUMENT_ROOT'] . $file)) {
				\Wolk\Core\Helpers\SaleOrder::saveProperty($this->getID(), self::PROP_FILEPDF, $file);
			}
		}
		return $file;
	}
	
	
	/**
	 * Получение ссылки на PDF.
	 */
	public function getFilePDF($make = false)
	{
		$data = $this->getData();
		$file = strval($this->data['PROPS'][self::PROP_FILEPDF]['VALUE']);
		
		if ($make) {
			if (empty($file)) {
				$file = $this->makeFilePDF(true);
			}
		}
		return $file;
	}
    	
	
	/**
	 * Получение надписи на фризовой панели.
	 */
	public function getFasciaBaskets()
    {
		$pids = \Wolk\OEM\Products\Base::getSpecialTypeIDs();
		$items = [];
		$baskets = $this->getBaskets();
		foreach ($baskets as $basket) {
			if (in_array($basket['PRODUCT_ID'], $pids[Product::SPECIAL_TYPE_FASCIA])) {
				$items []= $basket;
			}
		}
		return $items;
	}
	
	
	/**
	 * Получение форм подвесных конструкций.
	 */
	public function getFormHandingBaskets()
    {
		$pids  = \Wolk\OEM\Products\Base::getSpecialTypeIDs();
		$items = [];
		$baskets = $this->getBaskets();
		
		foreach ($baskets as $basket) {
			if (in_array($basket['PRODUCT_ID'], $pids[Product::SPECIAL_TYPE_HANDING])) {
				$items []= $basket;
			}
		}
		return $items;
	}
	
    
    
    /**
     * Получение информации о цене заказа.
     *
     * $price - Общая стоимость товаров и услуг.
     * $percent - Процент наценки.
     * $incvat - Включен ли НДС в стоимость
     * $vatpercent - Процент НДС
     */
    public static function getFullPriceInfo($price, $percent = 0, $incvat = false, $vatpercent = VAT_DEFAULT)
    {
        $price      = (float) $price;
        $percent    = (float) $percent;
        $vatpercent = (float) $vatpercent;
        $incvat     = (bool)  $incvat;
        
        // Процент НДС в цене.
        $unvat = $vatpercent / (100 + $vatpercent);
        
        // Сумма наценки.
        $surcharge = $price * $percent / 100;
        
        // Общая сумма с наценкой.
        $total = $price + $surcharge;
        
        // Сумма НДС.
        $vatprice = (!$incvat) ? ($total * $vatpercent / 100) : ($total * $unvat);
        
        // Общая сумма с НДС и наценкой.
        $summary = (!$incvat) ? ($total + $vatprice) : ($total);
        
        
        $prices = [
            'INCLUDE_VAT'       => $incvat,
			'SURCHARGE_PERCENT' => (float) $percent,
            'SURCHARGE_PRICE'   => (float) $surcharge,
            'VAT_PERCENT'       => (float) $vatpercent,
            'VAT_PRICE'         => (float) $vatprice,
			'PRICE'             => (float) $price,
            'TOTAL'             => (float) $total,
			'SUMMARY'           => (float) $summary,
		];
		
		return $prices;
    }
    
    
    
    
    
    /**
     * Создание и изменение заказа.
     */
    public static function make($data)
    {
        // Пользователь.
        $uid = (int) $data['UID'];
        
        // Контекст.
        $context = new \Wolk\OEM\Context($data['EID'], $data['TYPESTAND'], $data['LANGUAGE']);
        
        // Текущая корзина.
        $products = (array) $data['PRODUCTS'];
        
        // Мероприятие.
        $event = new \Wolk\OEM\Event($data['EID']);
                
        // Валюта заказа.
        $currency = (!empty($data['CURRENCY'])) ? (strval($data['CURRENCY'])) : ('EUR');
        
        // Итоговая сумма.
        $price = 0;
        
        
        // Удаление старых корзин.
        if (!empty($data['OID'])) {
            $obasket = \Bitrix\Sale\Order::load($data['OID'])->getBasket();
            $bresult = \Bitrix\Sale\Internals\BasketTable::getList(['filter' => ['ORDER_ID' => $data['OID']]]);
            
            while ($basket = $bresult->fetch()) {
                $obasket->getItemById($basket['ID'])->delete();
            }
            $obasket->save();
        } else {
            $salebasket = new \CSaleBasket();
            $salebasket->DeleteAll($uid);
        }
        
        
        // Сохранение стенда.
        $stand = null;
        if (!empty($data['SID'])) {
            $stand = new \Wolk\OEM\Stand($data['SID']);
        }
        
        if (!empty($stand)) {
            // Тип продукции.
            $note = 'STAND.' . strtoupper($context->getType());
            
            $fields = [
                'PRODUCT_ID'     => $stand->getID(),
                'QUANTITY'       => floatval($data['STANDWIDTH']) * floatval($data['STANDDEPTH']),
                'PRICE'          => floatval($data['STANDPRICE']),
                'CUSTOM_PRICE'   => 'Y',
                'CURRENCY'       => $currency,
                'LID'            => SITE_DEFAULT,
                'NAME'           => $stand->getTitle($context->getLang()),
                'SET_PARENT_ID'  => 0,
                'TYPE'           => 0,
                'FUSER_ID'       => $uid,
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
                $basket = \Bitrix\Sale\Basket::loadItemsForFUser($uid, SITE_DEFAULT);
                
                $basket_item = $basket->getItemByID($result->getID());
                $basket_prop = $basket_item->getPropertyCollection();
                $basket_prop->setProperty($props);
                $basket_prop->save();
            }
            
            
            // Общая стоимость продукции.
            $price += ($fields['PRICE'] * $fields['QUANTITY']);
        }
        
        // Сохранение продукции.
        foreach ($products as $product) {
			
			if (empty($product['ID'])) {
                continue;
            }
            
            // Продукиця.
            $element = new \Wolk\OEM\Products\Base($product['ID']);
            
            if (empty($element)) {
                continue;
            }
            
            $note = 'PRODUCT.' . (($product['INCLUDED']) ? ('BASE') : ('SALE'));
            
            $fields = [
                'PRODUCT_ID'     => $element->getID(),
                'QUANTITY'       => floatval($product['QUANTITY']),
                'PRICE'          => floatval($product['PRICE']),
                'CUSTOM_PRICE'   => 'Y',
                'CURRENCY'       => $currency,
                'LID'            => SITE_DEFAULT,
                'NAME'           => ($element->getTitle($context->getLang())) ?: ($product['NAME']),
                'SET_PARENT_ID'  => 0,
                'TYPE'           => 0,
                'FUSER_ID'       => $uid,
                'RECOMMENDATION' => $note,
            ];
            
            $props = [];
            
            // Поля заказа продукции.
            $props []= [
                'NAME'  => 'Поля заказа продукции',
                'CODE'  => 'FIELDS',
                'VALUE' => json_encode(array())
            ];
            
            // Свойства продукции.
            $props []= [
                'NAME'  => 'Свойства продукции',
                'CODE'  => 'PARAMS',
                'VALUE' => json_encode((array) $product['PROPS'])
            ];
            
            // Продукция включена в стенд.
            if ($product['INCLUDED']) {
                $props []= [
                    'NAME'  => 'Стандартная комплектация',
                    'CODE'  => 'INCLUDING',
                    'VALUE' => 'Y'
                ];
            }
			
            // Добавление корзины.
            $result = \Bitrix\Sale\Internals\BasketTable::add($fields);
            
            if (is_object($result)) {
                // Корзина пользователя.
                $basket = \Bitrix\Sale\Basket::loadItemsForFUser($uid, SITE_DEFAULT);
                
                $basket_item = $basket->getItemByID($result->getID());
                $basket_prop = $basket_item->getPropertyCollection();
                $basket_prop->setProperty($props);
                $basket_prop->save();
            }
            
            // Суммирование цены.
            $price += ($fields['PRICE'] * $fields['QUANTITY']);  
        }
        
        // Цены.
        $infoprices = self::getFullPriceInfo($price, $data['SURCHARGE'], $data['VAT']);
		
        $fields = [
            'LID'              => SITE_DEFAULT,
            'USER_ID'          => $uid,
            'PERSON_TYPE_ID'   => PERSON_TYPE_DETAULT,
            'DELIVERY_ID'      => DELIVERY_DETAULT,
            'PAYED'            => 'N',
            'CANCELED'         => 'N',
            'STATUS_ID'        => 'N',
            'DISCOUNT_VALUE'   => '',
            'PRICE'            => $infoprices['SUMMARY'],
            'TAX_VALUE'        => (!$data['VAT']) ? ($infoprices['VAT_PRICE']) : (0),
            'CURRENCY'         => $currency,
            'USER_DESCRIPTION' => $data['COMMENTS'],
        ];

        
        // Созданеи заказа.
        if (empty($data['OID'])) {
            $oid = \CSaleOrder::add($fields);
        } else {
			$result = \Bitrix\Sale\Internals\OrderTable::update($data['OID'], $fields);
			
            if (is_object($result) && $result->getID() > 0) {
				$oid = $result->getID();
                \CSaleOrderPropsValue::deleteByOrder($oid);
            }
        }
        
        
        // Сохранение свойств заказа.
        $result = \CSaleOrderProps::getList();
        $props = [];
        while ($prop = $result->fetch()) {
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
            'ORDER_PROPS_ID' => $props['SFORM']['ID'],
            'NAME'           => 'Тип стенда',
            'CODE'           => 'SFORM',
            'VALUE'          => ($data['SFORM']) ?: ('row'),
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['WIDTH']['ID'],
            'NAME'           => 'Ширина стенда',
            'CODE'           => 'WIDTH',
            'VALUE'          => $data['STANDWIDTH'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['DEPTH']['ID'],
            'NAME'           => 'Глубина стенда',
            'CODE'           => 'DEPTH',
            'VALUE'          => $data['STANDDEPTH'],
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
        
		/*8
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SKETCH']['ID'],
            'NAME'           => 'Скетч',
            'CODE'           => 'SKETCH_SCENE',
            'VALUE'          => $data['SKETCH_SCENE'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['SKETCH_IMAGE']['ID'],
            'NAME'           => 'Изображение скетча',
            'CODE'           => 'SKETCH_IMAGE',
            'VALUE'          => $data['SKETCH_IMAGE'],
        ];
        */
		
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['STANDNUM']['ID'],
            'NAME'           => 'Номер стенда',
            'CODE'           => 'STANDNUM',
            'VALUE'          => $data['STANDNUM'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['PAVILION']['ID'],
            'NAME'           => 'Павильон',
            'CODE'           => 'PAVILION',
            'VALUE'          => $data['PAVILION'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['TYPE']['ID'],
            'NAME'           => 'Тип заказа',
            'CODE'           => 'TYPE',
            'VALUE'          => $data['TYPE'],
        ];
        
        $dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['TYPESTAND']['ID'],
            'NAME'           => 'Тип застройки',
            'CODE'           => 'TYPESTAND',
            'VALUE'          => $context->getType(),
        ];
		
		$dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['RENDERS']['ID'],
            'NAME'           => 'Рендеры',
            'CODE'           => $props['RENDERS']['CODE'],
            'VALUE'          => [],
        ];
        
		$dataprops []= [
            'ORDER_ID'       => $oid,
            'ORDER_PROPS_ID' => $props['INCLUDE_VAT']['ID'],
            'NAME'           => 'Вклчен НДС',
            'CODE'           => $props['INCLUDE_VAT']['CODE'],
            'VALUE'          => $data['VAT'],
        ];
		
		
		// Удаление данных о скетче.
		OrderSketch::clear($oid);
		
		// Сохранение скетча.
		$sketch = new OrderSketch();
		$sketch->add([
			OrderSketch::FIELD_ORDER_ID => $oid,
			OrderSketch::FIELD_SCENE    => $data['SKETCH_SCENE'],
			OrderSketch::FIELD_IMAGE    => $data['SKETCH_IMAGE']
		]);
		$sketch->saveFile();
		
		
        
        foreach ($dataprops as $dataprop) {
            \CSaleOrderPropsValue::add($dataprop);
        }
        
        if (!$oid) {
            throw new \Exception("Can't create order.");
        }
        
        $baskets = \Bitrix\Sale\Internals\BasketTable::getList([
            'filter' => ['FUSER_ID' => $uid, 'ORDER_ID' => null]
        ])->fetchAll();
        
        foreach ($baskets as $basket) {
            $result = \Bitrix\Sale\Internals\BasketTable::update($basket['ID'], ['ORDER_ID' => $oid]);
        }
        
        return (new self($oid));
    }
}
