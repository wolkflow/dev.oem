<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;


/**
 * Class ForgotPassComponent
 */
class ForgotPassComponent extends \CBitrixComponent
{
    
    /** 
	 * Установка настроек.
	 */
    public function onPrepareComponentParams($arParams)
    {        
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
		
        
        // Подключение шаблона компонента.
		$this->IncludeComponentTemplate();
		
        
		return $this->arResult;
    }
    
}
