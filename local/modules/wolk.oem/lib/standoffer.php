<?php

namespace Wolk\OEM;

class StandOffer extends \Wolk\Core\System\IBlockEntity
{
	const IBLOCK_ID = 6;

	protected $id   = null;
	protected $data = [];
	
	
    public function __construct($id = null, $data = [])
    {
		$this->id   = (int) $id;
		$this->data = (array) $data;
    }
	
	
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
}