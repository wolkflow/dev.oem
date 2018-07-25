<?php

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Sale\Internals\BasketPropertyTable;
use Bitrix\Sale\Internals\BasketTable;
use Bitrix\Sale\Internals\OrderPropsValueTable;
use Bitrix\Sale\Internals\OrderTable;
use Wolk\Core\Helpers\ArrayHelper;

use Wolk\OEM\Event;
use Wolk\OEM\Stand;
use Wolk\OEM\Context;
use Wolk\OEM\Place;
use Wolk\OEM\MapObject;
use Wolk\OEM\Order;
use Wolk\OEM\Basket;
use Wolk\OEM\BasketItem;
use Wolk\OEM\Products\Param as SectionParam;


/**
 * Class WizardComponent
 */
class WizardComponent extends \CBitrixComponent
{
    const SESSCODE = 'OEMEVENTS';
    
    const DEFAULT_STAND_FORM = 'row';
	
	const SKETCH_SIDE_LENGTH = 5;
    
    protected $context = null;
    protected $basket  = null;
    
    
    /** 
	 * Установка настроек.
	 */
    public function onPrepareComponentParams($arParams)
    {
        // Код мероприятия.
        $arParams['CODE'] = (string) $arParams['CODE'];
        
        // Текущий шаг конструктора мероприятия.
        $arParams['STEP'] = (int) $arParams['STEP'];
        
        // Язык.
        $arParams['LANG'] = (string) $arParams['LANG'];
        
		// ID мероприятия.
		$arParams['EID'] = (int) \Wolk\Core\Helpers\IBlockElement::getIDByCode(IBLOCK_EVENTS_ID, $arParams['CODE']);
		
        // ID заказа.
        $arParams['OID'] = (int) $arParams['OID'];
		
        // Объект корзины.
        $this->basket = new Basket($arParams['CODE']);
		
		
		
		// Загрузка данных.
		if (!empty($arParams['OID'])) {
			if ($this->getBasket()->getOrderID() != $arParams['OID']) {
				$event = new Wolk\OEM\Event($arParams['EID']);
				$order = new Wolk\OEM\Order($arParams['OID']);
				if ($order->getUserID() != CUser::getID() || !$order->canEdit()) {
					LocalRedirect($event->getLink());
				}
				$this->getBasket()->load($order);
			}
			// Добавление параметра для корзины.
			$_SESSION[self::SESSCODE][strtoupper($arParams['CODE'])]['BASKET']['EVENT'] = $arParams['CODE'];
		} else {
			// На первом шаге сохраняем параметра стенда в сессию.
			if (empty($arParams['STEP'])) {
				$_SESSION[self::SESSCODE][strtoupper($arParams['CODE'])] = ['BASKET' => ['EVENT' => $arParams['CODE']]];
			}
		}
        
        return $arParams;
	}
    
    
    /**
	 * Выполнение компонента.
	 */
	public function executeComponent()
    {
		if (!\Bitrix\Main\Loader::includeModule('wolk.core')) {
			return;
		}

		if (!\Bitrix\Main\Loader::includeModule('wolk.oem')) {
			return;
		}
		
		global $APPLICATION;
		
		
		// Запрос.
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
		
		
		// Контекст исполнения.
        $this->context = new Context(
			$this->getEvent()->getID(), 
			$this->getBasket()->getParam('TYPE'), 
			mb_strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage())
		);
		
		
		// Редирект на шаг при изменении заказа.
		if (!empty($this->arParams['OID'])) {
			if (in_array('types', $this->getSteps())) {
				LocalRedirect($this->getStepLink(2));
			} else {
				LocalRedirect($this->getStepLink(1));
			}
		}
		
		// Переход на первый шаг, в случае отсутствие выбора типа стенда.
		if (!in_array('types', $this->getSteps())) {
			if ($this->getStepNumber() == 0) {
				LocalRedirect($this->getStepLink(1));
			}
			$infstep = 1;
		} else {
			$infstep = 0;
		}
		
