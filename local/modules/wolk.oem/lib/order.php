<?php

namespace Wolk\OEM;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

\Bitrix\Main\Loader::includeModule('sale');

class Order
{
	const PROP_SKETCH          = 'sketch';
	const PROP_SKETCH_IMAGE    = 'SKETCH_IMAGE';
	const PROP_PAVILLION       = 'pavillion';
	const PROP_STANDNUM		   = 'standNum';
	const PROP_LANGUAGE        = 'LANGUAGE';
	const PROP_INVOICE		   = 'INVOICE';
	const PROP_INVOICE_DATE    = 'INVOICE_DATE';
    const PROP_SURCHARGE       = 'SURCHARGE';
	const PROP_SURCHARGE_PRICE = 'SURCHARGE_PRICE';
	
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
	
	
	public function getUser()
	{
		$this->load();
		
		return (\CUser::getByID($this->data['USER_ID'])->Fetch());
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
	
	
	public function getLanguage()
	{
		$this->load();
		
		return $this->data['PROPS'][self::PROP_LANGUAGE]['VALUE_ORIG'];
	}
	
	
    public function getSurchargePercent()
	{
		$this->load();
		
		return $this->data['PROPS'][self::PROP_SURCHARGE]['VALUE_ORIG'];
	}
    
    
	public function getSurchargePrice()
	{
		$this->load();
		
		return $this->data['PROPS'][self::PROP_SURCHARGE_PRICE]['VALUE_ORIG'];
	}
	
	
	public function getEvent($asobject = false)
	{
		$data = $this->getData();
		
        if ($asobject) {
            return (new Event($data['PROPS']['eventId']['VALUE']));
        }
        
		$event = \CIBlockElement::getByID($data['PROPS']['eventId']['VALUE'])->GetNextElement();
		
		if ($event) {
			$result = $event->getFields();
			$result['PROPS'] = $event->getProperties();
		}
		return $result;
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
			$summary += (float) $basket['SUMMARY_PRICE'];
		}
		
		$surcharge = (float) $this->getSurchargePrice();
		
		$prices = [
			'BASKET'         => (float) $summary,
			'TAX'            => (float) $this->data['TAX_VALUE'],
			'SURCHARGE'      => (float) $surcharge,
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
    
}
