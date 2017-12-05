<?php

set_time_limit(0);


define('NO_KEEP_STATISTIC', true);
define('PULL_AJAX_INIT', true);
define('PUBLIC_AJAX_MODE', true);
define('NO_AGENT_STATISTIC', true);
define('NO_AGENT_CHECK', true);
define('DisableEventsCheck', true);

// Не пересчитывать заказ при создании из формы.
define('NO_ORDER_RECALC', 'Y');

// Директория для ajax-скриптов.
define('DIR_REMOTE', $_SERVER['DOCUMENT_ROOT'] . '/remote/include/');




use Bitrix\Main\Localization\Loc;

require ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');


/**
 * Ответ в формате JSON.
 */
function jsonresponse($status, $message = '', $data = null, $console = '', $type = 'json', $exit = true)
{
	$result = array(
		'status'  => (bool)   $status,
		'message' => (string) $message,
		'data' 	  => (array)  $data,
		'console' => (string) $console,
	);
	
	header('Content-Type: application/json');
	echo json_encode($result);
	
	if ($exit) {
		exit();
	}
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


global $DB, $USER, $APPLICATION;

// Запрос.
$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

// Действие.
$token = (string) $request->get('TOKEN');

// Язык.
$lang = \Bitrix\Main\Context::getCurrent()->getLanguage();

// Загрузка языковых файлов.
IncludeFileLangFile(__FILE__);



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
        $code = (string) $request->get('code');
        
        $basket = new \Wolk\OEM\Basket($code);
        
        $baskets = $basket->getList(true);
        $objects = json_decode($objs, true)['objects'];

        foreach ($objects as &$object) {
			if (!is_object($baskets[$object['id']])) {
				continue;
			}
            $element = $baskets[$object['id']]->getElement();
            
            $object['path'] = $element->getModelPath();
        }
        unset($baskets, $element);
		
		// Надпись на фризовой панели для рендера.
		$fascia = reset($basket->getFasciaBaskets());
        
        $params = $basket->getParams();
        $scene = [
            'width'      => $params['WIDTH'],
            'length'     => $params['DEPTH'],
            'type'       => $params['SFORM'],
            'owner_name' => (is_object($fascia)) ? ($fascia->getParam('TEXT')) : (''),
            'objects'    => $objects,
        ];
        
        $distance = 1;
        if ($params['WIDTH'] <= 3 && $params['DEPTH'] <= 3) {
            $distance = 2;
        }
        
        // Угол поворота.
        $rotates = [360, 45, 90, 105];
		
		// Рендеры сцены.
		$renders = [];
		foreach ($rotates as $rotate) {
			$renders []= Wolk\OEM\Render::render($code . '-' . $view, json_encode($scene), 'out-'.uniqid(), 1024, 768, $distance, $rotate);
        }
       
	    if (empty($renders)) {
			jsonresponse(false, '');
		}
		$path = reset($renders);
        
		
		// Сохранение в корзину.
        $basket->setRenders($renders);
		
		// Сохранение корзины во временное хранилище.
		$storage = Wolk\OEM\TempRenderStorage::push($basket->getData());
		
		// Печать
		$print = new Wolk\OEM\Prints\Prerender($storage->getID(), $code, $lang);
		$print->make();
		
		// Путь к файлу PDF.
		$file = $print->getPath();
		
		
        jsonresponse(true, '', ['path' => $path, 'file' => $file]);
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
        $context = new Wolk\OEM\Context($eid, $type, $lang);
        
        // Корзина.
        $basket = new \Wolk\OEM\Basket($code);
		$basket->setContext($context);
        
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

	
	
    // Изменение корзины.
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
        $context = new Wolk\OEM\Context($eid, $type, $lang);
        
        // Корзина.
        $basket = new \Wolk\OEM\Basket($code);
		$basket->setContext($context);
        
        // Изменение количества товара в корзине.
        $item = $basket->update(
			$bid, 
			['pid' => $pid, 'quantity' => $quantity, 'params' => $parameters, 'filed' => $fields]
		);
        
        // Обновление данных в корзине.
        $html = gethtmlremote('basket.php');
        
        jsonresponse(true, '', array('html' => $html, 'item' => $item));
        break;
		
	
	
	// Изменение количества товара в корзине.
    case ('update-basket-quantity'):
		$bid      = (string) $request->get('bid');
        $pid      = (int)    $request->get('pid');
        $eid      = (int)    $request->get('eid');
        $code     = (string) $request->get('code');
        $type     = (string) $request->get('type');
        $quantity = (float)  $request->get('quantity');
		$template = (string) $request->get('template');
		
		// Контекст конструктора.
        $context = new Wolk\OEM\Context($eid, $type, $lang);
		
		// Корзина.
        $basket = new \Wolk\OEM\Basket($code);
		$basket->setContext($context);
        
        // Изменение количества товара в корзине.
        $data = $basket->update($bid, ['quantity' => $quantity]);
		
		// Обновление данных в корзине.
		switch ($template) {
			case ('order'):
				$html = gethtmlremote('wizard.order.summary.php');
				break;
			default:
				$html = gethtmlremote('basket.php');
				break;
		}
		$event = new \Wolk\OEM\Event($eid);
		
		$currency = $event->getCurrencyStandsContext($context);
		$basitem  = new \Wolk\OEM\BasketItem($code, $data['id'], $data);
		$element  = $basitem->getElement();
		if (!empty($element)) {
			if (!$basitem->isIncluded()) {
				$basitem->setPrice($element->getContextPrice($context));
			}
		}
		$item = [
			'bid'	      => $basitem->getID(),
			'pid'	      => $basitem->getProductID(),
			'quantity'    => $basitem->getQuantity(),
			'price'       => $basitem->getPrice(),
			'cost'        => $basitem->getCost(),
			'cost-format' => FormatCurrency($basitem->getCost(), $currency),
		];
        
        jsonresponse(true, '', array('html' => $html, 'item' => $item, 'template' => $template));
		break;
		
	
	
	// Обновление параметров товара в корзине.
    case ('update-basket-property'):
        $bid      = (string) $request->get('bid');
        $eid      = (int)    $request->get('eid');
        $code     = (string) $request->get('code');
        $type     = (string) $request->get('type');
		$quantity = (float)  $request->get('quantity');
        $params   = (array)  $request->get('params');
        
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
        $context = new Wolk\OEM\Context($eid, $type, $lang);
        
        // Корзина.
        $basket = new \Wolk\OEM\Basket($code);
        $basket->setContext($context);
		
        // Изменение количества товара в корзине.
        $item = $basket->updateParams($bid, $parameters);
        
        // Обновление данных в корзине.
        $html = gethtmlremote('basket.php');
        
        jsonresponse(true, '', array('html' => $html, 'item' => $item));
        break;
    
	
	// Обновление скетча.
	case ('update-basket-sketch'):
		$code     = (string) $request->get('code');
        $scene    = (string) $request->get('SKETCH_SCENE');
        $image    = (string) $request->get('SKETCH_IMAGE');
        $comments = (string) $request->get('COMMENTS');
        
		// Корзина.
        $basket = new \Wolk\OEM\Basket($code);
		
        $basket->setSketch([
            'SKETCH_SCENE' => $scene,
            'SKETCH_IMAGE' => $image
        ]);
        $basket->setParam('COMMENTS', $comments);
		
		jsonresponse(true);
		break;
	
	
    
    // Удаление продукции их корзины.
    case ('remove-basket'):
        $bid      = (string) $request->get('bid');
        $eid      = (int)    $request->get('eid');
        $code     = (string) $request->get('code');
        $type     = (string) $request->get('type');
        
        // Контекст конструктора.
        $context = new Wolk\OEM\Context($eid, $type, $lang);
        
        // Корзина.
        $basket = new \Wolk\OEM\Basket($code);
        
        // Удаление продукции их корзины.
        $item = $basket->remove($bid);
        
        // Обновление данных в корзине.
        $html = gethtmlremote('basket.php');
        
        jsonresponse(true, '', array('html' => $html, 'item' => $item));
        break;
    
    
	
	// Генерация скетча и рендеров в PDF.
	case ('order-render-print'):
		$oid = (int) $request->get('oid');
		
		if (!\Bitrix\Main\Loader::includeModule('wolk.oem')) {
			jsonresponse(false, 'Ошибка выполнения: модуль не установлен');
		}
		
		$order = new Wolk\OEM\Order($oid);
		$print = new Wolk\OEM\Prints\Render($order->getID(), $order->getLanguage());
		
		// Печать заказа.
		$result = $print->make();
		
		if ($result !== 0) {
			jsonresponse(false, 'Ошибка создания PDF');
		}		
		jsonresponse(true, '', ['link' => $print->getPathPDF()]);
		
		break;
	
	
	
    // Создание заказа.
    case ('place-order'):
		$oid      = (int)    $request->get('oid');
        $eid      = (int)    $request->get('eid');
        $code     = (string) $request->get('code');
        $type     = (string) $request->get('type');
        
        // Контекст конструктора.
        $context = new Wolk\OEM\Context($eid, $type, $lang);
    
        // Предварительное действие.
        $preaction = $request->get('preaction');
        
        // Пользователь должен быть авторизован.
        if (!$USER->IsAuthorized()) {
            switch ($preaction) {
                
                // Авторизация пользователя.
                case ('login'):
                    $data = (array) $request->get('AUTH');
                    
                    $result = $USER->login($data['LOGIN'], $data['PASSWORD']);
                    if ($result !== true) {
                        jsonresponse(false, strip_tags($result['MESSAGE']));
                    }
                    break;
                
                // Регистрация пользователя.
                case ('register'):
                    $data = (array) $request->get('AUTH');
                    
                    // Обработка полей.
                    $data['LOGIN'] = $data['EMAIL'];
                    
                    $bxuser = new CUser();
                    $userid = $bxuser->add($data);
                    if (!$userid) {
                        jsonresponse(false, $bxuser->LAST_ERROR);
                    }
                    $USER->authorize($userid);
                    break;
                    
                default:
                    jsonresponse(false, Loc::getMessage('GL_NOT_AUTHORIZED'));
                    break;
            }
        }
        
        // Корзина.
        $basket = new \Wolk\OEM\Basket($code);
        
        $basket->setParam('STANDNUM', (string) $request->get('STANDNUM'));
        $basket->setParam('PAVILION', (string) $request->get('PAVILION'));
        
        // Создание заказа.
        try {
            $oid = $basket->order($context);
			
			// Очистка сессии.
			$_SESSION[\Wolk\OEM\Basket::SESSCODE_EVENT][strtoupper($code)] = null;
        } catch (Exceptino $e) {
            jsonresponse(false, $e->getMessag());
        }
		jsonresponse(true, '', null, '', 'json', false);
		
		session_write_close();
		fastcgi_finish_request();
		
		// Создание рендеров.
		$order = new Wolk\OEM\Order($oid);
		$order->makeRenders(true);
        break;
		
	
	
	// Восстановление пароля.
    case ('restore-password'):
        $email = (string) $request->get('EMAIL');
        
        if (empty($email)) {
            jsonresponse(false, '', ['error' => 'email-is-empty']);
        }
        
        $password = randString(8, array(
            'abcdefghijklnmopqrstuvwxyz',
            'ABCDEFGHIJKLNMOPQRSTUVWXYZ',
            '0123456789',
        ));
        
        $res = \CUser::getList(($b = "ID"), ($o = "ASC"), array('=EMAIL' => $email));
        
		if (!($user = $res->fetch())) {
			jsonresponse(false, '', ['error' => 'email-not-found']);
		}
		
		// Сохранение пароля пользователя.
        $cuser  = new \CUser();
        $result = $cuser->Update($user['ID'], array('PASSWORD' => $password));
        
		if (!$result) {
			jsonresponse(false, '', ['error' => 'password-not-change']);
		}
		
		// Поля.
		$fields = $user;
		$fields['PASSWORD'] = $password;
		
        // Отправка нового пароля.		
		$html = $APPLICATION->IncludeComponent(
			'wolk:mail.user',
			'restore-password',
			['ID' => $fields['ID'], 'FIELDS' => $fields]
		);
		
		// Отправка сообщения о подтвеерждении регистрации.
		$event = new \CEvent();
		$event->Send('RESTORE_PASSWORD', SITE_DEFAULT, ['EMAIL' => $fields['EMAIL'], 'HTML' => $html, 'THEME' => getMessage('MESSAGE_THEME_RESTORE_PASSWORD')]);
		
		jsonresponse(true);
        break;
	
	
	
	// Просмотр заказа.
	case ('show-order'):
		jsonresponse(true, '', ['html' => gethtmlremote('order.show.php')]);
		break;
    
    
	
    default:
		jsonresponse(false, Loc::getMessage('GL_ERROR_UNKNOWN'));
		break;
}