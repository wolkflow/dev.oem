<?php

namespace Wolk\OEM;

use Wolk\OEM\Products\Base      as Product;
use Wolk\OEM\Products\Equipment as Equipment;
use Wolk\OEM\Products\Service   as Service;
use Wolk\OEM\Products\Marketing as Marketing;


class StandOffer extends \Wolk\Core\System\IBlockEntity
{
	const IBLOCK_ID = IBLOCK_STAND_OFFERS_ID;

	protected $id    = null;
	protected $data  = [];
    protected $price = null;
	
    
    public function setPrice($price)
    {
        $this->price = (float) $price;
    }
    
    
    public function getPrice()
    {
        return $this->price;
    }
    
    
    public function getPriceArea($area)
    {
        return ($this->getPrice() * floatval($area));
    }
    
    
    public function getStandID()
	{
		$this->load();
		
		return ((int) $this->data['PROPS']['CML2_LINK']['VALUE']);
	}
    
    
	public function getStand()
	{
		$this->load();
		
		return (new Stand($this->getStandID()));
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
	public function getBaseProductQIDs()
	{
        $this->load();
        
        // ID продукции в базовой комплектации стенда.
        $ids = array_filter((array) $this->data['PROPS']['PRODUCTS']['VALUE']);
        
        $qids = [];
        foreach ($ids as $i => $id) {
            $qids[$id] = (int) $this->data['PROPS']['PRODUCTS']['DESCRIPTION'][$i];
        }
        unset($ids);
        
        return $qids;
    }
    
    
    /**
     * Список базового оборудования.
     */
	public function getBaseProducts(Context $context = null)
	{
        $pids = $this->getBaseProductQIDs();
        
        // Если задан контекст, можем определить цены на продукцию.
        $prices = [];
        if (!empty($pids) && !is_null($context)) {
            $event  = new Event($context->getEventID());
            $prices = $event->getProductPrices($context);
        }
        unset($event);
        
        
        // Базовая комплектация стенда.
        $products = [];
        foreach ($pids as $id => $count) {
            $product = new Product($id);
            
            // Установка цены.
            if (!empty($prices[$product->getID()])) {
                $product->setPrice($prices[$product->getID()]->getPrice());
            } else {
                $product->setPrice(0);
            }
            
            // Установка количества.
            $product->setCount($count);
            
            $products[$product->getID()] = $product;
        }
        return $products;
	}	
    
    
    /**
     * Список базового оборудования.
     */
	public function getBaseEquipments()
	{
        $this->load();
        
        $ids = (array) $this->data['PROPS']['EQUIPMENT']['VALUE'];
        
        $products = [];
        foreach ($ids as $id) {
            $products[$id] = new Equipment($id);
        }
        return $products;
	}	
	
	
    /**
     * Список базовых услуг.
     */
	public function getBaseServices()
	{
		// NO DATA...
	}
    
    
    /**
     * Список базовых услуг маркетинга.
     */
    public function getBaseMarketings()
	{
		// NO DATA...
	}
}