<?php

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
}

Loader::registerAutoLoadClasses(
    null,
    [
        'Helper' => '/local/php_interface/libs/helper.php'
    ]
);

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';