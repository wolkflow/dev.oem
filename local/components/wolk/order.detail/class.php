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
use Wolk\OEM\Order;
use Wolk\OEM\Basket;
use Wolk\OEM\BasketItem;

/**
 * Class WizardComponent
 */
class OrderDetailComponent extends \CBitrixComponent
{
    
    /** 
	 * Установка настроек.
	 */
    public function onPrepareComponentParams($arParams)
    {   
        // ID заказа.
        $arParams['OID']  = (int) $arParams['OID'];
		
		
		// Проверка принадлежности зкакза.
        $arParams['CHECK']  = (bool) $arParams['CHECK'];
		
        
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
		
		// Заказ.
		$order = new Order($this->arParams['OID']);
		
		// Проверка принадлежности заказа текущему пользователю.
		if ($this->arParams['CHECK']) {
			if ($order->getUserID() != CUser::getID()) {
				return;
			}
		}
		
		// Полная информация о заказе.
		$this->arResult = $order->getFullData();
        
		// Ссылка на редактироваине.
		$this->arResult['ORDER']['LINK'] = $order->getLinkEdit();
		
		// Скетч.
		$this->arResult['SKETCH'] = $order->getSketch();
		
		
		$this->arResult['STAND'] = array(
			'SFORM' => $this->arResult['ORDER']['PROPS']['SFORM']['VALUE'],
			'WIDTH' => $this->arResult['ORDER']['PROPS']['WIDTH']['VALUE'],
			'DEPTH' => $this->arResult['ORDER']['PROPS']['DEPTH']['VALUE'],
		);
		
		$this->arResult['ITEMS'] = array(
			Wolk\OEM\Products\Section::TYPE_EQUIPMENTS => array(),
			Wolk\OEM\Products\Section::TYPE_SERVICES   => array(),
			Wolk\OEM\Products\Section::TYPE_MARKETINGS => array(),
		);
		
		foreach ($this->arResult['BASKETS'] as $basket) {
			if ($basket['PROPS']['STAND']['VALUE'] == 'Y') {
				$stand = new Wolk\OEM\Stand($basket['PRODUCT_ID']);
				
				$this->arResult['STAND']['BASKET'] = $basket;
				$this->arResult['STAND']['ITEM']   = $stand;
				$this->arResult['STAND']['DATA']   = $stand->getData();
				
				continue;
			}
			
			if ($basket['PROPS']['INCLUDED']['VALUE'] == 'Y') {
				continue;
			}
			
			// Продукция.
			$product = new Wolk\OEM\Products\Base($basket['PRODUCT_ID']);
			
			// Раздел.
			$section = $product->getSection()->getSection();
			
			
			if (!array_key_exists($section->getID(), $this->arResult['ITEMS'][$section->getType()])) {
				$this->arResult['ITEMS'][$section->getType()][$section->getID()] = array(
					'SELF'  => $section, 
					'ITEMS' => array()
				);
			}
			
			$this->arResult['ITEMS'][$section->getType()][$section->getID()]['ITEMS'][$product->getID()] = array(
				'SELF'   => $product,
				'BASKET' => $basket,
			);
		}
		

        // Подключение шаблона компонента.
		$this->IncludeComponentTemplate();
		
		
		return $this->arResult;
    }
}

