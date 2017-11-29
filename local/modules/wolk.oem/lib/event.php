<?php

namespace Wolk\OEM;

use \Wolk\OEM\Products\Base as Product;
use Wolk\OEM\Prices\Stand   as StandPrice;
use Wolk\OEM\Prices\Product as ProductPrice;
use Wolk\OEM\Products\Param as SectionParam;

/**
 * Класс реализающий объект "Мероприятие" (выставка).
 */
class Event extends \Wolk\Core\System\IBlockEntity
{
    const IBLOCK_ID = IBLOCK_EVENTS_ID;
    
	const LINK_PREFIX     = '/events/';
	const LOGO_DEFAULT    = '/local/templates/.default/build/images/header-logo.png';
	
	const STEP_STANDS     = 'STANDS';
    const STEP_EQUIPMENTS = 'EQUIPMENTS';
    const STEP_SERVICES   = 'SERVICES';
    const STEP_MARKETINGS = 'MARKETINGS';

	protected $id      = null;
	protected $data    = [];
	protected $prices  = ['stands' => [], 'equipments' => [], 'services' => [], 'marketings' => []];
	
	protected $logo;
	protected $color;
    protected $preselect;
	
    
	
    public function __construct($id = null, $data = [])
    {
		parent::__construct($id, $data);
    }
	
    
    /**
     * Получение кода мероприятия.
     */
    public function getCode()
	{
		return $this->get('CODE');
	}
	
	
	
