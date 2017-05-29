<?php

namespace Wolk\OEM\Products;

class Base extends \Wolk\Core\System\IBlockModel
{
	const IBLOCK_ID   = IBLOCK_PRODUCTS_ID;
    const LANG_PREFIX = 'LANG_';
    
	protected $price;
    protected $count;
	
	
    public function __construct($id = null, $data = [], $lang = LANG_EN_UP)
    {
        parent::__construct($id, $data);
		
		$this->lang = mb_strtoupper((string) $lang);
    }
    
    
    public function getLang()
	{
		return $this->lang;
	}
	
	
	public function getTitle($lang = null)
	{
		$this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
		return $this->data['PROPS'][self::LANG_PREFIX . 'TITLE_' . $lang]['VALUE'];
	}
    
    
    public function getDescription($lang = null)
	{
		$this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
		return $this->data['PROPS'][self::LANG_PREFIX . 'DESCRIPTION_' . $lang]['VALUE'];
	}
    
    
    public function getSketchWidth()
    {
        $this->load();
        
        return floatval($this->data['PROPS']['SKETCH_WIDHT']['VALUE']);
    }
    
    
    public function getSketchHeight()
    {
        $this->load();
        
        return floatval($this->data['PROPS']['SKETCH_HEIGHT']['VALUE']);
    }
    
    
    /**
     * Получение цены.
     */
	public function getPrice($default = true)
	{
		if (!empty($this->price) && $default) {
			$this->load();
		}
		return $this->price;
	}
	
	
    /**
     * Установка цены.
     */
	public function setPrice($price)
	{
		$this->price = (float) $price;
	}
    
    
    /**
     * Получение количества.
     */
	public function getCount($default = true)
	{
		if (!empty($this->count) && $default) {
			$this->load();
		}
		return $this->count;
	}
	
	
    /**
     * Установка количества.
     */
	public function setCount($count)
	{
		$this->count = (int) $count;
	}
}
