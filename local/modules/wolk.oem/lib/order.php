<?php

namespace Wolk\OEM;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

\Bitrix\Main\Loader::includeModule('sale');

class Order
{
	const PROP_SKETCH       = 'sketch';
	const PROP_SKETCH_IMAGE = 'SKETCH_IMAGE';
	const PROP_PAVILLION    = 'pavillion';
	const PROP_STANDNUM		= 'standNum';
	const PROP_LANGUAGE     = 'LANGUAGE';
	const PROP_INVOICE		= 'INVOICE';
	const PROP_INVOICE_DATE = 'INVOICE_DATE';
	
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
	
	
	public function getEvent()
	{
		$data = $this->getData();
		
		$event = \CIBlockElement::getByID($data['PROPS']['eventId']['VALUE'])->GetNextElement();
		
		$result = $event->getFields();
		$result['PROPS'] = $event->getProperties();
		
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
		
		$title = Loc::getMessage('ORDER_STATUS_'.$this->data['STATUS_ID'], Loc::loadLanguageFile(__FILE__, $lang), $lang); 
		
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
		
		return $data;
	}
}
