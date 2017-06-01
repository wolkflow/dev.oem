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



// ID элемента инфоблока.
$ID = (int) $request->get('ID');

// Валюты цен на стенды.
$currencies_stands = (array) $request->get('CURRENCY_STANDS');

// Валюты цен на продукцию.
$currencies_products = (array) $request->get('CURRENCY_PRODUCTS');


// Проверка наличия ошибок в данных.
if (!empty($ID)) {
    if (empty($currencies_stands[StandPrice::TYPE_STANDARD]['RU'])) {
        $error = new _CIBlockError(IBLOCK_ERROR_TYPE_FAIL, "ERROR_SAVE_STANDS_CURRENCY", 'Не указана валюта для цен стандартных стендов (RU)');
    }

    if (empty($currencies_stands[StandPrice::TYPE_INDIVIDUAL]['EN'])) {
        $error = new _CIBlockError(IBLOCK_ERROR_TYPE_FAIL, "ERROR_SAVE_STANDS_CURRENCY", 'Не указана валюта для цен стандартных стендов (EN)');
    }

    if (empty($currencies_stands[StandPrice::TYPE_STANDARD]['RU'])) {
        $error = new _CIBlockError(IBLOCK_ERROR_TYPE_FAIL, "ERROR_SAVE_STANDS_CURRENCY", 'Не указана валюта для цен индивидуальных стендов (RU)');
    }

    if (empty($currencies_stands[StandPrice::TYPE_INDIVIDUAL]['EN'])) {
        $error = new _CIBlockError(IBLOCK_ERROR_TYPE_FAIL, "ERROR_SAVE_STANDS_CURRENCY", 'Не указана валюта для цен индивидуальных стендов (EN)');
    }


    if (empty($currencies_products[ProductPrice::TYPE_STANDARD]['RU'])) {
        $error = new _CIBlockError(IBLOCK_ERROR_TYPE_FAIL, "ERROR_SAVE_STANDS_CURRENCY", 'Не указана валюта для цен стандартной продукции (RU)');
    }

    if (empty($currencies_products[ProductPrice::TYPE_INDIVIDUAL]['EN'])) {
        $error = new _CIBlockError(IBLOCK_ERROR_TYPE_FAIL, "ERROR_SAVE_STANDS_CURRENCY", 'Не указана валюта для цен индивидуальной продукции (EN)');
    }

    if (empty($currencies_products[ProductPrice::TYPE_STANDARD]['RU'])) {
        $error = new _CIBlockError(IBLOCK_ERROR_TYPE_FAIL, "ERROR_SAVE_STANDS_CURRENCY", 'Не указана валюта для цен стандартной продукции (RU)');
    }

    if (empty($currencies_products[ProductPrice::TYPE_INDIVIDUAL]['EN'])) {
        $error = new _CIBlockError(IBLOCK_ERROR_TYPE_FAIL, "ERROR_SAVE_STANDS_CURRENCY", 'Не указана валюта для цен индивидуальной продукции (EN)');
    }
}


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
    
    
    // Валюты цен на стенды.
    $currencies_stands = (array) $request->get('CURRENCY_STANDS');
    
    // Валюты цен на продукцию.
    $currencies_products = (array) $request->get('CURRENCY_PRODUCTS');
    
    
    // Сохранение цен на выбранные стенды.
    if (!empty($prices_stands)) {
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
                        StandPrice::FIELD_CURRENCY => ($currencies_stands[$type][$lang]) ?: (CURRENCY_DEFAULT),
                        StandPrice::FIELD_PRICE    => (float) $price,
                    ];
                    
                    $element = new StandPrice();
                    
                    try {
                        $result = $element->add($pricedata);
                    } catch (\Exception $e) {
                        //
                    }
                }
                
                // Сохранение валюты для цен.
                CIBlockElement::SetPropertyValueCode($arFields['ID'], 'LANG_STANDS_'.$type.'_CURRENCY_'.$lang, $currencies_stands[$type][$lang]);
            }
        }
    }
    
    
    // Сохранение цен на выбранные стенды.
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
                        ProductPrice::FIELD_CURRENCY => ($currencies_products[$type][$lang]) ?: (CURRENCY_DEFAULT),
                        ProductPrice::FIELD_PRICE    => (float) $price,
                    ];
                    
                    $element = new ProductPrice();
                    
                    try {
                        $result = $element->add($pricedata);
                    } catch (\Exception $e) {
                        // 
                    }
                }
                
                // Сохранение валюты для цен.
                CIBlockElement::SetPropertyValueCode($arFields['ID'], 'LANG_PRODUCTS_'.$type.'_CURRENCY_'.$lang, $currencies_products[$type][$lang]);
            }
        }
    }
    
    return $arFields;
}

