<?
define('PUBLIC_AJAX_MODE', true);
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;
use Bitrix\Main\ArgumentException;
global $APPLICATION;
Loader::includeModule('iblock');

CBitrixComponent::includeComponentClass('wolk:event.detail');
$component = new EventDetailComponent;

$actions = [
    'getServices',
    'placeOrder',
    'getOrder',
    'upload'
];

try {
    if (!Context::getCurrent()->getRequest()->isPost()) {
        throw new Exception('wrong request');
        #die('wrong request');
    }

    if (!array_key_exists('action', $_POST) || !in_array($_POST['action'], $actions)) {
        throw new Exception('wrong action');
        #die('wrong action');
    }

    switch ($_POST['action']) {
		
        case 'getServices':
            $eventId = intval($_POST['event']);
            $result = $component->getServices($eventId);
            break;
			
        case 'placeOrder':
            Loader::includeModule('sale');
            $params = [];
            if (array_key_exists('selectedParams', $_POST)) {
                $params = \Bitrix\Main\Web\Json::decode($_POST['selectedParams']);
                $params['EVENT_ID'] = $_POST['orderParams']['eventId'];
            }
            $component->arParams = $params;
            $result = $component->addToCart();
            break;
			
        case 'upload':
            $arFields = $_FILES;
            $result = [
                'files' => []
            ];
            $error = CFile::CheckFile($arFields['FILE'], 0, false, CFile::GetImageExtensions() . ',eps,ai,cdr');
            if (
                !$error &&
                CFile::SaveForDB($arFields, 'FILE', 'sale')
            ) {
                $file = CFIle::GetFileArray($arFields['FILE']);
                $result['files'][] = [
                    'name' => $file['FILE_NAME'],
                    'size' => $file['FILE_SIZE'],
                    'url'  => $file['SRC'],
                    'id'   => $arFields['FILE']
                ];
            } else {
                CHTTP::SetStatus('422 Unprocessable Entity');
                die($error);
            }
            break;
			
        case 'getOrder':
            if ($order = $component->getOrder($_POST['orderId'])) {
                $result = $order;
            } else {
                $result = 'Order not found';
                CHTTP::SetStatus('422 Unprocessable Entity');
            }
    }
} catch (Exception $e) {
    $result = $e->getMessage();
    CHTTP::SetStatus('422 Unprocessable Entity: '.$result.'[file: '.$e->getFile().', line: '.$e->getLine().']');
}

$response = Json::encode($result, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json");
die($response);