	/**
     * Получение даты начала мероприятия.
     */
    public function getDateBegin()
	{
		$this->load();
		
		return $this->data['PROPS']['DATE_BEGIN']['VALUE'];
	}
    
	
	/**
     * Получение даты окончания мероприятия.
     */
    public function getDateFinish()
	{
		$this->load();
		
		return $this->data['PROPS']['DATE_FINISH']['VALUE'];
	}
	
	
	/**
	 * Локализованное название мероприятия.
	 */
    public function getTitle($lang = null)
    {
		$this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
		return ($this->data['PROPS']['LANG_TITLE_' . $lang]['VALUE']);
    }
	
	
	/**
	 * Локализованный заголовок мероприятия.
	 */
	public function getHeader($lang = null)
	{
		$this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
		return ($this->data['PROPS']['LANG_HEADER_' . $lang]['VALUE']);
	}
	
	
	/**
	 * Локализованный подзаголовок мероприятия.
	 */
	public function getSubHeader($lang = null)
	{
		$this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
		return ($this->data['PROPS']['LANG_SUBHEADER_' . $lang]['~VALUE']['TEXT']);
	}
	
	
	/**
	 * Получение расписания мероприятия.
	 */
	public function getSchedule($lang = null)
    {
		$this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
        $schedule = $this->data['PROPS']['LANG_SCHEDULE_' . $lang]['~VALUE']['TEXT'];
         
        if ($this->data['PROPS']['LANG_SCHEDULE_' . $lang]['~VALUE']['TYPE'] == 'text') {
            $schedule = nl2br($schedule);
        }
        return $schedule;
    }
	
	
	/**
	 * Получение списка контактов.
	 */
	public function getContacts($lang = null)
    {
		$this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
		return ($this->data['PROPS']['LANG_CONTACTS_' . $lang]['VALUE']);
    }
	
    
    /**
     * Полуение электронных почт для отправки копий счета.
     */
    public function getEmails()
    {
		return ($this->data['PROPS']['EMAILS']['VALUE']);
    }
    
	
    /**
     * Получение дат для наценок.
     */
	public function getMarginDates()
	{
		$this->load();
        
        $dates = [];
        foreach ($this->data['PROPS']['MARGIN_DATES']['VALUE'] as $i => $date) {
            $dates[$date] = (float) $this->data['PROPS']['MARGIN_DATES']['DESCRIPTION'][$i];
        }
		return $dates;
	}
    
    
    /**
     * Получение списка шагов конструктора мероприятия.
     */
    public function getSteps(Context $context = null)
    {
        $this->load();
        
        $steps = (array) $this->data['PROPS']['STEPS']['VALUE_XML_ID'];
        $steps = array_combine($steps, $steps);
        $steps = array_values($steps);
        
        return $steps;
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


    public function getPlaceTitle($lang = null)
    {
        $this->load();

        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);

        return ($this->data['PROPS']['LANG_LOCATION_' . $lang]['VALUE']);
    }
    
    
    /**
     * Показывать внешнюю ссылку.
     */
    public function showExternalLink()
    {
        $this->load();
        
        return ($this->data['PROPS']['SHOW_EXTERNAL_LINK']['VALUE'] == 'Y');
    }
        
    
    /**
     * Получить внешнюю ссылку.
     */
    public function getExternalLink($lang = null)
    {
        $this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
        
        return ($this->data['PROPS']['LANG_EXTERNAL_LINK_' . $lang]['VALUE']);
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
	
	
	public function getStandIDs()
	{
		$this->load();
		
		return $this->data['PROPS']['STANDS']['VALUE'];
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
	 * Получение списка возможных стендов мероприятия.
	 */
	public function getStands()
	{
		$ids    = $this->getStandIDs();
		$stands = [];
		foreach ($ids as $id) {
			$stands[$id] = new Stand($id);
		}
		return $stands;
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
	 * Получение количества символов, после которых символ становится платным.
	 */
	public function getPayLimitSymbols()
	{
		$this->load();
		
		return intval($this->data['PROPS']['PAY_LIMIT_SYMBOLS_PANEL']['VALUE']);
	}
	
	
	/**
	 * Получение ссылки на выставку.
	 */
	public function getLink()
	{
		return (self::LINK_PREFIX . mb_strtolower($this->getCode()) . '/');
	}
	
	
	/**
	 * Получение логотипа выставки.
	 */
	public function getLogo($lang = null)
	{
		if (empty($this->logo)) {
			$this->load();
			
			if (empty($lang)) {
				$lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
			}
			$lang = mb_strtoupper($lang);
			$logo = $this->data['PROPS']['LANG_LOGO_' . $lang]['VALUE'];
			
			if (!empty($logo)) {
				$this->logo = \CFile::ResizeImageGet($logo, ['width' => 220, 'height' => 65])['src'];
			} else {
				$this->logo = self::LOGO_DEFAULT;
			}
		}
		return $this->logo;
	}
	
	
	/**
	 * Получение цветовой гаммы выставки.
	 */
	public function getColor()
	{
		$this->load();
		
		return $this->data['PROPS']['COLOR']['VALUE'];
	}
	
    
    
	/**
	 * Получение валюты.
	 */
    public function getCurrencyContext(Context $context)
    {
        $this->load();
        
        $lang = $context->getLang();
        
        return ((string) $this->data['PROPS']['LANG_CURRENCY_' . strtoupper($lang)]['VALUE']);
    }
	
	
	public function getCurrencyStandsContext(Context $context)
	{
		return $this->getCurrencyContext($context);
	}
	
	
	public function getCurrencyProductsContext(Context $context)
	{
		return $this->getCurrencyContext($context);
	}
	
	
	public function getCurrencyStandard($lang = LANG_EN_UP)
    {
        $this->load();
        
        return (string) $this->data['PROPS']['LANG_CURRENCY_' . mb_strtoupper($lang)]['VALUE'];
    }
    
    
    public function getCurrencyIndividual($lang = LANG_EN_UP)
    {
        $this->load();
        
        return (string) $this->data['PROPS']['LANG_CURRENCY_' . mb_strtoupper($lang)]['VALUE'];
    }
    
	
    public function getCurrencyStandsStandard($lang = LANG_EN_UP)
    {
        return $this->getCurrencyStandard($lang);
    }
    
    
    public function getCurrencyStandsIndividual($lang = LANG_EN_UP)
    {
        return $this->getCurrencyIndividual($lang);
    }
    
    
    public function getCurrencyProductsStandard($lang = LANG_EN_UP)
    {
        return $this->getCurrencyStandard($lang);
    }
    
    
    public function getCurrencyProductsIndividual($lang = LANG_EN_UP)
    {
        return $this->getCurrencyIndividual($lang);
    }
	
    
    
	/**
	 * Получение списка ID услуг и оборудования мероприятия.
	 * @deprecated
	 */
	public function getOwnServiceIDs()
	{
		$this->load();
		
		return $this->data['PROPS']['OPTIONS']['VALUE'];
	}
    
    
    /**
	 * Получение списка ID услуг и оборудования мероприятия.
	 * Отдает либо продукцию стандартной, либо индивидуальной застройки.
	 * В случае, если не указан контекст, отдает всю продукцию из обоих типов застройки.
	 */
	public function getProductIDs(Context $context = null)
	{
		$this->load();
		
		if (!is_null($context)) {
			$ids = (array) $this->data['PROPS']['PRODUCTS_' . strtoupper($context->getType())]['VALUE'];
		} else {
			$ids = array_merge(
				(array) $this->data['PROPS']['PRODUCTS_STANDARD']['VALUE'],
				(array) $this->data['PROPS']['PRODUCTS_INDIVIDUAL']['VALUE']
			);
		}
		$ids = array_map('intval', array_unique($ids));
		
		return $ids;
	}
	
	
	
	/**
	 * Получение списка активных языков.
	 */
	public function getLangs()
	{
		$this->load();
		
		$langs = [];
		foreach ($this->data['PROPS'] as $code => $prop) { 
			if (strpos($code, 'LANG_ACTIVE_') !== false) {
				if ($prop['VALUE'] == 'Y') {
					$langs []= strtolower(str_replace('LANG_ACTIVE_', '', $code));
				}
			}
		}
		return $langs;
	}
	
    
    /**
	 * Получение списка стендовых предложений в зависимости от размеров.
	 */
	public function getStandsList($width, $depth, Context $context)
	{
        // Цены на стенды.
        $prices = $this->getStandPrices($context);
        
        $width = (float) $width;
		$depth = (float) $depth;
        
        // Площадь стенда.
		$area = $width * $depth;
		
        // Выбор вариантов стендов с подходящей площадью.
		$stands = StandOffer::getList([
			'order'  => ['PROPERTY_AREA_MAX' => 'DESC'],
			'filter' => [
				'ACTIVE'              => 'Y',
				'PROPERTY_CML2_LINK'  => $this->getStandIDs(),
				'<=PROPERTY_AREA_MIN' => $area,
				'>=PROPERTY_AREA_MAX' => $area,
			]
		]);
        
        // Список стендов с ценами.
        foreach ($stands as $stand) {
            $priceitem = $prices[$stand->getStandID()];
            if (!is_null($priceitem)) {
                $stand->setPrice($priceitem->getPrice());
            }
        }
        return $stands;
    }
    
    
	
	/**
	 * Получение списка стендовых предложений в зависимости от размеров.
	 */
	public function getStandOffers($width, $depth, $asobject = false)
	{
		$width = (float) $width;
		$depth = (float) $depth;
        
        // Площадь стенда.
		$area = $width * $depth;
		
        // Выбор вариантов стендов с подходящей площадью.
		$offers = StandOffer::getList([
			'order'  => ['PROPERTY_AREA_MAX' => 'DESC'],
			'filter' => [
				'ACTIVE'              => 'Y',
				'PROPERTY_CML2_LINK'  => $this->getStandIDs(),
				'<=PROPERTY_AREA_MIN' => $area,
				'>=PROPERTY_AREA_MAX' => $area,
			]
		]);
        
        if (!$asobject) {
            return $offers;
        }
        
		// Конкретные предложения по стендам с конкретной площадью.
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
    public function getProducts(Context $context = null, $section = null)
    {
        $ids = $this->getProductIDs($context);
        
        if (empty($ids)) {
            return [];
        }
        
        // Указан контекст.
		if (!is_null($context)) {
			
			// Цены продукции мероприятия.
			$prices = $this->getProductPrices($context);
			
			// Свойства продукции мероприятия.
			$params = $this->getSectionParams($context);
		}
        
        // Фильтр.
        $filter = ['ID' => $ids, 'ACTIVE' => 'Y'];
        
        if (!is_null($section)) {
            $filter['SECTION_CODE'] = (string) $section;
            $filter['INCLUDE_SUBSECTIONS'] = 'Y';
        }
        
        // Установка ценовой политики мероприятия.
        $products = Product::getList([
            'filter' => $filter
        ]);
        
		foreach ($products as &$product) {
            $priceitem = $prices[$product->getID()];
			$paramitem = $params[$product->getID()];
            if (!is_null($priceitem)) {
                $product->setPrice($priceitem->getPrice());
            }
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
	 * Получение списка цен на стенды мероприятия по типу.
	 */
	protected function getStandPrices(Context $context)
    {
        if (empty($this->prices['stands'])) {
            $this->prices['stands'] = StandPrice::getList(
                [
                    'filter' => [
                        StandPrice::FIELD_EVENT => $this->getID(),
                        StandPrice::FIELD_TYPE  => $context->getType(),
                        StandPrice::FIELD_LANG  => $context->getLang(),
                    ]
                ], 
                true,
                StandPrice::FIELD_STAND
            );
		}
		return $this->prices['stands'];
    }

    
    /**
     * Получение цен на продукцию мероприятия по типу.
     */
    public function getProductPrices(Context $context)
    {
        if (empty($this->prices['products'])) {
            $this->prices['products'] = ProductPrice::getList(
                [
                    'filter' => [
                        ProductPrice::FIELD_EVENT => $this->getID(),
                        ProductPrice::FIELD_TYPE  => $context->getType(),
                        ProductPrice::FIELD_LANG  => $context->getLang(),
                    ]
                ],
                true,
                ProductPrice::FIELD_PRODUCT
            );
		}
		return $this->prices['products'];
    }
    
	
	/**
     * Получение цен на продукцию мероприятия по типу.
     */
    public function getSectionParams(Context $context, $sid = null)
    {
		$result = SectionParam::getList([
			'filter' => [
					SectionParam::FIELD_EVENT => $this->getID(),
					SectionParam::FIELD_LANG  => $context->getLang(),
				]
			],
			false
		);
		
		$params = array();
		while ($item = $result->fetch()) {
			$item['PROPS'] = json_decode($item['UF_PROPS'], true);
			$item['NAMES'] = json_decode($item['UF_NAMES'], true);
			
			$params[$item[SectionParam::FIELD_SECTION]] = $item;
		}
		
		if (!empty($sid)) {
			$params = $params[intval($sid)];
		}
		return $params;
	}
	
	
	/**
	 * Получение количества бесплатных символов для фризовой панели.
	 */
	public function getPayLimitSymbolsPanel()
	{
		$this->load();
		
		return intval($this->data['PROPS']['PAY_LIMIT_SYMBOLS_PANEL']['VALUE']);
	}
	
	
	
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
        if (empty($time)) {
            $time = time();
        }
		$dates = array_keys($this->getMarginDates());
		$notes = array_values($this->getMarginDates());
        
        if (!empty($dates) && is_array($dates)) {
            $index = null;
            foreach ($dates as $i => $date) {
                if (strtotime($date) <= $time && (is_null($index) || strtotime($date) > strtotime($dates[$index]))) {
                    $index = $i;
                }
            }
            return $notes[$index];
        }
        return 0;
    }
	
    
    
    /**
     * Установка цен на стенды в рамках мерпориятия.
     */
    public function clearStandsPrices($type, $lang)
    {
        return StandPrice::clear($this->getID(), $type, $lang);
    }
    
    
    /**
     * Установка цен на продукцию в рамках мерпориятия.
     */
    public function clearProductsPrices($type, $lang)
    {
        return ProductPrice::clear($this->getID(), $type, $lang);
    }
	
	
	/**
     * Установка цен на продукцию в рамках мерпориятия.
     */
    public function clearSectionsParams($lang)
    {
        return SectionParam::clear($this->getID(), $lang);
    }
	
	
    
    
    /**
     * Установка цен на стенды в рамках мерпориятия.
    
    public function setStandsPrices($type, $lang, $currency, $prices)
    {
        $type     = mb_strtoupper((string) $type);
        $lang     = mb_strtoupper((string) $lang);
        $currency = mb_strtoupper((string) $currency);
        $prices   = (array) $prices;
        
        if (empty($language)) {
            throw new \Exception('Не указан язык для сохранения цен стендов');
        }
        
        if (empty($currency)) {
            throw new \Exception('Не указана валюта для сохранения цен стендов');
        }
        
        if (empty($type)) {
            throw new \Exception('Не указан тип для сохранения цен стендов');
        }
        
        if (!in_array($type, \Wolk\OEM\Prices\Stand::getTypeList())) {
            throw new \Exception('Недопустимый тип для сохранения цен стендов');
        }
        
        // Удаление цен для текущего языка и типа.
        $this->clearStandsPrices($language, $type);
        
        // Сохранение цен по стендам.
        foreach ($prices as $id => $price) {
            $standprice = new StandPrice();
            
            // Добавление цены стенду.
            $result = $standprice->add([
                StandPrice::FIELD_EVENT     => $this->getID(),
                StandPrice::FIELD_STAND     => intval($id),
                StandPrice::FIELD_PRICE     => floatval($price),
                StandPrice::FIELD_CURRENCY  => $currency,
                StandPrice::FIELD_LANG      => $language,
                StandPrice::FIELD_TYPE      => $type,
            ]);
            
            if (!$result->isSuccess()) {
                // TODO: note about fail.
            }
        }
    }
     */
}
