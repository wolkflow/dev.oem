<?php

namespace Wolk\OEM\Products;

class Base extends \Wolk\Core\System\IBlockEntity
{
	const IBLOCK_ID   = 5;//IBLOCK_PRODUCTS_ID;
    const LANG_PREFIX = 'LANG_';
    
	protected $lang = LANG_EN_UP;
	
	
    public function __construct($id = null, $data = [], $lang = LANG_EN_UP)
    {
        parent::__construct($id, $data);
		
		$this->lang = mb_strtoupper((string) $lang);
    }
    
    
    public function getLang()
	{
		return $this->lang;
	}
	
	
	public function getTitle()
	{
		$this->load();
		
		return $this->data['PROPS'][self::LANG_PREFIX . 'TITLE_' . $this->getLang()]['VALUE'];
	}
    
    
    public function getDescription()
	{
		$this->load();
		
		return $this->data['PROPS'][self::LANG_PREFIX . 'DESCRIPTION_' . $this->getLang()]['VALUE'];
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
}