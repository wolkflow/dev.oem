<?

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * Class MailOrderComponent
 */
class MailOrderComponent extends \CBitrixComponent
{
	
	/** 
	 * Установка настроек.
	 */
    public function onPrepareComponentParams($arParams)
    {
		// ID заказа.
		$arParams['ID'] = (int) $arParams['ID'];
		
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

		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			ShowError('Модуль iblock не устанволен.');
			return;
		}

		if (!\Bitrix\Main\Loader::includeModule('sale')) {
			ShowError('Модуль sale не устанволен.');
			return;
		}
		
		
		// Установка текущего языка.
		Loc::setCurrentLang($this->arParams['LANG']);
		
		// TODO: Не менять язык.
		// Loc::loadLanguageFile(__FILE__, $this->arParams['LANG']);
		
		$site = \CSite::GetByID(SITE_DEFAULT)->Fetch();
		
		$this->arResult['SERVER_NAME'] = $site['SERVER_NAME'];
		$this->arResult['LANGUAGE']    = strtoupper($this->arParams['LANG']);
		
		// Заказ.
		$this->arResult['ORDER']   = CSaleOrder::getByID($this->arParams['ID']);
		$this->arResult['PROPS']   = Wolk\Core\Helpers\SaleOrder::getProperties($this->arParams['ID']);
		$this->arResult['BASKETS'] = Wolk\Core\Helpers\SaleOrder::getBaskets($this->arParams['ID']);
		$this->arResult['USER']    = CUser::getByID($this->arResult['ORDER']['USER_ID'])->Fetch();
		
		$event = CIBlockElement::getByID($this->arResult['PROPS']['eventId']['VALUE'])->GetNextElement();
		
		$this->arResult['EVENT'] = $event->getFields();
		$this->arResult['EVENT']['PROPS'] = $event->getProperties();
		$this->arResult['EVENT']['LOGO'] = CFile::ResizeImageGet($this->arResult['EVENT']['PROPS']['LANG_LOGO_'.$this->arResult['LANGUAGE']]['VALUE'], ['width' => 168, 'height' => 68], BX_RESIZE_IMAGE_PROPORTIONAL_ALT)['src'];
		
		
		// Количество позиций с ненулевой стоимостью.
		$count   = 0;
		$summary = 0;
		foreach ($this->arResult['BASKETS'] as &$basket) {
			
			$basket['SUMMARY_PRICE'] = $basket['PRICE'] * $basket['QUANTITY'];
			
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
		
		
	
		
		$surcharge = (float) $this->arResult['PROPS']['SURCHARGE_PRICE']['VALUE_ORIG'];

		$this->arResult['PRICES'] = [
			'BASKET'               => $summary,
			'VAT'                  => $this->arResult['ORDER']['TAX_VALUE'],
			'TOTAL_WITH_VAT'       => $this->arResult['ORDER']['PRICE'] - $surcharge,
			'TOTAL_WITH_SURCHARGE' => $this->arResult['ORDER']['PRICE'],
			'FINAL'                => $this->arResult['ORDER']['PRICE'],
		];

		if ($surcharge > 0) {
			$this->arResult['PRICES']['SURCHARGE']       = $this->arResult['PROPS']['SURCHARGE']['VALUE_ORIG'];
			$this->arResult['PRICES']['SURCHARGE_PRICE'] = $surcharge;
		}
		
		/*
		// Скетч.
		$printer = new Wolk\OEM\SketchPrint($this->arResult['ORDER']['ID']);
		$printer->make();
		
		// Путь.
		$this->arResult['SKETCH'] = $printer->getPath();
		*/
		
		$this->arResult['STATUSES'] = Wolk\Core\Helpers\SaleOrder::getStatuses();
				
		// Подключение шаблона.
		ob_start();
		
		$this->includeComponentTemplate();
		
		return ob_get_clean();
	}
	
}




