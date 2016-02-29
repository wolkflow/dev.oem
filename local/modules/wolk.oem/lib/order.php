<?php

namespace Wolk\OEM;

\Bitrix\Main\Loader::includeModule('sale');

class Order
{
	protected $id;
	protected $data;
	protected $baskets;
	
	
    public function __construct($id = null, $data = [])
    {
		$this->id   = (int) $id;
		$this->data = (array) $data;
    }
	
	
	/**
     * Получение ID.
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Получение данных в виде массива.
     */
    public function getData()
    {
        $this->load();

        return $this->data;
    }
	
	
	/**
	 * Загрузка данных заказа.
	 *
	 * @param bool $force
	 * @return array
	 */
	public function load($force = false)
	{
		if (empty($this->data) || $force) {
			$this->data = \CSaleOrder::GetByID($this->getID());
			$this->data['PROPS'] = \Wolk\Core\Helpers\SaleOrder::getProperties($this->getID());
		}
		return $this->data;
	}
	
	
	/**
	 * Загрузка позиций заказа.
	 *
	 * @param bool $force
	 * @return array
	 */
	public function getBaskets($force = false)
	{
		if (empty($this->baskets) || $force) {
			$this->baskets = \Wolk\Core\Helpers\SaleOrder::getBaskets($this->getID());
		}
		return $this->baskets;
	}
	
	
	public function getStandNumber()
	{
		$this->load();
		
		return $this->data['PROPS']['standNum']['VALUE'];
	}
	
	
	public function getPavilion()
	{
		$this->load();
		
		return $this->data['PROPS']['pavillion']['VALUE'];
	}
}
