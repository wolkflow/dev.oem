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
        $arParams['STEP'] = (string) $arParams['STEP'];
        
        // Тип стенда.
        $arParams['TYPE'] = (string) $arParams['TYPE'];
        
        // Язык.
        $arParams['LANG'] = (string) $arParams['LANG'];
        
        // Ширина стенда.
        $arParams['WIDTH'] = (float) $arParams['WIDTH'];
        
        // Глубина стенда.
        $arParams['DEPTH'] = (float) $arParams['DEPTH'];
        
        
        // Контекст исполнения.
        $this->context = new Context($arParams['EID'], $arParams['TYPE'], $arParams['LANG']);
        
        // Объект корзины.
        $this->basket = new Basket($arParams['CODE']);
        
        
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
            LocalRedirect($this->getStepLink(1));
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
        
        
        // Мероприятие.
        $this->arResult['EVENT'] = $this->getEvent();
        
        // Контекст.
        $this->arResult['CONTEXT'] = $this->getContext();
        
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
        
        // Очистка данных по выставке.
        $_SESSION[self::SESSCODE][mb_strtoupper($event->getCode())] = array('STAND' => array(), 'BASKET' => array());
        
        // Площадь стенда.
        $this->arResult['AREA'] = $this->arParams['WIDTH'] * $this->arParams['DEPTH'];
        
        $this->arResult['PRESTAND'] = $event->getPreselectStand();
        $this->arResult['PREOFFER'] = null;
        if (!empty($this->arResult['PRESTAND'])) {
            $this->arResult['PREOFFER'] = $this->arResult['PRESTAND']->getStandOffer($this->arParams['WIDTH'], $this->arParams['DEPTH'], $this->getContext());
        }
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
        $this->putSession($data);
        
        // Сохранение данных в корзину.
        $this->getBasket()->put(
            intval($request->get('STAND')),
            ($this->arParams['WIDTH'] * $this->arParams['DEPTH']),
            Basket::KIND_STAND,
            [
                'width' => $this->arParams['WIDTH'], 
                'depth' => $this->arParams['DEPTH']
            ],
            $this->getContext()
        );
    }
    
    
    
    /**
     * ШАГ "Выбор оборудования".
     */
    protected function doStepEquipments()
    {
        $event = $this->getEvent();
        
        // Спсиок оборудования.
        $products = $event->getProducts($this->getContext(), Wolk\OEM\Products\Section::TYPE_EQUIPMENTS);
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
        
        // Группы и продукция.
        $this->arResult['ITEMS'] = $parents;
    }
    
    
    /**
     * Обработка шага "Выбор оборудования".
     */
    protected function processStepEquipments()
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
     * Получение корзины.
     */
    protected function getBasket()
    {
        return $this->basket;
    }
    
    
    /**
     * Получение обхекта мероприятия.
     */
    protected function getEvent()
    {
        return (new Event($this->arParams['EID']));
    }
    
    
    /**
     * Получение кода мероприятия.
     */
    protected function getEventCode()
    {
        return (mb_strtoupper($this->getEvent()->getCode()));
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
    public function getPrevStepLink($step)
    {
        $fields = [
            $this->arParams['CODE'],
            mb_strtolower($this->arParams['TYPE']),
            $this->getPrevStepNumber(),
        ];
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
        ];
        $link = '/wizard/' . implode('/', $fields) . '/';
        
        return $link;
    }
    
    
    /**
     * Получение ссылки следующего шага.
     */
    public function getNextStepLink($step)
    {
        $fields = [
            $this->arParams['CODE'],
            mb_strtolower($this->arParams['TYPE']),
            $this->getNextStepNumber(),
        ];
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
}