		// Проверка валидности сессии.
		if ($this->getStepNumber() > $infstep && !$request->isPost() && empty($_SESSION[self::SESSCODE][$this->getEventCode()]['BASKET']['EVENT'])) {
			LocalRedirect($this->getEventLink());
		}
		
		
		// Шаги.
		$this->arResult['STEP']  = strtolower($this->getStep());
		$this->arResult['PREV']  = strtolower($this->getPrevStep());
        $this->arResult['LINKS'] = [
            'PREV' => $this->getPrevStepLink(),
            'NEXT' => $this->getNextStepLink(),
        ];
        
        
        // Обработка данных предыдущего шага.
        if ($request->isPost()) {
            switch ($this->arResult['PREV']) {
				// Выбор типа стенда.
                case ('types'):
                    $this->processStepTypes();
                    break;
				
                // Выбор стенда.
                case ('stands'):
                    $this->processStepStands();
                    break;
                
                // Выбор оборудования.
                case ('equipments'):
                    $this->processStepEquipments();
                    break;
                
                // Выбор сервисов.
                case ('services'):
                    $this->processStepServices();
                    break;
                
                // Выбор маркетинга.
                case ('marketings'):
                    $this->processStepMarketings();
                    break;
                
                // Расстановка на скетче.
                case ('sketch'):
                    $this->processStepSketch();
                    break;
                    
                // Заказ.
                case ('order'):
                    $this->processStepOrder();
                    break;
                
                default:
                    break;
            }
        }
		
		
		// Список шагов.
		$this->arResult['STEPS'] = $this->getSteps();
		        
        
        // Выбираем шаг.
        switch ($this->arResult['STEP']) {
			// Выбор типа стенда.
            case ('types'):
                $this->doStepTypes();
                break;
			
            // Выбор стенда.
            case ('stands'):
                $this->doStepStands();
                break;
            
            // Выбор оборудования.
            case ('equipments'):
                $this->doStepEquipments();
                break;
            
            // Выбор сервисов.
            case ('services'):
                $this->doStepServices();
                break;
            
            // Выбор маркетинга.
            case ('marketings'):
                $this->doStepMarketings();
                break;
            
            // Расстановка на скетче.
            case ('sketch'):
                $this->doStepSketch();
                break;
                
            // Заказ.
            case ('order'):
                $this->doStepOrder();
                break;
            
            default:
                break;
        }
        
        // Язык.
        $this->arResult['LANG'] = $this->getContext()->getLang();
        
        // Контекст.
        $this->arResult['CONTEXT'] = $this->getContext();
        
        // Мероприятие.
        $this->arResult['EVENT'] = $this->getEvent();
        
        // Корзина.
        $this->arResult['BASKET'] = $this->getBasket();
        
        // Получение предвыбранного стенда.
        $this->usePreStand();
        
		// Получение параметров разделов.
        $this->useSectionParams();
		
        // Валюта.
        $this->arResult['CURRENCY'] = $this->getEvent()->getCurrencyContext($this->getContext());
        
		
        // Ссылки на шаги конструктора.
        $this->arResult['STEPLINKS'] = [];
        foreach ($this->arResult['STEPS'] as $n => $step) {
            $this->arResult['STEPLINKS'][$step] = $this->getStepLink($n);
        }
		
		
		// Проверка языка мероприяитя (если доступен только один язык - редирект на него).
		if (count($this->arResult['EVENT']->getLangs()) == 1) {
			$elang = reset($this->arResult['EVENT']->getLangs());
			
			if ($elang != \Bitrix\Main\Context::getCurrent()->getLanguage()) {
				LocalRedirect($APPLICATION->getCurPageParam('set_lang='.$elang, ['set_lang', '?set_lang', '&set_lang', 'CODE'], false));
			}
		}
		
		
		
        // Подключение шаблона компонента.
		$this->IncludeComponentTemplate();
		
		
		// Контент мероприятия.
		$APPLICATION->addViewContent('EVENT_LINK', $this->getEvent()->getLink());
		$APPLICATION->addViewContent('EVENT_LOGO', $this->getEvent()->getLogo());
		
