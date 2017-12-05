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
		// Код корзины.
		$arParams['DATA'] = (array) $arParams['DATA'];
		
		// Язык.
		$arParams['LANG'] = (string) $arParams['LANG'];
		
		// Проверка принадлежности.
		$arParams['CHECK'] =(string) $arParams['CHECK'] ;
		
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
		
		global $USER;
		
				
		// Настройки локализации.
		$site = \CSite::GetByID(SITE_DEFAULT)->Fetch();
		
		$this->arResult['SERVER_NAME'] = $site['SERVER_NAME'];
		
		$this->arResult['LANGUAGE'] = strtoupper($this->arParams['LANG']);
		
		
		// Установка языка.
		\Bitrix\Main\Context::getCurrent()->setLanguage($this->arParams['LANG']);
		
		
		// Корзина.
		$this->arResult['BASKET'] = $this->arParams['DATA'];
		
		// Идентификатор выставки.
		$eid = (int) \Wolk\Core\Helpers\IBlockElement::getIDByCode(IBLOCK_EVENTS_ID, $this->arResult['BASKET']['EVENT']);
		
		// Выставка.
		$this->arResult['EVENT'] = (new Wolk\OEM\Event($eid))->getData();
		
		
		// Подключение шаблона.
		$this->includeComponentTemplate();
	}
	
}
