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
    case ('file-upload'):
        if (!empty($_FILES['upload'])) {
            $file = $_FILES['upload'];

            // Временный файл.
            $file['MODULE_ID']   = 'temp';
            $file['description'] = '';

            if ($fid = CFile::SaveFile($file, 'temp')) {
                $path  = CFile::GetPath($fid);
                $isimg = false;
                if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['png', 'jpeg', 'jpg', 'gif'])) {
                    $isimg = true;
                }
                jsonresponse(true, '', ['file' => $fid, 'path' => $path, 'isimg' => $isimg]);
            }
            jsonresponse(false, '');
        }
        break;
    
    
    // Создание сцены изображения.
    case ('render'):
        $objs = (string) $request->get('objs');
        $view = (string) $request->get('view');
        $code = (string) $request->get('code');
        
        $oembasket = new \Wolk\OEM\Basket($code);
        
        $baskets = $oembasket->getList();
        $objects = json_decode($objs, true)['objects'];
        foreach ($objects as &$object) {
            $basket  = $baskets[$object['id']];
            $element = $basket->getElement();
            
            $object['path'] = $element->getModelPath();
        }
        unset($baskets, $element);
        
        $params = $oembasket->getParams();
        $scene = [
            'width'      => $params['WIDTH'],
            'length'     => $params['DEPTH'],
            'type'       => $params['SFORM'],
            'owner_name' => 'Test Stand',
            'objects'    => $objects,
        ];
        
        // Рендер сцены.
        $path = Wolk\OEM\Render::render($code . $view, json_encode($scene), 'out-'.uniqid());
        
        if ($path === false) {
            jsonresponse(false, '');
        }
        $renders = $oembasket->getRenders();
        $renders []= $path;
        $oembasket->setRenders($renders);
        
        jsonresponse(true, '', ['path' => $path]);
        break;
    
        
    // Созхранение продукции в корзину.
    case ('put-basket'):
        $index    = (int)    $request->get('index');
        $pid      = (int)    $request->get('pid');
        $eid      = (int)    $request->get('eid');
        $code     = (string) $request->get('code');
        $type     = (string) $request->get('type');
        $kind     = (string) $request->get('kind');
        $quantity = (float)  $request->get('quantity');
        $params   = (array)  $request->get('params');
        $fields   = (array)  $request->get('fields');
        
        $parameters = [];
        foreach ($params as $key => $value) {
            if (strpos($key, '.') !== false) {
                list($key, $subkey) = explode('.', $key);

                $parameters[$key][$subkey] = $value;
            } else {
                $parameters[$key] = $value;
            }
        }
        
        // Контекст конструктора.
        $context = new Wolk\OEM\Context($eid, $type);
        
        // Корзина.
        $basket = new \Wolk\OEM\Basket($code);
        
        // Сохранение продукции в корзину.
        $item = $basket->put(
            $pid,
            $quantity,
            \Wolk\OEM\Basket::KIND_PRODUCT,
            $parameters,
            $fields
        );
        
        // Обновление данных в корзине.
        $html = gethtmlremote('basket.php');
        
        jsonresponse(true, '', array('html' => $html, 'item' => $item));
        break;

    
    // Изменение количества товара в корзине.
    case ('update-basket'):
        $bid      = (string) $request->get('bid');
        $pid      = (int)    $request->get('pid');
        $eid      = (int)    $request->get('eid');
        $code     = (string) $request->get('code');
        $type     = (string) $request->get('type');
        $quantity = (float)  $request->get('quantity');
        $params   = (array)  $request->get('params');
        $fields   = (array)  $request->get('fields');
        
        $parameters = [];
        foreach ($params as $key => $value) {
            if (strpos($key, '.') !== false) {
                list($key, $subkey) = explode('.', $key);

                $parameters[$key][$subkey] = $value;
            } else {
                $parameters[$key] = $value;
            }
        }

        
        // Контекст конструктора.
        $context = new Wolk\OEM\Context($eid, $type);
        
        // Корзина.
        $basket = new \Wolk\OEM\Basket($code);
        
        // Изменение количества товара в корзине.
        $item = $basket->update($bid, $pid, $quantity, $parameters, $fields);
        
        // Обновление данных в корзине.
        $html = gethtmlremote('basket.php');
        
        jsonresponse(true, '', array('html' => $html, 'item' => $item));
        break;
        
    
    // Удаление продукции их корзины.
    case ('remove-basket'):
        $bid      = (string) $request->get('bid');
        $eid      = (int)    $request->get('eid');
        $code     = (string) $request->get('code');
        $type     = (string) $request->get('type');
        
        // Контекст конструктора.
        $context = new Wolk\OEM\Context($eid, $type);
        
        // Корзина.
        $basket = new \Wolk\OEM\Basket($code);
        
        // Удаление продукции их корзины.
        $item = $basket->remove($bid);
        
        // Обновление данных в корзине.
        $html = gethtmlremote('basket.php');
        
        jsonresponse(true, '', array('html' => $html, 'item' => $item));
        break;
        
    
    default:
		jsonresponse(false, Loc::getMessage('GL_ERROR_UNKNOWN'));
		break;
}