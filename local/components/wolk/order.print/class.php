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
		
		$this->arResult['LANGUAGE'] = strtoupper($this->arParams['LANG']);
		
		
		// Заказ.
		$this->arResult['OEMORDER'] = new \Wolk\OEM\Order($this->arParams['OID']);
		
		// Данные заказа.
		$this->arResult = array_merge($this->arResult, $this->arResult['OEMORDER']->getFullData());
		
		
		// Курс пересчета заказа.
		$rate = $this->arResult['ORDER']['PROPS']['RATE']['VALUE'];
		$this->arResult['RATE'] = (!empty($rate))  ? (floatval($rate)) : (1);
		
		// Валюта пересчета заказа.
		$currency = $this->arResult['ORDER']['PROPS']['RATE_CURRENCY']['VALUE'];
		$this->arResult['RATE_CURRENCY'] = (!empty($currency)) ? (strval($currency)) : ($this->arResult['ORDER']['CURRENCY']);
		
		// Мероприятие.
		$event = new \Wolk\OEM\Event($this->arResult['ORDER']['PROPS']['EVENT_ID']['VALUE']);
		
		
		// Данные мероприятия.
		$this->arResult['EVENT'] = $event->getData();
		
		
		
		// Подключение шаблона.
		$this->includeComponentTemplate();
	}
	
}