		$color = $this->getEvent()->getColor();
		if (!empty($color)) {
			$APPLICATION->AddViewContent(
				'EVENT_COLOR',
				'.customizable        {background-color: '.$color.' !important;} 
				 .customizable_border {border-color: '.$color.';}
				 .customizable_instep {background: linear-gradient(to right, '.$color.', #7f7f7f)!important;}'
			);
		}

		
		return $this->arResult;
    }
    
	
	/**
     * ШАГ "Выбор типа стенда".
     */
    protected function doStepTypes()
    {
		// Документы.
		$this->arResult['DOCUMENTS'] = [];

		$lid = $this->getEvent()->getLocationID();
		
		if ($lid > 0) {
			$code = 'DOCS_' . strtoupper($this->getContext()->getLang());
			$prop = CIBlockElement::getByID($lid)->getNextElement()->getProperty($code);
			
			foreach ($prop['~VALUE'] as $i => $doc) {
				$this->arResult['DOCUMENTS'] []= [
					'ID'	=> $prop['PROPERTY_VALUE_ID'][$i],
					'TITLE' => $prop['DESCRIPTION'][$i],
					'HTML'  => $doc['TEXT']
				];
			}
		}
		
		// Объекты интерактивной карты.
		$pid = $this->getEvent()->getPlaceID();

		if ($pid > 0) {
			$this->arResult['PLACE'] = $this->getEvent()->getPlace();
			
			$this->arResult['MAPOBJECTS'] = MapObject::getList([
				'filter' => [MapObject::FIELD_PLACE => $pid]
			]);
		}
    }
	
	
	/**
     * Обработка шага "Выбор типа стенда".
     */
    protected function processStepTypes()
    {
		// Запрос.
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
		
		// Параметры стенда.
		$params = [];
		
		
		// Обраотка типа застройки.
		$type = (string) $request->get('TYPE');
        if (empty($type)) {
            $type = Context::TYPE_STANDARD;
        }
		
		// Обраотка формы стенда.
		$sform = (string) $request->get('SFORM');
        if (empty($sform)) {
            $sform = self::DEFAULT_STAND_FORM;
        }
		
		// Обработка данных по ширине стенда - округление до 0.5
		$width = (float) $request->get('WIDTH');
		if (!self::isAllowSketchSideLength($width)) {
			$width = self::roundSketchSideLength($width);
		}
		
		// Обработка данных по глубине стенда - округление до 0.5
		$depth = (float) $request->get('DEPTH');
		if (!self::isAllowSketchSideLength($depth)) {
			$depth = self::roundSketchSideLength($depth);
		}
		
		if (empty($type) || empty($width) || empty($depth)) {
			LocalRedirect($this->getEventLink());
		}
		
		// Сохранение параметров в корзину.
		$this->getBasket()->setParams([
			'TYPE'  => $type,
			'WIDTH' => $width,
			'DEPTH' => $depth,
			'SFORM' => $sform
		]);
		
		// Контекст исполнения.
        $this->context = new Context(
			$this->getEvent()->getID(), 
			$this->getBasket()->getParam('TYPE'), 
			mb_strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage())
		);
	}
	
    
    /**
     * ШАГ "Выбор стенда".
     */
    protected function doStepStands()
    {
		$width = $this->getBasket()->getParam('WIDTH');
		$depth = $this->getBasket()->getParam('DEPTH');
		
        // Площадь стенда.
        $this->arResult['AREA'] = $width * $depth;
        
        // Получение предвыбранного стенда.
        $this->usePreStand();
        
        // Список стендов.
        $this->arResult['STANDS'] = $this->getEvent()->getStandsList($width, $depth, $this->getContext());
    }
    
    
    /**
     * Обработка шага "Выбор стенда".
     */
    protected function processStepStands()
    {
        // Запрос.
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        
        // Получение данных из сессии.
        $data = $this->getSession();
		
		// Данные о стенде и форме стенда.
        $data['STAND'] = intval($request->get('STAND'));
		$data['SFORM'] = strval($request->get('SFORM'));
		
		if (!empty($data['SFORM'])) {
			$this->getBasket()->setParam('SFORM', $data['SFORM']);
		}
		
		// Параметры стенда.
		$width = $this->getBasket()->getParam('WIDTH');
		$depth = $this->getBasket()->getParam('DEPTH');
		$sform = $this->getBasket()->getParam('SFORM');
		
		
        // Если выбран предустановленный станд.
        if (!empty($data['STAND'])) {
			$stand = new Stand($data['STAND']);
            
			if ($stand->exists()) {
				
				// Сохранение данных в корзину.
				$this->getBasket()->put(
					$stand->getID(),
					($width * $depth),
					Basket::KIND_STAND,
					[
						'width' => $width, 
						'depth' => $depth,
						'sform' => $sform
					],
					$this->getContext()
				);
				
				
				// Добавление включенных товаров и услуг в выбранный стенд.
				$standoffer = $stand->getStandOffer($width, $depth, $this->getContext());
				
				if (!empty($standoffer)) {
					$data['BASE'] = $standoffer->getBaseProductQIDs();
					
					$basket = $this->getBasket();
					foreach ($data['BASE'] as $pid => $quantity) {
						$basket->put(
							$pid,
							$quantity,
							\Wolk\OEM\Basket::KIND_PRODUCT,
							[],
							[],
							true
						);
					}
					$this->putSessionParam('BASE', $data['BASE']);
				}
			}
        }
		
		// Уточнение формы стенда для индивидуальной застройки.
		// Если добавилась форма стенда - надо перезагрузить страницу с другим набором шагов.
		if (!empty($data['SFORM'])) {
			LocalRedirect($this->getStepLink());
		}
    }


