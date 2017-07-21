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
use Wolk\OEM\Context;
use Wolk\OEM\Basket;
use Wolk\OEM\BasketItem;

/**
 * Class WizardComponent
 */
class WizardComponent extends \CBitrixComponent
{
    const SESSCODE = 'OEMEVENTS';
    
    const DEFAULT_STAND_FORM = 'row';
    
    protected $context = null;
    protected $basket  = null;
    
    
    /** 
	 * Установка настроек.
	 */
    public function onPrepareComponentParams($arParams)
    {   
        // ID мероприятия.
        $arParams['EID']  = (int) $arParams['EID'];
        
        // Код мероприятия.
        $arParams['CODE'] = (string) $arParams['CODE'];
        
        // Текущий шаг конструктора мероприятия.
        $arParams['STEP'] = (int) $arParams['STEP'];
        
        // Тип стенда.
        $arParams['TYPE'] = (string) $arParams['TYPE'];
        
        // Язык.
        $arParams['LANG'] = (string) $arParams['LANG'];
        
        
        // Ширина стенда.
        $arParams['WIDTH'] = (float) $arParams['WIDTH'];
        
        // Глубина стенда.
        $arParams['DEPTH'] = (float) $arParams['DEPTH'];

        // Форма стенда.
        $arParams['SFORM'] = strtolower((string) $arParams['SFORM']);

        if (empty($arParams['SFORM'])) {
            $arParams['SFORM'] = self::DEFAULT_STAND_FORM;
        }
        
        
        // Контекст исполнения.
        $this->context = new Context($arParams['EID'], $arParams['TYPE'], $arParams['LANG']);
        
        // Объект корзины.
        $this->basket = new Basket($arParams['CODE']);
        
        // На первом шаге сохраняем параметра стенда в сессию.
        if ($arParams['STEP'] == 1) {
            $this->getBasket()->clear();
            
            // Очистка данных по выставке.
            $_SESSION[self::SESSCODE][$this->getBasket()->getEventCode()] = array('STAND' => array(), Basket::SESSCODE_BASKET => array());
            
            $this->getBasket()->setParams(array(
                'WIDTH' => $arParams['WIDTH'],
                'DEPTH' => $arParams['DEPTH'],
                'SFORM' => $arParams['SFORM']
            ));
        } else {
            $params = $this->getBasket()->getParams();
            
            $arParams['WIDTH'] = $params['WIDTH'];
            $arParams['DEPTH'] = $params['DEPTH'];
            $arParams['SFORM'] = $params['SFORM'];
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
        
        // Проверка валидности сессии.
        if ($this->getStepNumber() > 1 && empty($_SESSION[self::SESSCODE][$this->getEventCode()])) {
            LocalRedirect('/events/'.$this->getEventCode().'/');
        }
        
        // Запрос.
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        
        // Шаги.
        $this->arResult['STEPS']    = $this->getSteps();
        $this->arResult['STEP']     = $this->getStep();
        $this->arResult['PREV']     = $this->getPrevStep();
        $this->arResult['LINKS']    = [
            'PREV' => $this->getPrevStepLink(),
            'NEXT' => $this->getNextStepLink(),
        ];
        
        // Обработка данных предыдущего шага.
        if ($request->isPost()) {
            switch ($this->arResult['PREV']) {
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
                    break;
                
                // Выбор маркетинга.
                case ('marketings'):
                    break;
                
                // Расстановка на скетче.
                case ('sketch'):
                    break;
                    
                // Заказ.
                case ('order'):
                    break;
                
                default:
                    break;
            }
        }
        
        
        // Выбираем шаг.
        switch ($this->arResult['STEP']) {
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
        
        // Валюта.
        $this->arResult['CURRENCY'] = $this->arResult['EVENT']->getCurrencyStandsContext($this->getContext());
        

        // Подключение шаблона компонента.
		$this->IncludeComponentTemplate();
		
        
        /*
        echo '<pre>';
        print_r($_SESSION[self::SESSCODE]);
        echo '</pre>';
        */
        
		return $this->arResult;
    }
    
    
    /**
     * ШАГ "Выбор стенда".
     */
    protected function doStepStands()
    {
        $event = $this->getEvent();
        
        // Площадь стенда.
        $this->arResult['AREA'] = $this->arParams['WIDTH'] * $this->arParams['DEPTH'];
        
        // Получение предвыбранного стенда.
        $this->usePreStand();
        
        // Список стендов.
        $this->arResult['STANDS'] = $this->getEvent()->getStandsList($this->arParams['WIDTH'], $this->arParams['DEPTH'], $this->getContext());
    }
    
    
    /**
     * Обработка шага "Выбор стенда".
     */
    protected function processStepStands()
    {
        // Запрос.
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        
        // Стенды мероприятия.
        $stands = $this->getEvent()->getStandsList($this->arParams['WIDTH'], $this->arParams['DEPTH'], $this->getContext());
        
        // Получение данных из сессии.
        $data = $this->getSession();
        $data['STAND'] = (int) $request->get('STAND');
        
        // Если выбран предустановленный станд.
        if ($data['STAND'] == $this->getEvent()->getPreselectStandID()) {
            $params = $this->getBasket()->getParams();
            
            $standoffer = $this->getEvent()->getPreselectStand()->getStandOffer($params['WIDTH'], $params['DEPTH'], $this->getContext());
            
            if (!empty($standoffer)) {
                $data['BASE'] = $standoffer->getBaseProductQIDs();
            }
        }
        
        $this->putSession($data);
        
        // Параметры стенда.
        $params = $this->getBasket()->getParams();
        
        // Сохранение данных в корзину.
        $this->getBasket()->put(
            intval($request->get('STAND')),
            ($params['WIDTH'] * $params['DEPTH']),
            Basket::KIND_STAND,
            [
                'width' => $params['WIDTH'], 
                'depth' => $params['DEPTH']
            ],
            $this->getContext()
        );
    }


    /**
     *  Шаг наполнениея продукцией
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
        $baskets = $this->getBasket()->getList();
        $objects = [];

        foreach ($baskets as $basket) {
            $element = $basket->getElement();

            if (empty($element)) {
                continue;
            }

            if (!$element->isSketchShow()) {
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

        // Объекты для скетча.
        $this->arResult['OBJECTS'] = $objects;

        // Размещенные обекты.
        $this->arResult['PLACED'] = $this->getBasket()->getSketch();

        // Стенд.
        $this->arResult['EVENT'] = $this->getEvent();

        // Стенд.
        if (!is_null($this->getBasket()->getStand())) {
            $this->arResult['STAND'] = new \Wolk\OEM\Stand($this->getBasket()->getStand()->getProductID());
        }
        
        // Параметры стенда.
        $params = $this->getBasket()->getParams();
        
        $this->arResult['WIDTH'] = $params['WIDTH'];
        $this->arResult['DEPTH'] = $params['DEPTH'];
        $this->arResult['SFORM'] = $params['SFORM'];
    }
    
    
    
    /**
     * Получение контекста.
     */
    protected function getContext()
    {
        return $this->context;
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
        $this->arResult['PRESTAND'] = $this->getEvent()->getPreselectStand();
        $this->arResult['PREOFFER'] = null;
        if (!empty($this->arResult['PRESTAND'])) {
            $this->arResult['PREOFFER'] = $this->arResult['PRESTAND']->getStandOffer($this->arParams['WIDTH'], $this->arParams['DEPTH'], $this->getContext());
        }
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
        $count = count($this->getSteps());
        $step  = $this->getStepNumber() - 1;
        
        if ($step < 1) {
            $step = 1;
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
     * Получение ссылки предыдущего шага.
     */
    public function getPrevStepLink()
    {
        $fields = [
            $this->arParams['CODE'],
            mb_strtolower($this->arParams['TYPE']),
            $this->getPrevStepNumber(),
            $this->arParams['WIDTH'].'x'.$this->arParams['DEPTH']
        ];
        
        if ($this->getContext()->getType() != Context::TYPE_INDIVIDUAL) {
            $fields []= $this->arParams['SFORM'];
        }
        
        $link = '/wizard/' . implode('/', $fields) . '/';
        
        return $link;
    }
    
    
    /**
     * Получение ссылки шага.
     */
    public function getStepLink($step = null)
    {
        if (is_null($step)) {
            $step = $this->getStepNumber();
        }
        
        $fields = [
            $this->arParams['CODE'],
            mb_strtolower($this->arParams['TYPE']),
            $step,
            $this->arParams['WIDTH'].'x'.$this->arParams['DEPTH']
        ];
        
        if ($this->getContext()->getType() != Context::TYPE_INDIVIDUAL) {
            $fields []= $this->arParams['SFORM'];
        }
        
        $link = '/wizard/' . implode('/', $fields) . '/';
        
        return $link;
    }
    
    
    /**
     * Получение ссылки следующего шага.
     */
    public function getNextStepLink()
    {
        $fields = [
            $this->arParams['CODE'],
            mb_strtolower($this->arParams['TYPE']),
            $this->getNextStepNumber(),
            $this->arParams['WIDTH'].'x'.$this->arParams['DEPTH']
        ];
        
        if ($this->getContext()->getType() != Context::TYPE_INDIVIDUAL) {
            $fields []= $this->arParams['SFORM'];
        }
        
        $link = '/wizard/' . implode('/', $fields) . '/';
        
        return $link;
    }
    
    
    /**
     * Получение списка шагов шагов.
     */
    protected function getSteps()
    {
        $steps = $this->getSessionParam['STEPS'];
        if (empty($steps)) {
            $steps = array_merge(['STANDS'], $this->getEvent()->getSteps($this->getContext()));
            
            // Добавление шага заказ и скетч (в случае если это не индивидуальная застройка).
            if ($this->getContext()->getType() != Context::TYPE_INDIVIDUAL) {
                $steps = array_merge($steps, ['SKETCH', 'ORDER']);
            } else {
                $steps = array_merge($steps, ['ORDER']);
            }
            $steps = array_map('mb_strtolower', $steps);
            $steps = array_flip(array_map(function($x) { return ($x + 1); }, array_flip($steps)));
            
            // Сохранение шагов в сессию.
            $this->putSessionParam('STEPS', $steps);
        }
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
        Bitrix\Main\Loader::includeModule('highloadblock');
        
        //$hlblock = HighloadBlockTable::getById(COLORS_ENTITY_ID)->fetch();
        //$entity  = HighloadBlockTable::compileEntity($hlblock);
        //$class   = $entity->getDataClass();

        $result = \Wolk\OEM\Dicts\Color::getList(['order' => ['UF_NUM' => 'ASC', 'UF_SORT' => 'ASC']], false);
        $colors = array();
        while ($color = $result->fetch()) {
            $colors[$color['ID']] = $color;
        }
        return $colors;
    }
}

