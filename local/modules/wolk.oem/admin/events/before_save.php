<?php

use \Wolk\OEM\Event;
use \Wolk\OEM\Context;
use \Wolk\OEM\Products\Base as Product;
use Wolk\OEM\Prices\Stand   as StandPrice;
use Wolk\OEM\Prices\Product as ProductPrice;
use Wolk\OEM\Products\Param as SectionParam;

if (!\Bitrix\Main\Loader::includeModule('wolk.core')) {
    return;
}

if (!\Bitrix\Main\Loader::includeModule('wolk.oem')) {
    return;
}

// Запрос.
$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if (!$request->isPost() || !empty($dontsave)) {
    return;
}



// ID элемента инфоблока.
$ID = (int) $request->get('ID');

// Валюты цен на стенды.
$currencies = (array) $request->get('CURRENCIES');


/**
 * Функция сохранения данных.
 */
function BXIBlockAfterSave(&$arFields)
{
    // Запрос.
    $request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    
    // Мероприятие.
    $event = new Event($arFields['ID']);
    
	
	// Продукция стандартной застройки.
    //$products_standard = (array) $request->get('PROP')[ELEMENT_EVENTS_PROPERTY_PRODUCTS_STANDARD_ID];
	//$products_standard = array_filter($products_standard, function($x) { return (!empty($x) && !is_array($x)); });
	$products_standard = array_filter((array) $request->get('PRODUCTS_STANDARD'));
	
	// Продукция индивидуальной застройки.
    //$products_individual = (array) $request->get('PROP')[ELEMENT_EVENTS_PROPERTY_PRODUCTS_INDIVIDUAL_ID];
	//$products_individual = array_filter($products_individual, function($x) { return (!empty($x) && !is_array($x)); });
	$products_individual = array_filter((array) $request->get('PRODUCTS_INDIVIDUAL'));
	
	CIBlockElement::SetPropertyValueCode($event->getID(), 'PRODUCTS_STANDARD',   $products_standard);
	CIBlockElement::SetPropertyValueCode($event->getID(), 'PRODUCTS_INDIVIDUAL', $products_individual);
	
    // Цены на стенды.
    $prices_stands = (array) $request->get('PRICES_STANDS');
    
    // Цены на продукцию.
    $prices_products = (array) $request->get('PRICES_PRODUCTS');
	
	// Параметры продукции.
    $params_sections = (array) $request->get('PARAMS_SECTIONS');
	
	
    // Сохранение цен на выбранные стенды.
    if (!empty($prices_stands)) {
		
		$datas = [];
		
        foreach ($prices_stands as $type => $langs) {
            foreach ($langs as $lang => $prices) {
                
                // Удаление старых цен.
                $event->clearStandsPrices($type, $lang);
                
                foreach ($prices as $stand => $price) {
                    $data = [
                        StandPrice::FIELD_EVENT    => $arFields['ID'],
                        StandPrice::FIELD_STAND    => $stand,
                        StandPrice::FIELD_TYPE     => $type,
                        StandPrice::FIELD_LANG     => $lang,
                        StandPrice::FIELD_CURRENCY => ($currencies[$type][$lang]) ?: (CURRENCY_DEFAULT),
                        StandPrice::FIELD_PRICE    => (float) $price,
                    ];
					
					$datas []= $data;
                }
            }
        }
		
		if (!empty($datas)) {
			try {
				$result = StandPrice::runBatchInsert($datas);
			} catch (\Exception $e) {
				// exception
			}
		}
    }
	
	
    // Сохранение цен на выбранные товары и услуги.
    if (!empty($prices_products)) {
		
		$datas = [];
		
        foreach ($prices_products as $type => $langs) {
            foreach ($langs as $lang => $prices) {
                
                // Удаление старых цен.
                $event->clearProductsPrices($type, $lang);
                
                foreach ($prices as $product => $price) {
                    $data = [
                        ProductPrice::FIELD_EVENT    => $arFields['ID'],
                        ProductPrice::FIELD_PRODUCT  => $product,
                        ProductPrice::FIELD_TYPE     => $type,
                        ProductPrice::FIELD_LANG     => $lang,
                        ProductPrice::FIELD_CURRENCY => ($currencies[$type][$lang]) ?: (CURRENCY_DEFAULT),
                        ProductPrice::FIELD_PRICE    => (float) $price,
                    ];
                    
					$datas []= $data;
                }
            }
        }
		
		if (!empty($datas)) {
			try {
				$result = ProductPrice::runBatchInsert($datas);
			} catch (\Exception $e) {
				// exception
			}
		}
    }
	
	
	// Сохранение параметров продукции.
    if (!empty($params_sections)) {
		
		$datas = [];
		
        foreach ($params_sections as $lang => $sections) {
			
			// Удаление старых цен.
			$event->clearSectionsParams($lang);
			
			foreach ($sections as $section => $params) {
				$data = [
					SectionParam::FIELD_EVENT   => $arFields['ID'],
					SectionParam::FIELD_SECTION => $section,
					SectionParam::FIELD_LANG    => $lang,
					SectionParam::FIELD_PROPS   => json_encode((array) $params['PROPS'], JSON_UNESCAPED_UNICODE),
					SectionParam::FIELD_NAMES   => json_encode((array) $params['NAMES'], JSON_UNESCAPED_UNICODE),
					SectionParam::FIELD_NOTE    => (string) $params['NOTE'],
				];
				
				$datas []= $data;
			}
		}
		
		if (!empty($datas)) {
			try {
				$result = SectionParam::runBatchInsert($datas);
			} catch (\Exception $e) {
				// exception
			}
		}
	}
	
    return $arFields;
}

