<?php

namespace Wolk\OEM;

use \Wolk\OEM\Products\Base as Product;

class Event extends \Wolk\Core\System\IBlockEntity
{
    const IBLOCK_ID = IBLOCK_EVENTS_ID;

	protected $id     = null;
	protected $data   = [];
	protected $lang   = LANG_EN_UP;
	protected $prices = ['stands' => [], 'equipments' => [], 'services' => [], 'marketings' => []];
	
    protected $preselect;
	
    public function __construct($id = null, $data = [], $lang = LANG_EN_UP)
    {
		parent::__construct($id, $data);
        
		$this->lang = mb_strtoupper((string) $lang);
    }
	

	public function getLang()
	{
		return $this->lang;
	}
	
	
	/**
	 * Локализованное название мероприятия.
	 */
    public function getTitle()
    {
		$this->load();
		
		return ($this->data['PROPS']['LANG_TITLE_' . $this->getLang()]['VALUE']);
    }
	
	
	/**
	 * Получение расписания мероприятия.
	 */
	public function getShedule()
    {
		$this->load();
		
		return ($this->data['PROPS']['LANG_SCHEDULE_' . $this->getLang()]['VALUE']);
    }
	
	
	public function getContacts()
    {
		$this->load();
		
		return ($this->data['PROPS']['LANG_CONTACTS_' . $this->getLang()]['VALUE']);
    }
	
	
	public function getMarginDates()
	{
		$this->load();
        
        $dates = array();
        foreach ($this->data['PROPS']['MARGIN_DATES']['VALUE'] as $i => $date) {
            $dates[$date] = (float) $this->data['PROPS']['MARGIN_DATES']['DESCRIPTION'][$i];
        }
		return $dates;
	}
    
    
    public function getSteps()
    {
        $this->load();
        
        return ((array) $this->data['PROPS']['STEPS']['VALUE_XML_ID']);
    }

    
    /**
     * Получение ID местоположения.
     */
    public function getLocationID()
    {
        $this->load();
        
        return ((int) $this->data['PROPS']['LOCATION']['VALUE']);
    }
    
    
    /**
     * Получение местоположения.
     */
    public function getLocation()
    {
        $this->load();
        
        return (new Location($this->getLocationID()));
    }
    
    
    /**
     * Получение ID предустановленного стенда.
     */
    public function getPreselectStandID()
    {
        $this->load();
        
        return ((int) $this->data['PROPS']['PRESELECT']['VALUE']);
    }
    
    
    /**
     * Получение предустановленного стенда.
     */
    public function getPreselectStand($force = false)
    {
        if (!isset($this->preselect) || $force) {
            if ($this->getPreselectStandID() > 0) {
                $this->preselect = new Stand($this->getPreselectStandID());
            }
        }
        return $this->preselect;
    }
	
	
    public function getAvailableStands()
    {
        return [];
    }
	
	
	public function getStandIDs()
	{
		$this->load();
		
		return $this->data['PROPS']['STANDS']['VALUE'];
	}
    
    
    /**
	 * Получение списка возможных стендов мероприятия.
	 */
	public function getStands()
	{
		$ids = $this->getStandIDs();
        
		$stands = [];
		foreach ($ids as $id) {
			$stands[$id] = new Stand($id, [], $this->getLang());
		}
		return $stands;
	}
    
    
    public function getInvoices($code = 'VALUE')
	{
		$this->load();
		
		return $this->data['PROPS']['INVOICES'][$code];
	}
	
    
    public function hasVAT()
    {
        $this->load();
        
        return ($this->data['PROPS']['INCLUDE_VAT']['VALUE'] == 'Y');
    }
    
	
	/**
	 * Получение списка ID менеджеров мероприятия.
	 */
	public function getManagerIDs()
	{
		$this->load();
		
		return $this->data['PROPS']['MANAGERS']['VALUE'];
	}
	
	
	/**
	 * Получение списка ID услуг и оборудования мероприятия.
	 */
	public function getOwnServiceIDs()
	{
		$this->load();
		
		return $this->data['PROPS']['OPTIONS']['VALUE'];
	}
    
    
    /**
	 * Получение списка ID услуг и оборудования мероприятия.
	 */
	public function getProductIDs()
	{
		$this->load();
		// TODO: PRODUCTS.
		return array_map('intval', $this->data['PROPS']['OPTIONS']['VALUE']);
	}
	
	
	/**
	 * Получение списка стендовых предложений в зависимости от размеров.
	 */
	public function getStandOffers($width, $depth, $isobject = false)
	{
		$width = (float) $width;
		$depth = (float) $depth;
        
        // Площадь стенда.
		$area = $width * $depth;
		
		$offers = StandOffer::getList([
			'order'  => ['PROPERTY_AREA_MAX' => 'DESC'],
			'filter' => [
				'ACTIVE'              => 'Y',
				'PROPERTY_CML2_LINK'  => $this->getStandIDs(),
				'<=PROPERTY_AREA_MIN' => $area,
				'>=PROPERTY_AREA_MAX' => $area,
			]
		]);
        
        if ($isobject) {
            return $offers;
        }
        
		$items = [];
		foreach ($offers as $offer) {
            $item = [
                'MIN'   => $offer->getAreaMin(),
                'MAX'   => $offer->getAreaMax(),
                'STAND' => $offer->getStand()->getTitle(),
            ];
			$items []= $item;
		}
		return $items;
	}
    
    
    /**
     * Получение списка оборудования и услуг с ценами.
     */
    public function getProducts()
    {
        $ids = $this->getProductIDs();
        
        if (empty($ids)) {
            return [];
        }
        
        // Цены для мероприятия.
        $prices = $this->getProductPrices();
        
        // Установка ценовой политики мероприятия.
        $products = Product::getList([
            'filter' => ['ID' => $ids, 'ACTIVE' => 'Y']
        ]);
        
		foreach ($products as &$product) {
			$product->setPrice($prices[$product->getID()]);
		}
		return $products;
    }
	
	
	/**
	 * Получение списка сервисов мероприятия.
	 */
	public function getServices()
	{
		$services = Service::getList(['filter' => ['ID' => $this->getOwnServiceIDs(), 'ACTIVE' => 'Y']]);
		$prices   = $this->getEquipmentsPrices();
		
		// Установка ценовой политики мероприятия.
		foreach ($services as &$service) {
			$service->setPrice($prices[$service->getID()]);
		}
		return $services;
	}
	
	
	/**
	 * Получение списка цен на стенды мероприятия.
	 */
	public function getStandPrices()
    {
		if (empty($this->prices['stands'])) {
			$prices = \Wolk\OEM\EventStandPricesTable::getList([
				'filter' =>
					[
						'EVENT_ID' => $this->getID(),
						'SITE_ID'  => $this->getLang()
					]
			])->fetchAll();

			foreach ($prices as $price) {
				$this->prices['stands'][$price['STAND_ID']] = (float) $price['PRICE'];
			}
		}
		return $this->prices['stands'];
    }

    
    public function getProductPrices()
    {
        return $this->getEquipmentsPrices();
    }
    
	
	/**
	 * Получение списка цен на обррудование и услуги мероприятия.
	 */
    public function getEquipmentsPrices()
    {
		if (empty($this->prices['equipments'])) {
			$prices = \Wolk\OEM\EventEquipmentPricesTable::getList([
				'filter' =>
					[
						'EVENT_ID' => $this->getID(),
						'SITE_ID'  => $this->getLang()
					]
			])->fetchAll();

			foreach ($prices as $price) {
				$this->prices['equipments'][$price['EQUIPMENT_ID']] = (float) $price['PRICE'];
			}
		}
		return $this->prices['equipments'];
    }
	
	
	/*
	public function getServices()
	{
		$sections = [];

		$services = $this->getEventServices($event);


		$this->arResult['EVENT']['ALL_SERVICES'] += $services;


		$arServices = [];
		foreach ($services as $arService) {
			$arServices[$arService['IBLOCK_SECTION_ID']][$arService['ID']] = $arService;
		}

		$servicesSections = \Bitrix\Iblock\SectionTable::getList([
			'order' => ['SORT' => 'ASC', 'ID' => 'ASC'],
			'filter' =>
				[
					'ID' => array_keys($arServices)
				]
		])->fetchAll();

		$filter = [
			['LOGIC' => 'OR'],
			[
				'ID' => array_keys($arServices)
			]
		];

		foreach ($servicesSections as $serviceSection) {
			$filter[] = [
				'<LEFT_MARGIN'  => $serviceSection['LEFT_MARGIN'],
				'>RIGHT_MARGIN' => $serviceSection['RIGHT_MARGIN'],
			];
		}

		$obSections = CIBlockSection::GetTreeList([
				'IBLOCK_ID' => OPTIONS_IBLOCK_ID,
				'ACTIVE'    => 'Y',
			] + $filter, [
			'ID',
			'NAME',
			'CODE',
			'SORT',
			'DEPTH_LEVEL',
			'IBLOCK_SECTION_ID',
			'UF_SUBTITLE_' . $this->curLang,
			'UF_NAME_' . $this->curLang,
			'UF_SORT'
		]);

		while ($arSection = $obSections->Fetch()) {
			$arSection['NAME'] = $arSection['UF_NAME_' . $this->curLang] ?: $arSection['NAME'];
			$arSection['SUBTITLE'] = $arSection['UF_SUBTITLE_' . $this->curLang] ?: $arSection['UF_SUBTITLE'];
			
			if ($arSection['IBLOCK_SECTION_ID']) {
				if (array_key_exists($arSection['ID'], $arServices)) {
					$arSection['ITEMS'] = $arServices[$arSection['ID']];
				}
				$sections[$arSection['IBLOCK_SECTION_ID']]['SECTIONS'][$arSection['ID']] = $arSection;
				$sections[$arSection['ID']] = &$sections[$arSection['IBLOCK_SECTION_ID']]['SECTIONS'][$arSection['ID']];
			} else {
				$arSections[$arSection['ID']] = $arSection;
				$sections[$arSection['ID']] = &$arSections[$arSection['ID']];
			}
		}
		
		foreach ($arSections as &$section) {
			foreach ($section['SECTIONS'] as &$subsection) {
				if (isset($subsection['SECTIONS'])) {
					uasort($subsection['SECTIONS'], function ($x1, $x2) { return ($x1['SORT'] - $x2['SORT']); } );
				}
			}
			uasort($section['SECTIONS'], function ($x1, $x2) { return ($x1['SORT'] - $x2['SORT']); } );
		}

        return $arSections;
	}
	*/
	
	
	/**
	 * Получение ID предвыбранного стенда.
	 */
	public function getPreselectedStandID()
	{
		$this->load();
		
		return $this->data['PROPS']['PRESELECT']['VALUE'];
	}
	
	
	/**
	 * Получение предвыбранного стенда.
	 */
	public function getPreselectedStand()
	{
		return (new Stand($this->getPreselectedStandID()));
	}
	
	
	
	/**
	 * Получение наценки на мероприятие по дате.
	 */
	public function getSurcharge($time = null)
    {
        if (is_null($time)) {
            $time = time();
        }
		$dates = $this->getMarginDates()['DATES'];
		$notes = $this->getMarginDates()['NOTES'];
	
        if (!empty($dates) && is_array($dates)) {
            $index = null;
            foreach ($dates as $i => $date) {
                if (strtotime($date) <= $time && (is_null($index) || strtotime($date) > $dates[$index])) {
                    $index = $i;
                }
            }
            return $notes[$index];
        }
        return 0;
    }
}
