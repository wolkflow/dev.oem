<?php

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

use Wolk\OEM\Products\Base as Product;

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
		
		// ID корзины.
		$arParams['BID'] = (int) $arParams['BID'];
		
		// Путь к файлу.
		$arParams['PATH'] = (string) $arParams['PATH'];
		
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
		
		
		// Заказ.
		$this->arResult['OEMORDER'] = new \Wolk\OEM\Order($this->arParams['OID']);
		
		if ($this->arParams['CHECK'] == 'Y') {
			if ($this->arResult['OEMORDER']->getUserID() != $USER->getID()) {
				return;
			}
		}
		
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
		
		// Стенд.
		$this->arResult['STAND'] = null;
		
		// Надпись на фриз.
		$this->arResult['FASCIA'] = [];
		
		foreach ($this->arResult['BASKETS'] as &$basket) {
			if ($basket['PROPS']['STAND']['VALUE'] == 'Y') {
				$this->arResult['STAND'] = $basket;
			}
			$basket['PROPS']['FIELDS']['VALUE'] = json_decode($basket['PROPS']['FIELDS']['VALUE'], true);
			$basket['PROPS']['PARAMS']['VALUE'] = json_decode($basket['PROPS']['PARAMS']['VALUE'], true);
			
			$element = new Product($basket['PRODUCT_ID']);
			
			if ($element->isSpecialType(Product::SPECIAL_TYPE_FASCIA)) {
				$this->arResult['FASCIA'] []= $basket;
			}
		}
		
		
		// Скетч.
		$sketch = $this->arResult['OEMORDER']->getSketch();
		
		if (is_object($sketch)) {
			$this->arResult['ORDER']['PROPS']['SKETCH_SCENE']['VALUE'] = $sketch->getScene();
			$this->arResult['ORDER']['PROPS']['SKETCH_IMAGE']['VALUE'] = $sketch->getImage();
			$this->arResult['ORDER']['PROPS']['SKETCH_FILE']['VALUE']  = $sketch->getFilePath();
		}
		
		
		// Цвета.
		$this->arResult['COLORS'] = $this->getColors();
		
		
		// Подключение шаблона.
		$this->includeComponentTemplate();
	}
	
	
	
	/**
     * Выбор цветов.
     */
    protected static function getColors()
    {
        $result = \Wolk\OEM\Dicts\Color::getList(['order' => ['UF_SORT' => 'ASC', 'UF_NUM' => 'ASC']], false);
        $colors = [];
        while ($color = $result->fetch()) {
            $colors[$color['ID']] = $color;
        }
        return $colors;
    }
	
}
