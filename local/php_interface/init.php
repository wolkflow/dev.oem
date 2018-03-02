<?php

// Глобальный js-переменные.
$GLOBALS['JSVARS'] = array(
    'LANGS' => [], 
    'LANG'  => \Bitrix\Main\Context::getCurrent()->getLanguage()
);

// Константы.
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;


// Константы.
include($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/constants.php');

// Специфические обработчики (контент).
include($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/peculiarities.php');

// Функции.
include($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/functions.php');


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
    
    $em->addEventHandler('sale', 'OnSaleOrderSaved', ['\Wolk\OEM\Events\Sale', 'OnSaleOrderSaved']);
}

// Регистрация классов с автозагрузкой.
Loader::registerAutoLoadClasses(null, ['Helper' => '/local/php_interface/libs/helper.php']);

// require_once ($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');






// ТЕСТ
/*
$em->addEventHandler('fileman', 'OnBeforeHTMLEditorScriptRuns', 'OnIncludeHTMLEditorScriptRunsHandler');   

function OnIncludeHTMLEditorScriptRunsHandler()
{
	
	/*
	// Регистрируем расширение, с подключением основного скрипта и подключением языкового файла.      
	CJSCore::RegisterExt('sotbit_htmleditor_additional', array(
		'js' => '/bitrix/tools/'.self::MODULE_ID.'/js/script.js',    
		'lang' => '/bitrix/modules/'.self::MODULE_ID.'/lang/'.LANGUAGE_ID.'/install/tools/'.self::MODULE_ID.'/js/script.php',    
	));       
    
   // Инициализируем наше расширение.     
   CJSCore::Init(array('sotbit_htmleditor_additional'));
  
}
 */











