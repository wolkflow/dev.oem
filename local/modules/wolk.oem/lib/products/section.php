<?php

namespace Wolk\OEM\Products;

class Base extends \Wolk\Core\System\IBlockModel
{
	const IBLOCK_ID   = IBLOCK_PRODUCTS_ID;
    const LANG_PREFIX = 'LANG_';
    
    const DEPTH_SECTION = 1; // Раздел
    const DEPTH_GROUP   = 2; // Группа
    const DEPTH_VIEW    = 3; // Вид
    
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
		
		return $this->data['UF_' . self::LANG_PREFIX . 'TITLE_' . $this->getLang()];
	}
    
    
    public function getDescription()
	{
		$this->load();
		
		return $this->data['UF_' . self::LANG_PREFIX . 'NOTE_' . $this->getLang()];
	}
    
    
    /**
     * Получение уровня вложенности.
     */
	public function getDepth()
	{
		return intval($this->data['DEPTH_LEVEL']);
	}
}