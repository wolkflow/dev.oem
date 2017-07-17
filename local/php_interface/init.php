<?php

// Глобальный js-переменные.
$GLOBALS['JSVARS'] = array(
    'LANGS' => [], 
    'LANG'  => \Bitrix\Main\Context::getCurrent()->getLanguage()
);

// Константы.
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;

include($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/constants.php');

/*
 * Подключение обработчкиов событий.
 */
$em = EventManager::getInstance();

Loader::includeModule('wolk.core');

if (Loader::includeModule('wolk.oem')) {
    $em->addEventHandler('iblock', 'OnIBlockPropertyBuildList', ['\Wolk\OEM\Events\Iblock', 'colorPickerPropertyType']);
    $em->addEventHandler('iblock', 'OnIBlockPropertyBuildList', ['\Wolk\OEM\Events\Iblock', 'UsingEquipmentPropertyType']);
	
    $em->addEventHandler('iblock', 'OnAfterIBlockElementAdd', ['\Wolk\OEM\Events\Iblock', 'saveProductsSet']);
    $em->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', ['\Wolk\OEM\Events\Iblock', 'saveProductsSet']);
    
	$em->addEventHandler('main', 'OnBeforeUserUpdate', ['\Wolk\OEM\Events\Main', 'onBeforeUserUpdateHandler']);
    $em->addEventHandler('main', 'OnBeforeUserRegister', ['\Wolk\OEM\Events\Main', 'onBeforeUserRegisterHandler']);
	$em->addEventHandler('main', 'OnAfterUserRegister', ['\Wolk\OEM\Events\Main', 'OnAfterUserRegister']);
    
	// $em->addEventHandler('catalog', 'OnGetOptimalPrice', ['\Wolk\OEM\Events\Catalog', 'onGetOptimalPriceHandler']);
    // $em->addEventHandler('sale', 'OnSaleStatusOrder', ['\Wolk\OEM\Events\Sale', 'OnOrderStatusSendEmail']);
    
    $em->addEventHandler('sale', 'OnSaleOrderSaved', ['\Wolk\OEM\Events\Sale', 'OnSaleOrderSaved']);
}

Loader::registerAutoLoadClasses(
    null,
    [
        'Helper' => '/local/php_interface/libs/helper.php'
    ]
);

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';



// Исправление добавления товара.
// https://dev.1c-bitrix.ru/community/forums/forum6/topic84667/#message430619
/*
AddEventHandler('sale', 'OnBeforeSaleOrderSetField', 'OnBeforeBasketUpdateAfterCheckHandle');
function OnBeforeBasketUpdateAfterCheckHandle($id, $item)
{
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log.txt', print_r(func_get_args(), true));
	/*
	$discountMode = \Bitrix\Sale\Compatible\DiscountCompatibility::MODE_CLIENT;
	$discountConfig = array(
	   'SITE_ID'  => SITE_DEFAULT,
	   'CURRENCY' => $fields['CURRENCY']
	);
	\Bitrix\Sale\Compatible\DiscountCompatibility::reInit($discountMode, $discountConfig);
	* /
}
*/