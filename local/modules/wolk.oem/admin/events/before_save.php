<?php

use \Wolk\OEM\Event;
use \Wolk\OEM\Context;
use \Wolk\OEM\Products\Base as Product;
use Wolk\OEM\Prices\Stand   as StandPrice;
use Wolk\OEM\Prices\Product as ProductPrice;

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

//if ($REQUEST_METHOD=="POST" && strlen($Update)>0 && $view!="Y" && (!$error) && empty($dontsave) && strlen($_POST['DETAIL_TEXT'])<=0)
  // $error = new _CIBlockError(2, "DESCRIPTION_REQUIRED", "Введите текст статьи");


// echo '<pre>'; print_r($_REQUEST); echo '</pre>'; die();

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
    
    // Свойства иинфоблока мероприятий.
    // $props = Wolk\Core\Helpers\IBlock::getProps(IBLOCK_EVENTS_ID);
    
    // Мероприятие.
    $event = new Event($arFields['ID']);
        
    // Цены на стенды.
    $prices_stands = (array) $request->get('PRICES_STANDS');
    
    // Цены на продукцию.
    $prices_products = (array) $request->get('PRICES_PRODUCTS');
	
	
	
    
	
    // Сохранение цен на выбранные стенды.
    if (!empty($prices_stands)) {
		$pricedatas = [];
        foreach ($prices_stands as $type => $langs) {
            foreach ($langs as $lang => $prices) {
                
                // Удаление старых цен.
                $event->clearStandsPrices($type, $lang);
                
                foreach ($prices as $stand => $price) {
                    $pricedata = [
                        StandPrice::FIELD_EVENT    => $arFields['ID'],
                        StandPrice::FIELD_STAND    => $stand,
                        StandPrice::FIELD_TYPE     => $type,
                        StandPrice::FIELD_LANG     => $lang,
                        StandPrice::FIELD_CURRENCY => ($currencies[$type][$lang]) ?: (CURRENCY_DEFAULT),
                        StandPrice::FIELD_PRICE    => (float) $price,
                    ];
					
					$pricedatas []= $pricedata;
                }
            }
        }
		
		if (!empty($pricedatas)) {
			try {
				$result = StandPrice::runBatchInsert($pricedatas);
			} catch (\Exception $e) {
				// exception
			}
		}
    }
	return;
    
    
	/*
    // Сохранение цен на выбранные товары и услуги.
    if (!empty($prices_products)) {
        foreach ($prices_products as $type => $langs) {
            foreach ($langs as $lang => $prices) {
                
                // Удаление старых цен.
                $event->clearProductsPrices($type, $lang);
                
                foreach ($prices as $product => $price) {
                    $pricedata = [
                        ProductPrice::FIELD_EVENT    => $arFields['ID'],
                        ProductPrice::FIELD_PRODUCT  => $product,
                        ProductPrice::FIELD_TYPE     => $type,
                        ProductPrice::FIELD_LANG     => $lang,
                        ProductPrice::FIELD_CURRENCY => ($currencies[$type][$lang]) ?: (CURRENCY_DEFAULT),
                        ProductPrice::FIELD_PRICE    => (float) $price,
                    ];
                    
                    $element = new ProductPrice();
                    
                    try {
                        $result = $element->add($pricedata);
                    } catch (\Exception $e) {
                        // exception
                    }
                }
            }
        }
    }
	*/
    
    return $arFields;
}

