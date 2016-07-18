<?php

namespace Wolk\OEM;

class Service extends \Wolk\Core\System\IBlockEntity
{
	const IBLOCK_ID = 5;
	
	protected $id    = null;
	protected $data  = [];
	protected $price = null;

	
    public function __construct($id = null, $data = [])
    {
		$this->id   = (int) $id;
		$this->data = (array) $data;
    }
	
	
	public function getPrice($default = true)
	{
		if (!empty($this->price) && $default) {
			$this->load();
			// return $this->data[]
		}
		return $this->price;
	}
	
	
	public function setPrice($price)
	{
		$this->price = (float) $price;
	}
}