    /**
     *  Шаг наполнения продукцией.
     */
    protected function doStep($code)
    {
        $data  = $this->getSession();
        $event = $this->getEvent();

        // Спсиок оборудования.
        $products = $event->getProducts($this->getContext(), $code);
        $sections = [];
        foreach ($products as &$product) {
            if (!array_key_exists($product->getSectionID(), $sections)) {
                $sections[$product->getSectionID()] = $product->getSection();
            }
            $section = $sections[$product->getSectionID()];
            $section->load();
            $section->addInside($product, $product->getID());
        }

        // Список разделов.
        $parents = [];
        foreach ($sections as &$section) {
            if (!array_key_exists($section->getSectionID(), $parents)) {
                $parents[$section->getSectionID()] = $section->getSection();
            }
            $parent = $parents[$section->getSectionID()];
            $parent->load();
            $parent->addInside($section, $section->getID());
        }

        uasort($parents, function($x1, $x2) {
            return ($x1->get('SORT') - $x2->get('SORT'));
        });

        // Группы и продукция.
        $this->arResult['ITEMS'] = $parents;

        // Стенд.
        $this->arResult['BASE'] = $data['BASE'];

        // Цвета.
        $this->arResult['COLORS'] = self::getColors();
    }

    
    /**
     * ШАГ "Выбор оборудования".
     */
    protected function doStepEquipments()
    {
        $this->doStep(Wolk\OEM\Products\Section::TYPE_EQUIPMENTS);
    }
    
    
    /**
     * Обработка шага "Выбор оборудования".
     */
    protected function processStepEquipments()
    {
        
    }


    /**
     * ШАГ "Выбор услуг".
     */
    protected function doStepServices()
    {
        $this->doStep(Wolk\OEM\Products\Section::TYPE_SERVICES);
    }


    /**
     * Обработка шага "Выбор услуг".
     */
    protected function processStepServices()
    {

    }


    /**
     * ШАГ "Выбор маркетинга".
     */
    protected function doStepMarketings()
    {
        $this->doStep(Wolk\OEM\Products\Section::TYPE_MARKETINGS);
    }


    /**
     * Обработка шага "Выбор маркетинга".
     */
    protected function processStepMarketings()
    {

    }


