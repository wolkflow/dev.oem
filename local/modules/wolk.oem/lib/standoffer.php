<?php

namespace Wolk\OEM;


class StandOffer extends \Wolk\Core\System\IBlockEntity
{
	const IBLOCK_ID = IBLOCK_STAND_OFFERS_ID;

	protected $id   = null;
	protected $data = [];
	
	
    
	public function getStand()
	{
		$this->load();
		
		return (new Stand($this->data['PROPS']['CML2_LINK']['VALUE']));
	}
	
	
	public function getAreaMin()
	{
		$this->load();
		
		return $this->data['PROPS']['AREA_MIN']['VALUE'];
	}
	
	
	public function getAreaMax()
	{
		$this->load();
		
		return $this->data['PROPS']['AREA_MAX']['VALUE'];
	}
    
    
    /**
     * Список базового оборудования.
     */
	public function getBaseEquipments()
	{
		
	}	
	
	
    /**
     * Список базовых услуг.
     */
	public function getBaseServices()
	{
		
	}
    
    
    /**
     * Список базовых услуг маркетинга.
     */
    public function getBaseMarketings()
	{
		
	}
}