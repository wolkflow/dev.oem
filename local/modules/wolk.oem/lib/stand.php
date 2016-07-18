<?php

namespace Wolk\OEM;

class Stand extends \Wolk\Core\System\IBlockEntity
{
	const IBLOCK_ID = 2;

	protected $id    = null;
	protected $data  = [];
	protected $price = null;
	protected $lang  = 'EN';
	
	
    public function __construct($id = null, $data = [], $lang = 'EN')
    {
		$this->id   = (int) $id;
		$this->data = (array) $data;
		$this->lang = mb_strtoupper((string) $lang);
    }
	
	
	public function getLang()
	{
		return $this->lang;
	}
	
	
	public function getLangTitle($lang = 'EN')
	{
		if (empty($lang)) {
			$lang = $this->getLang();
		} else {
			$lang = mb_strtoupper((string) $lang);
		}
		
		$this->load();
		
		return $this->data['PROPS']['LANG_NAME_' . $lang]['VALUE'];
	}
	
	
	public function getLangTitle()
	{
		$this->load();
		
		return $this->data['PROPS']['LANG_NAME_' . $this->getLang()]['VALUE'];
	}
	
	
	public function getEquipment()
	{
		
	}	
	
	
	public function getServices()
	{
		
	}
	
	
	/**
	 * Получение цены стенда.
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
	 * Расчет площади стенда.
	 */
	protected function calc($price, $width, $depth)
    {
        return ($price * $width * $depth);
    }
}