    /**
     * ШАГ "Скетч".
     */
    protected function doStepSketch()
    {
        $baskets = $this->getBasket()->getList(true);
        $objects = [];
		
        foreach ($baskets as $basket) {
            $element = $basket->getElement();
			
			// Проверка типа "Надпись на фризовую панель".
			if ($basket->getType() == \Wolk\OEM\Products\Base::SPECIAL_TYPE_FASCIA) {
				$this->arResult['FASCIA'] []= $basket;
			}

            if (empty($element)) {
                continue;
            }

            if (!$element->isSketchShow()) {
                continue;
            }
			
			if (!is_file($_SERVER['DOCUMENT_ROOT'] . $element->getSketchImagePrepared())) {
                continue;
            }
			
            $object = [
                'id'        => $basket->getID(),
                'quantity'  => $basket->getQuantity(),
                'pid'       => $element->getID(),
                'title'     => $element->getTitle(),
                'type'      => $element->getSketchType(),
                'w'         => $element->getSketchWidth() / 1000,
                'h'         => $element->getSketchHeight() / 1000,
                'imagePath' => $element->getSketchImagePrepared(),
                'path'      => $element->getModelPath(),
            ];
            $objects[$basket->getID()] = $object;
        }
		
        
        // Стенд.
        $this->arResult['EVENT'] = $this->getEvent();
        
        // Объекты для скетча.
        $this->arResult['OBJECTS'] = $objects;
		
		
        // Размещенные обекты.
        $this->arResult['PLACED'] = json_decode($this->getBasket()->getSketch()['SKETCH_SCENE'], true)['objects'];
        
        // Стенд.
        if (!is_null($this->getBasket()->getStand())) {
            $this->arResult['STAND'] = new \Wolk\OEM\Stand($this->getBasket()->getStand()->getProductID());
        }
        
        // Параметры стенда.
        $params = $this->getBasket()->getParams();
        
        $this->arResult['WIDTH'] = $params['WIDTH'];
        $this->arResult['DEPTH'] = $params['DEPTH'];
        $this->arResult['SFORM'] = $params['SFORM'];
        
        // Комментарий к заказу.
        $this->arResult['COMMENTS'] = $this->getBasket()->getParam('COMMENTS');
    }
    
    
    /**
     * Обработка шага "Скетч".
     */
    protected function processStepSketch()
    {
        // Запрос.
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        
        $scene    = (string) $request->get('SKETCH_SCENE');
        $image    = (string) $request->get('SKETCH_IMAGE');
        $comments = (string) $request->get('COMMENTS');
        
        $this->getBasket()->setSketch([
            'SKETCH_SCENE' => $scene,
            'SKETCH_IMAGE' => $image
        ]);
        $this->getBasket()->setParam('COMMENTS', $comments);
    }
    
    
    /**
     * ШАГ "Заказ".
     */
    protected function doStepOrder()
    {
        $oembasket = $this->getBasket();
        $baskets   = $oembasket->getList(true);
        
		// Проверка на заполненность скетча.
		if (in_array('sketch', $this->getSteps())) {
			$objects = 0;
			foreach ($baskets as $basket) {
				$element = $basket->getElement();

				if (empty($element)) {
					continue;
				}

				if (!$element->isSketchShow()) {
					continue;
				}
				
				if (!is_file($_SERVER['DOCUMENT_ROOT'] . $element->getSketchImagePrepared())) {
					continue;
				}
				
				$objects += (int) $basket->getQuantity();
			}
			
			// Размещенные обекты.
			$placed = count((array) json_decode($this->getBasket()->getSketch()['SKETCH_SCENE'], true)['objects']);
			
			if ($placed < $objects) {
				$this->arResult['SKETCHERROR'] = true;
			}
		}
		
		$this->arResult['PRODUCTS'] = ['EQUIPMENTS' => [], 'SERVICES' => [], 'MARKETINGS' => []];
	
        $price = 0;
        foreach ($baskets as $basket) {
            $elem = $basket->getElement();
            
            if (empty($elem)) {
                continue;
            }
            
            // Входит в стоимость стнеда.
            if ($basket->isIncluded()) {
                continue;
            }
            
            $basket->setPrice($elem->getContextPrice($this->getContext()));
            
            // Общая стоимость продукции.
            $price += $basket->getCost();
            
            $this->arResult['PRODUCTS'][$elem->getSectionType()][$basket->getID()] = ['ITEM' => $elem, 'BASKET' => $basket];
        }
        
        // Стенд.
		$stand = $oembasket->getStand();
        if (!empty($stand)) {
            $stand->loadPrice($this->getContext());
            $this->arResult['STAND'] = ['ITEM' => $stand->getElement(), 'BASKET' => $stand];
            
            // Стоимость стенда.
            $price += $stand->getCost();
        }
        
        
		// Данные по стенду.
		$this->arResult['STANDNUM'] = $oembasket->getParam('STANDNUM');
		$this->arResult['PAVILION'] = $oembasket->getParam('PAVILION');
		
		
        // Мероприятие.
        $event = $this->getEvent();
        
        // Цены.
        $this->arResult['PRICES'] = \Wolk\OEM\Order::getFullPriceInfo($price, $event->getSurcharge(), $event->hasVAT());
    }
    
    
    /**
     * Обработка шага "Заказ".
     */
    protected function processStepOrder()
    {
    
    }
    
    
    
    
    /**
     * Получение контекста.
     */
    protected function getContext()
    {
        return $this->context;
    }


