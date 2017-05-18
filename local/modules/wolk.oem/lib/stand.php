<?php

namespace Wolk\OEM;

class Stand extends \Wolk\Core\System\IBlockEntity
{
	const IBLOCK_ID = IBLOCK_STANDS_ID;

	protected $id      = null;
	protected $data    = [];
	protected $price   = null;
	protected $context = null;
	
    
    
    public function __construct($id = null, $data = [])
    {
		parent::__construct($id, $data);
    }
	
	    
	public function getTitle($lang = null)
	{
        $this->load();
        
        if (empty($lang)) {
            $lang = LANGUAGE_ID;
        }
        $lang = mb_strtoupper($lang);
        
		return $this->data['PROPS']['LANG_NAME_' . $lang]['VALUE'];
	}
	
    
    public function setPrice($price)
    {
        $this->price = (float) $price;
    }
    
    
    public function getPrice()
    {
        return $this->price;
    }
    
    
    /**
     * ������ ������������.
     */
	public function getEquipments()
	{
		
	}	
	
	
    /**
     * ������ �����.
     */
	public function getServices()
	{
		
	}
    
    
    /**
     * ������ ����� ����������.
     */
    public function getMarketings()
	{
		
	}
	
	
	/**
	 * ��������� ���� ������.
	 */
	public function getLangPrice($event_id, $lang)
	{
		if (is_null($this->price)) {
			$price = \Wolk\OEM\EventStandPricesTable::getList([
				'filter' =>
					[
						'EVENT_ID' => intval($event_id),
						'STAND_ID' => $this->getID(),
						'SITE_ID'  => $lang,
					],
				'limit' => 1
			])->fetch();
			
			if ($price) {
				$this->price = (float) $price['PRICE'];
			}
		}
		return $this->price;
	}
	
	
	/**
	 * ������ ���� ������ �� �������.
	 */
	protected static function calc($price, $width, $depth)
    {
        $price = (float) $price;
        $width = (float) $width;
        $depth = (float) $depth;
        
        return ($price * $width * $depth);
    }
}