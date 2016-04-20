<?php

use Bitrix\Main\Application;

include($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$context = Application::getInstance()->getContext();
$request = $context->getRequest();

/*
 * Данные запроса.
 */
$action = (string) $request->get('action');

switch ($action) {
	
	/*
	 * Скачивание изображения скетча.
	 */
	case 'sketch-download':
		$id = (int) $request->get('ID');
		
		$oemorder = new Wolk\OEM\Order($id);
		$oemorder->showSketchJPG();
		exit();
		break;
}