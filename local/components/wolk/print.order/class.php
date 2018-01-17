<?

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

// https://habrahabr.ru/post/190364/
// https://habrahabr.ru/sandbox/23506/

/**
 * Class PrintOrderComponent
 */
class PrintOrderComponent extends \CBitrixComponent
{
	
	/** 
	 * Установка настроек.
	 */
    public function onPrepareComponentParams($arParams)
    {
		// ID заказа.
		$arParams['ORDER_ID'] = (int) $arParams['ORDER_ID'];
		
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
		
		// Настройки локализации.
		$site = \CSite::GetByID(SITE_DEFAULT)->Fetch();
		
		$this->arResult['SERVER_NAME'] = $site['SERVER_NAME'];
		$this->arResult['LANGUAGE']    = strtoupper($this->arParams['LANG']);
		
		// Заказ.
		$this->arResult['ORDER']   = CSaleOrder::getByID($this->arParams['ORDER_ID']);
		$this->arResult['PROPS']   = Wolk\Core\Helpers\SaleOrder::getProperties($this->arParams['ORDER_ID']);
		$this->arResult['BASKETS'] = Wolk\Core\Helpers\SaleOrder::getBaskets($this->arParams['ORDER_ID']);
		$this->arResult['USER']    = CUser::getByID($this->arResult['ORDER']['USER_ID'])->Fetch();
		
		
		// Скетч.
		$order  = new Wolk\OEM\Order($this->arParams['ORDER_ID']);
		$sketch = $order->getSketch();
		
		$this->arResult['SKETCH_IMAGE'] = '';
		if (is_object($sketch)) {
			$this->arResult['SKETCH_IMAGE'] = $sketch->getFilePath();
		}
		
		
		// Курс пересчета заказа.
		$rate     = (!empty($this->arResult['PROPS']['RATE']['VALUE'])) 
					? (floatval($this->arResult['PROPS']['RATE']['VALUE'])) 
					: (1);
		$currency = (!empty($this->arResult['PROPS']['RATE_CURRENCY']['VALUE'])) 
					? (strval($this->arResult['PROPS']['RATE_CURRENCY']['VALUE'])) 
					: ($this->arResult['ORDER']['CURRENCY']);
		
        // Наценка.
        $surcharge       = (float) $this->arResult['PROPS']['SURCHARGE']['VALUE_ORIG'];
        $surcharge_price = (float) $this->arResult['PROPS']['SURCHARGE_PRICE']['VALUE_ORIG'];
        
		$event = CIBlockElement::getByID($this->arResult['PROPS']['EVENT_ID']['VALUE'])->GetNextElement();
		
		$this->arResult['EVENT'] = $event->getFields();
		$this->arResult['EVENT']['PROPS'] = $event->getProperties();
		$this->arResult['EVENT']['LOGO']  = CFile::ResizeImageGet($this->arResult['EVENT']['PROPS']['LANG_LOGO_'.$this->arResult['LANGUAGE']]['VALUE'], ['width' => 168, 'height' => 68], BX_RESIZE_IMAGE_PROPORTIONAL_ALT)['src'];
		
		
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
		
		
		// Подключение шаблона.
		$this->includeComponentTemplate();
	}
	
}




