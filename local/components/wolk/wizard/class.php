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
use Wolk\OEM\Components\BaseListComponent;

use Wolk\OEM\Event;
use Wolk\OEM\Context;

/**
 * Class WizardComponent
 */
class WizardComponent extends \CBitrixComponent
{
    const SESSCODE = 'OEMEVENT';
    
    protected $context = null;
    
    
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
        
        
        // Контекст исполнения.
        $this->context = new Context($arParams['EID'], $arParams['TYPE'], $arParams['LANG']);
        
        
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
        
        
        // Шаги.
        $this->arResult['STEPS'] = $this->getSteps();
        $this->arResult['STEP']  = $this->arResult['STEPS'][$this->getStep()];
        
        // Выбираем шаг.
        switch ($this->arResult['STEP']) {
            // Выбор стенда.
            case ('stands'):
                $this->doStepStands();
                break;
            
            // Выбор оборудования.
            case ('equpments'):
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
        
        
        
        
        // Подключение шаблона компонента.
		$this->IncludeComponentTemplate();
		
        
		return $this->arResult;
    }
    
    
    /**
     * ШАГ "Выбор стенда".
     */
    protected function doStepStands()
    {
        $this->arResult['STANDS'] = $this->getEvent()->getStandsList($this->arParams['WIDTH'], $this->arParams['HEIGHT'], $this->getContext());
    }
    
    
    
    
    /**
     * Получение контекста.
     */
    protected function getContext()
    {
        return $this->context;
    }
    
    
    /**
     * Получение обхекта мероприятия.
     */
    protected function getEvent()
    {
        return (new Event($this->arParams['EID']));
    }
    
    
    /**
     * Получение текущего шага.
     */
    protected function getStep()
    {
        return $this->arParams['STEP'];
    }
    
    
    /**
     * Получение ссылки шага.
     */
    public function getStepLink($step)
    {
        $fields = [
            $this->arParams['CODE'],
            mb_strtolower($this->arParams['TYPE']),
            intval($step),
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
            
            // Сохранение шагов в сессию.
            $this->putSessionParam('STEPS', $steps);
        }
        return $steps;
    }
    
    
    /**
     * Получение массива данных из сессии.
     */
    protected function getSession()
    {
        return $_SESSION[self::SESSCODE];
    }
    
    
    /**
     * Сохранение массива данных в сессию.
     */
    protected function putSession($data)
    {
        $_SESSION[self::SESSCODE] = $data;
    }
    
    
    /**
     * Получение данных из сессии.
     */
    protected function getSessionParam($param)
    {
        return $_SESSION[self::SESSCODE][$param];
    }
    
    
    /**
     * Сохранение данных в сессию.
     */
    protected function putSessionParam($param, $value)
    {
        $_SESSION[self::SESSCODE][$param] = $value;
    }
}





