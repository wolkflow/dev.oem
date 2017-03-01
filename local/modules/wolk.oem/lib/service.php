<?php

namespace Wolk\OEM;

class Service extends \Wolk\Core\System\IBlockEntity
{
	const IBLOCK_ID = 5;
	
	protected $price = null;

	
	/**
     * Получение цены/
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