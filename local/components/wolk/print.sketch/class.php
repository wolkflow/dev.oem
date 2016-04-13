<?php


/**
 * Class PrintSketchComponent
 */
class PrintSketchComponent extends \CBitrixComponent
{
	
	/** 
	 * Установка настроек.
	 */
    public function onPrepareComponentParams($arParams)
    {
		// ID заказа.
		$arParams['ORDER_ID'] = (int) $arParams['ORDER_ID'];
				
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

		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			ShowError('Модуль iblock не устанволен.');
			return;
		}

		if (!\Bitrix\Main\Loader::includeModule('sale')) {
			ShowError('Модуль sale не устанволен.');
			return;
		}
		
		
		// Заказ.
		$this->arResult['ORDER']   = CSaleOrder::getByID($this->arParams['ORDER_ID']);
		$this->arResult['PROPS']   = Wolk\Core\Helpers\SaleOrder::getProperties($this->arParams['ORDER_ID']);
		$this->arResult['BASKETS'] = Wolk\Core\Helpers\SaleOrder::getBaskets($this->arParams['ORDER_ID']);
		
		foreach ($this->arResult['BASKETS'] as &$basket) {
			if ($basket['PRODUCT_ID'] > 0) {
				$item = CIBlockElement::getByID($basket['PRODUCT_ID'])->GetNextElement();
				
				$basket['ITEM'] = $item->getFields();
				$basket['ITEM']['PROPS'] = $item->getProperties();
			}
		}
		unset($item, $basket);
		
		// Данные для скетча.
		$this->arResult['SKETCH'] = json_decode($this->arResult['PROPS']['sketch']['VALUE'], true);

		$this->arResult['SKETCH']['items'] = [];
		foreach ($this->arResult['BASKETS'] as $basket) {
			if ($basket['ITEM']['PROPS']['WIDTH']['VALUE'] && $basket['ITEM']['PROPS']['HEIGHT']['VALUE']) {
				if (array_key_exists($basket['ITEM']['ID'], $this->arResult['SKETCH']['items'])) {
					$this->arResult['SKETCH']['items'] [$basket['ITEM']['ID']]['quantity'] += $basket['QUANTITY'];
				} else {
					$this->arResult['SKETCH']['items'] [$basket['ITEM']['ID']] = [
						'id'        => $basket['ITEM']['ID'],
						'imagePath' => CFile::ResizeImageGet($basket['ITEM']['PROPS']['SKETCH_IMAGE']['VALUE'], [
							'width' => ($basket['ITEM']['PROPS']['WIDTH']['VALUE'] / 10 < 30) ? 30 : $basket['ITEM']['PROPS']['WIDTH']['VALUE'] / 10,
							'height' => ($basket['ITEM']['PROPS']['HEIGHT']['VALUE'] / 10 < 30) ? 30 : $basket['ITEM']['PROPS']['HEIGHT']['VALUE'] / 10,
						])['src'],
						'quantity'  => $basket['QUANTITY'],
						'title'     => $basket['ITEM']['NAME'],
						'type'      => $basket['ITEM']['PROPS']['SKETCH_TYPE']['VALUE'] ?: 'droppable',
						'w'         => (float) $basket['ITEM']['PROPS']['WIDTH']['VALUE'] / 1000,
						'h'         => (float) $basket['ITEM']['PROPS']['HEIGHT']['VALUE'] / 1000
					];
				}

			}
		}
		unset($item, $basket);
		
		
		// Подключение шаблона.
		$this->includeComponentTemplate();
	}
	
}




