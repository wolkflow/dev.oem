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
        $this->arResult['BASKET'] = new \Wolk\OEM\Basket($this->arParams['CODE']);
        
        // Мероприятие.
        $this->arResult['EVENT'] = $this->getEvent();
        
        // Контекст.
        $this->arResult['CONTEXT'] = $this->getContext();
        
        // Валюта.
        $this->arResult['CURRENCY'] = $this->arResult['EVENT']->getCurrencyProductsContext($this->getContext());
        
        // Стенд.
        $this->arResult['STAND'] = null;
        
        // Продукция.
        $this->arResult['PRODUCTS'] = array();
        
        // Записив корзине.
        $this->arResult['ITEMS'] = $this->arResult['BASKET']->getData();
        
        foreach ($this->arResult['ITEMS'] as $item) {
            if ($item['kind'] == 'stand') {
                $this->arResult['STANDITEM'] = $item;
                
                $this->arResult['STAND'] = new \Wolk\OEM\Stand($item['pid']);
                $this->arResult['STAND']->setPrice($item['price']);
            } else {
                $product = new \Wolk\OEM\Products\Base($item['pid']);
                $product->setPrice($item['price']);
                $product->setCount($item['quantity']);
                
                $this->arResult['PRODUCTS'][$product->getID()] = $product;
            }
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
