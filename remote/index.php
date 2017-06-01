<?php

define('NO_KEEP_STATISTIC', true);
define('PULL_AJAX_INIT', true);
define('PUBLIC_AJAX_MODE', true);
define('NO_AGENT_STATISTIC', true);
define('NO_AGENT_CHECK', true);
define('DisableEventsCheck', true);

use Bitrix\Main\Localization\Loc;

require ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

// Директория для ajax-скриптов.
define ('DIR_REMOTE', $_SERVER['DOCUMENT_ROOT'] . '/remote/include/');

IncludeFileLangFile(__FILE__);

/**
 * Ответ в формате JSON.
 */
function jsonresponse($status, $message = '', $data = null, $console = '', $type = 'json')
{
	$result = array(
		'status'  => (bool)   $status,
		'message' => (string) $message,
		'data' 	  => (array)  $data,
		'console' => (string) $console,
	);
	
	header('Content-Type: application/json');
	echo json_encode($result);
	exit();
}

/** 
 * Получение HTML.
 */
function gethtmlremote($file)
{
	ob_start();
	include (DIR_REMOTE . $file);
	return ob_get_clean();
}


Bitrix\Main\Loader::includeModule('wolk.core');
Bitrix\Main\Loader::includeModule('wolk.oem');
Bitrix\Main\Loader::includeModule('iblock');
Bitrix\Main\Loader::includeModule('catalog');
Bitrix\Main\Loader::includeModule('sale');


global $USER;

// Запрос.
$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

// Действие.
$token = (string) $request->get('TOKEN');



// Обработка действий.
switch ($action) {
    
    
    // Загрузка изображения.
    case ('image-upload-tmp'):
        break;
        
        
    // Созхранение продукции в корзину.
    case ('put-basket'):
        $index    = (int)    $request->get('index');
        $pid      = (int)    $request->get('pid');
        $eid      = (int)    $request->get('eid');
        $event    = (string) $request->get('event');
        $type     = (string) $request->get('type');
        $kind     = (string) $request->get('kind');
        $quantity = (float)  $request->get('quantity');
        $params   = (array)  $request->get('params');
        
        // Контекст конструктора.
        $context = new Wolk\OEM\Context($eid, $type);
        
        // Корзина.
        $basket = new \Wolk\OEM\Basket($event);
        
        // Сохранение продукции в корзину.
        $basket->put(
            $pid,
            $quantity,
            $kind,
            $params,
            $context
        );
        
        // Обновление данных в корзине.
        $html = getdataremote('basket.php');
        
        jsonresponse(true, '', array('html' => $html));
        break;

        
    
    default:
		jsonresponse(false, Loc::getMessage('GL_ERROR_UNKNOWN'));
		break;
}