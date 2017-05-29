<?php

namespace Wolk\OEM;

class Stand extends \Wolk\Core\System\IBlockEntity
{
	const IBLOCK_ID = IBLOCK_STANDS_ID;

	protected $id      = null;
	protected $data    = [];
	protected $price   = null;
	
    
    
    public function __construct($id = null, $data = [])
    {
		parent::__construct($id, $data);
    }
	
	    
	public function getTitle($lang = null)
	{
        $this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
        
		return $this->data['PROPS']['LANG_NAME_' . $lang]['VALUE'];
	}
    
    
    public function getDescription($lang = null)
	{
        $this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
        
		return $this->data['PROPS']['LANG_DESCRIPTION_' . $lang]['VALUE']['TEXT'];
	}
    
    
    public function getPreviewImageSrc()
    {
        $this->load();
        
        return (\CFile::getPath($this->data['PREVIEW_PICTURE']));
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
	public function getLangPrice(Context $context)
	{
		if (is_null($this->price)) {
			$price = \Wolk\OEM\EventStandPricesTable::getList([
				'filter' =>
					[
						'EVENT_ID' => $context->getEventID(),
						'STAND_ID' => $this->getID(),
						'SITE_ID'  => $context->getLang(),
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
     * ��������� ����������� ���������� ����������� �������� ������.
     */
    public function getStandOffer($width, $depth, Context $context)
    {
        $width = (float) $width;
		$depth = (float) $depth;
        
        // ������� ������.
		$area = $width * $depth;
		
        // ����� ��������� ������� � ���������� ��������.
		$stands = StandOffer::getList([
			'order'  => ['PROPERTY_AREA_MAX' => 'DESC'],
			'filter' => [
				'ACTIVE'              => 'Y',
				'=PROPERTY_CML2_LINK' => $this->getID(),
				'<=PROPERTY_AREA_MIN' => $area,
				'>=PROPERTY_AREA_MAX' => $area,
			],
            'limit' => 1
		]);
        
        $stand = null;
        if (!empty($stands)) {
            $stand = reset($stands);
        }
        return $stand;
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