<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * Class ElementDataComponent
 */
class ElementDataComponent extends \CBitrixComponent
{
	
	/** 
	 * Установка настроек.
	 */
    public function onPrepareComponentParams($arParams)
    {
		// ID инфоблока.
		$arParams['IBLOCK_ID'] = (int) $arParams['IBLOCK_ID'];
		
		// Код элемента.
		$arParams['CODE'] = (string) $arParams['CODE'];
		
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
		
		$engine = new CComponentEngine($this);
		
		$templates = array(
			'none' => '',
			'element' => '#CODE#/',
		);
		
		$urltemplates = CComponentEngine::MakeComponentUrlTemplates($templates, $templates);

		$page = $engine->guessComponentPath(
			'/events/',
			$urltemplates,
			$variables
		);
		
		$this->arResult['ELEMENT'] = Wolk\Core\Helpers\IBlockElement::getByCode($this->arParams['IBLOCK_ID'], $variables['CODE']);
		
		$this->includeComponentTemplate();
	}
}