	/**
     * Получение ссылки на мероприятие.
     */
    protected function getEventLink()
    {
        return $this->getEvent()->getLink();
    }
    
    
    /**
     * Получение кода мероприятия.
     */
    protected function getEventCode()
    {
        return (mb_strtoupper($this->getEvent()->getCode()));
    }
    
    
    /**
     * Получение обхекта мероприятия.
     */
    protected function getEvent()
    {
        return (new Event($this->arParams['EID']));
    }
    
    
    /**
     * Получение корзины.
     */
    protected function getBasket()
    {
        return $this->basket;
    }
    
    
    /**
     * Получение предвыбранного стенда.
     */
    protected function usePreStand()
    {
		$width = $this->getBasket()->getParam('WIDTH');
		$depth = $this->getBasket()->getParam('DEPTH');
		
        $this->arResult['PRESTAND'] = $this->getEvent()->getPreselectStand();
        $this->arResult['PREOFFER'] = null;
        if (!empty($this->arResult['PRESTAND'])) {
            $this->arResult['PREOFFER'] = $this->arResult['PRESTAND']->getStandOffer($width, $depth, $this->getContext());
        }
    }
	
	
	/**
     * Получение параметров разделов.
     */
	protected function useSectionParams()
	{
		$result = SectionParam::getList([
			'filter' => [
					SectionParam::FIELD_EVENT => $this->getEvent()->getID(),
					SectionParam::FIELD_LANG  => $this->getContext()->getLang(),
				]
			],
			false
		);
		
		$params = array();
		while ($item = $result->fetch()) {
			$item['PROPS'] = json_decode($item['UF_PROPS'], true);
			$item['NAMES'] = json_decode($item['UF_NAMES'], true);
			
			$params
				[$item[SectionParam::FIELD_SECTION]] 
			= $item;
		}
		
		$this->arResult['SECTION_PARAMS'] = $params;
	}
    
    
    /**
     * Получение номера текущего шага.
     */
    protected function getStepNumber()
    {
        return (int) $this->arParams['STEP'];
    }
    
    
    /**
     * Получение текущего шага.
     */
    protected function getStep()
    {
        $steps = $this->getSteps();
        
        return $steps[$this->getStepNumber()];
    }
    
    
    /**
     * Получение номера следующего шага.
     */
    protected function getPrevStepNumber()
    {
		$steps = $this->getSteps();
        $step  = $this->getStepNumber() - 1;
		
		$infinum = (in_array('types', $steps)) ? (0) : (1);
		
		if ($step < $infinum) {
			$step = $infinum;
		}
        return $step;
    }
    
    
    /**
     * Получение следующего шага.
     */
    protected function getPrevStep()
    {
        $steps = $this->getSteps();
        
        return $steps[$this->getPrevStepNumber()];
    }
    
    
    /**
     * Получение номера следующего шага.
     */
    protected function getNextStepNumber()
    {
        $count = count($this->getSteps());
        $step  = $this->getStepNumber() + 1;
        
        if ($step > $count) {
            $step = $count;
        }
        return $step;
    }
    
    
    /**
     * Получение следующего шага.
     */
    protected function getNextStep()
    {
        $steps = $this->getSteps();
        
        return $steps[$this->getNextStepNumber()];
    }
    
    
    
    
    
