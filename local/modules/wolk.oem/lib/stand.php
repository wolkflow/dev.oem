<?php

namespace Wolk\OEM;

class Stand extends \Wolk\Core\System\IBlockEntity
{
	const IBLOCK_ID = IBLOCK_STANDS_ID;

	protected $id      = null;
	protected $data    = [];
	protected $price   = null;
	protected $context = null;
	
    public function __construct($id = null, $data = [], Context $context = null)
    {
		parent::__construct($id, $data);
        
        // ��������, ��������� � ����� ������ � ������.
        if (empty($context)) {
            $context = new Context();
        }
        $this->context = $context;
    }
	
    
    public function getContext()
    {
        return $this->context;
    }
    
	
	public function getLangTitle($lang = LANG_EN_UP)
	{
		if (empty($lang)) {
			$lang = $this->getLang();
		} else {
			$lang = mb_strtoupper((string) $lang);
		}
		
		$this->load();
		
		return $this->data['PROPS']['LANG_NAME_' . $lang]['VALUE'];
	}
	
	
	public function getTitle()
	{
		return $this->getLangTitle($this->getLang());
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