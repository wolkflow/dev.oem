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
		
		$event = CIBlockElement::getByID($this->arResult['PROPS']['eventId']['VALUE'])->GetNextElement();
		
		$this->arResult['EVENT'] = $event->getFields();
		$this->arResult['EVENT']['PROPS'] = $event->getProperties();
		$this->arResult['EVENT']['LOGO']  = CFile::ResizeImageGet($this->arResult['EVENT']['PROPS']['LANG_LOGO_'.$this->arResult['LANGUAGE']]['VALUE'], ['width' => 168, 'height' => 68], BX_RESIZE_IMAGE_PROPORTIONAL_ALT)['src'];
		
		
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
		foreach ($this->arResult['BASKETS'] as &$basket) {
			
			if ($basket['PRODUCT_ID'] > 0) {
				$item = CIBlockElement::getByID($basket['PRODUCT_ID'])->GetNextElement();
				
				$basket['ITEM'] = $item->getFields();
				$basket['ITEM']['PROPS'] = $item->getProperties();
				$basket['ITEM']['IMAGE'] = CFile::ResizeImageGet($basket['ITEM']['PROPS']['SKETCH_IMAGE']['VALUE'], ['width' => 78, 'height' => 78])['src']; // CFile::getPath($basket['ITEM']['PREVIEW_PICTURE']);
			}
			
			// Является ли товар стендом.
			$basket['IS_STAND'] = ($basket['TYPE'] == 0);
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
		
		
		
		// Количество позиций с ненулевой стоимостью.
		$count   = 0;
		$summary = 0;
		foreach ($this->arResult['BASKETS'] as $basket) {
			if ($basket['SUMMARY_PRICE'] > 0) {
				$count++;
				$summary += $basket['SUMMARY_PRICE'];
			}
		}
		
		
		// Стоимость товаров в корзине (без наценок).
		$this->arResult['ORDER']['BASKET_PRICE'] = $summary;
		
		// Стоимость заказа без налогов и с наценками.
		$this->arResult['ORDER']['BASKET_TOTAL_PRICE'] = $this->arResult['ORDER']['PRICE'] - $this->arResult['ORDER']['TAX_VALUE'];
		
		
		if ($count > 0) {
			$overprice = ($this->arResult['ORDER']['BASKET_TOTAL_PRICE'] - $this->arResult['ORDER']['BASKET_PRICE']) / $count;
			
			foreach ($this->arResult['BASKETS'] as &$basket) {
				if ($basket['SUMMARY_PRICE'] > 0) {
					$basket['TOTAL_PRICE'] = $basket['SUMMARY_PRICE'] + $overprice;
				}
			}
		}
		*/
		
		// Подключение шаблона.
		$this->includeComponentTemplate();
	}
	
}