    /**
     * Получение ссылки шага.
     */
    public function getStepLink($step = null)
    {
        if (is_null($step)) {
            $step = $this->getStepNumber();
        }
        
		/*
        $fields = [
            $this->arParams['CODE'],
            mb_strtolower($this->arParams['TYPE']),
            $step,
            $this->arParams['WIDTH'].'x'.$this->arParams['DEPTH'],
			$fields []= $this->arParams['SFORM']
        ];
        
		
        // if ($this->getContext()->getType() != Context::TYPE_INDIVIDUAL) {
            // $fields []= $this->arParams['SFORM'];
        // }
		
		if ($this->getBasket()->getOrderID() > 0) {
			$fields []= $this->getBasket()->getOrderID();
		}
        $fields = array_filter($fields);
		*/
		
		$fields = [$this->arParams['CODE'], $step];
		
        $link = '/events/' . implode('/', $fields) . '/';
        
        return $link;
    }
    
	
	/**
     * Получение ссылки предыдущего шага.
     */
    public function getPrevStepLink()
    {
        return $this->getStepLink($this->getPrevStepNumber());
    }
	
    
    /**
     * Получение ссылки следующего шага.
     */
    public function getNextStepLink()
    {
        return $this->getStepLink($this->getNextStepNumber());
    }
    
    
    /**
     * Получение списка шагов шагов.
     */
    protected function getSteps()
    {
		// Выбранные шаги мероприяитя.
		$eventsteps = $this->getEvent()->getSteps();
		
		// Наличие стенда в корзине.
		$stand = $this->getBasket()->getStand();
		
		// Стенды (если включены)
		// Оборудование (если включено и если выбран стенд)
		// Услуги (если ключено)
		// Маркетинг (если включено)
		// Скетч (только при наличии стенде)
		// Заказ (всегда)
		
		$index = 0;
		$steps = [];
		
		// Добавление шага выбора типа стенда.
		if (in_array('STANDS', $eventsteps)) {
			$steps[$index++] = 'TYPES';
			$steps[$index++] = 'STANDS';
		} else {
			$index = 1;
		}
		
		// Учет шага выбора оборудования.
		if (in_array('EQUIPMENTS', $eventsteps)) {
			if ($this->getContext()->getType() == Context::TYPE_STANDARD) {
				$steps[$index++] = 'EQUIPMENTS';
			} else {
				if (!empty($stand)) {
					$steps[$index++] = 'EQUIPMENTS';
				}
			}
		}
		
		// Учет шага выбора услуг.
		if (in_array('SERVICES', $eventsteps)) {
			$steps[$index++] = 'SERVICES';
		}
		
		// Учет шага выбора маркетинга.
		if (in_array('MARKETINGS', $eventsteps)) {
			$steps[$index++] = 'MARKETINGS';
		}
		
		
		// Добавление шага расстановки оборудования.
		if ((in_array('STANDS', $eventsteps) && in_array('EQUIPMENTS', $eventsteps) && $this->getContext()->getType() == Context::TYPE_STANDARD) || !empty($stand)) {
			$steps[$index++] = 'SKETCH';
		}
		
		// Добавление шага заказа.
		$steps[$index++] = 'ORDER';
		
		
		$steps = array_map('strtolower', $steps);
		
        return $steps;
    }
    
    
    /**
     * Полчение кода мероприятия для сессии.
     */
    protected function getSessionEventCode()
    {
        return (mb_strtoupper($this->getEvent()->getCode()));
    }
    
	
    
    /**
     * Получение массива данных из сессии.
     */
    protected function getSession()
    {
        return $_SESSION[self::SESSCODE][$this->getSessionEventCode()];
    }
    
    
    /**
     * Сохранение массива данных в сессию.
     */
    protected function putSession($data)
    {
        $_SESSION[self::SESSCODE][$this->getSessionEventCode()] = $data;
    }
    
    
    /**
     * Получение данных из сессии.
     */
    protected function getSessionParam($param)
    {
        return $_SESSION[self::SESSCODE][$this->getSessionEventCode()][$param];
    }
    
    
    /**
     * Сохранение данных в сессию.
     */
    protected function putSessionParam($param, $value)
    {
        $_SESSION[self::SESSCODE][$this->getSessionEventCode()][$param] = $value;
    }
    
    
    
    
    /**
     * Выбор цветов.
     */
    protected static function getColors()
    {
        $result = \Wolk\OEM\Dicts\Color::getList(['order' => ['UF_SORT' => 'ASC', 'UF_NUM' => 'ASC']], false);
        $colors = array();
        while ($color = $result->fetch()) {
            $colors[$color['ID']] = $color;
        }
        return $colors;
    }
	
	
	
	/**
	 * Проверка указания длины стороны скетча.
	 */
	public static function isAllowSketchSideLength($length)
	{
		return ((floatval($length) * 10) % self::SKETCH_SIDE_LENGTH == 0);
	}
	
	
	/**
	 * Округление размера скетча.
	 */
	public static function roundSketchSideLength($length)
	{
		return (ceil(floatval($length) / (self::SKETCH_SIDE_LENGTH / 10)) * (self::SKETCH_SIDE_LENGTH / 10));
	}

}

