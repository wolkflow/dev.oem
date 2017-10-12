<?php

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * Class OrderPrintComponent
 */
class OrderPrintComponent extends \CBitrixComponent
{
	
	/** 
	 * Установка настроек.
	 */
    public function onPrepareComponentParams($arParams)
    {
		// ID заказа.
		$arParams['OID'] = (int) $arParams['OID'];
		
		// Путь к файлу.
		$arParams['PATH'] = (string) $arParams['PATH'];
		
		// Язык.
		$arParams['LANG'] = (string) $arParams['LANG'];
		
		if (empty($arParams['LANG'])) {
			$arParams['LANG'] = \Bitrix\Main\Application::getInstance()->getContext()->getLanguage();
		}
		
        return $arParams;
    }
	
	
	/**
	 * Выполнение компонента.
	 */
	public function executeComponent()
    {
		if (!\Bitrix\Main\Loader::includeModule('wolk.core')) {
			ShowError('Модуль wolk.core не устанволен.');
			return;
		}
		
		if (!\Bitrix\Main\Loader::includeModule('wolk.oem')) {
			ShowError('Модуль wolk.oem не устанволен.');
			return;
		}

		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			ShowError('Модуль iblock не устанволен.');
			return;
		}

		if (!\Bitrix\Main\Loader::includeModule('sale')) {
			ShowError('Модуль sale не устанволен.');
			return;
		}
		
		
		// Настройки локализации.
		$site = \CSite::GetByID(SITE_DEFAULT)->Fetch();
		
		$this->arResult['SERVER_NAME'] = $site['SERVER_NAME'];
		$this->arResult['LANGUAGE']    = strtoupper($this->arParams['LANG']);
		
		
		// Заказ.
		$this->arResult['OEMORDER'] = new \Wolk\OEM\Order($this->arParams['OID']);
		
		// Данные заказа.
		$this->arResult['ORDER'] = $this->arResult['OEMORDER']->getFullData();
		
		
		// Курс пересчета заказа.
		$this->arResult['RATE'] = (!empty($this->arResult['ORDER']['PROPS']['RATE']['VALUE'])) 
								? (floatval($this->arResult['ORDER']['PROPS']['RATE']['VALUE'])) 
								: (1);
		
		// Валюта пересчета заказа.
		$this->arResult['RATE_CURRENCY'] = (!empty($this->arResult['ORDER']['PROPS']['RATE_CURRENCY']['VALUE'])) 
									     ? (strval($this->arResult['ORDER']['PROPS']['RATE_CURRENCY']['VALUE'])) 
									     : ($this->arResult['ORDER']['CURRENCY']);
		
		// Мероприятие.
		$event = new \Wolk\OEM\Event($this->arResult['PROPS']['EVENT_ID']['VALUE']);
		
		// Данные мероприятия.
		$this->arResult['EVENT'] = $event->getData();
		
		
		/*
		// Количество позиций с ненулевой стоимостью.
		$count   = 0;
		$summary = 0;
		foreach ($this->arResult['BASKETS'] as &$basket) {
			
			$basket['SUMMARY_PRICE'] = $basket['PRICE'] * $basket['QUANTITY'] * $rate;
			
			if ($basket['PRICE'] > 0) {
				$count++;
				$summary += $basket['SUMMARY_PRICE'];
			}
			
			if ($basket['PRODUCT_ID'] > 0) {
				$element = CIBlockElement::getByID($basket['PRODUCT_ID'])->GetNextElement();
				
				if (!$element) {
					continue;
				}
				$basket['ITEM'] = $element->getFields();
				$basket['ITEM']['PROPS'] = $element->getProperties();
				$basket['ITEM']['IMAGE'] = CFile::getPath($basket['ITEM']['PREVIEW_PICTURE']);
			}
			
			// Является ли товар стендом.
			$basket['IS_STAND'] = ($basket['TYPE'] == 0);
		}
		unset($element, $basket);
		
        
		$this->arResult['PRICES'] = [
			'BASKET'               => $summary,
			'VAT'                  => $this->arResult['ORDER']['TAX_VALUE'],
			'TOTAL_WITH_VAT'       => $this->arResult['ORDER']['PRICE'] - $surcharge,
			'TOTAL_WITH_SURCHARGE' => $this->arResult['ORDER']['PRICE'],
			'FINAL'                => $this->arResult['ORDER']['PRICE'],
		];
		
		if ($surcharge > 0) {
			$this->arResult['PRICES']['SURCHARGE'] = $this->arResult['PROPS']['SURCHARGE']['VALUE_ORIG'];
			$this->arResult['PRICES']['SURCHARGE_PRICE'] = $surcharge;
		}
		
		
		// Конвертирование цены.
		foreach ($this->arResult['PRICES'] as &$price) {
			$price *= $rate;
		}
		
		// Конвертирование валюты.
		$this->arResult['ORDER']['CURRENCY'] = $currency;
		*/
		
		
		
		// Подключение шаблона.
		$this->includeComponentTemplate();
	}
	
}
