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
use Wolk\OEM\Basket;
use Wolk\OEM\BasketItem;

/**
 * Class BasketComponent
 */
class BasketComponent extends \CBitrixComponent
{
    const SESSCODE = 'OEMEVENTS';
    
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
        
        // Корзина.
        $this->arResult['BASKET'] = new Basket($this->arParams['CODE']);
        
        // Суммарная стоимость.
        $this->arResult['PRICE'] = 0;
        
        // Мероприятие.
        $this->arResult['EVENT'] = $this->getEvent();
        
        // Контекст.
        $this->arResult['CONTEXT'] = $this->getContext();
        
        // Валюта.
        $this->arResult['CURRENCY'] = $this->arResult['EVENT']->getCurrencyProductsContext($this->getContext());
        
        // Стенд.
        $this->arResult['STAND'] = null;
        
        // Продукция.
        $this->arResult['STAND'] = $this->arResult['BASKET']->getStand();
        if (!empty($this->arResult['STAND'])) {
            $this->arResult['STAND']->loadPrice($this->getContext());
            $this->arResult['PRICE'] = $this->arResult['STAND']->getCost();
        }
        
        // Записив корзине.
        $this->arResult['ITEMS'] = $this->arResult['BASKET']->getList();
        
        foreach ($this->arResult['ITEMS'] as $item) {
            $item->loadPrice($this->getContext());
            
            // Суммарная стоимость продукции.
            $this->arResult['PRICE'] += $item->getCost();
        }
        
        
        // Подключение шаблона компонента.
		$this->IncludeComponentTemplate();
		
        
		return $this->arResult;
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
}
