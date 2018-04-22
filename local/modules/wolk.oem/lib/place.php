<?php

namespace Wolk\OEM;


class Place extends \Wolk\Core\System\IBlockEntity
{
    const IBLOCK_ID = IBLOCK_PLACES_ID;
    
	protected $lang;
	
    
    public function __construct($id = null, $data = [], $lang = LANG_EN_UP)
    {
		parent::__construct($id, $data);
        
		$this->lang = mb_strtoupper((string) $lang);
    }
	

	public function getLang()
	{
		return $this->lang;
	}
	
	
	
	/**
	 * Получение названия.
	 */
    public function getName()
    {
		$this->load();
		
		return ($this->data['NAME']);
    }
	
	
	/**
	 * Локализованное название мероприятия.
	 */
    public function getTitle($lang = null)
    {
		$this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
		return ($this->data['PROPS']['LANG_TITLE_' . $lang]['VALUE']);
    }
	
	
	/**
	 * Получение широты.
	 */
    public function getCoordLat()
    {
		$this->load();
		
		return ($this->data['PROPS']['COORD_LAT']['VALUE']);
    }
	
	
	/**
	 * Получение долготы.
	 */
    public function getCoordLng()
    {
		$this->load();
		
		return ($this->data['PROPS']['COORD_LNG']['VALUE']);
    }
    
	
	/**
	 * Получение пути к 3D карте.
	 */
	public function getMap3Dpath()
	{
		$this->load();
		
		return (\CFile::getPath($this->data['PROPS']['MAP3D']['VALUE']));
	}
}