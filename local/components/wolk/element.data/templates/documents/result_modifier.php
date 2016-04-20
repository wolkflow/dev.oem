<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;


Loader::includeModule('iblock');

// Документы.
$arResult['DOCUMENTS'] = [];

$locationID = (int) $arResult['ELEMENT']['PROPERTIES']['LOCATION']['VALUE'];
if ($locationID > 0) {
	$code = 'DOCS_' . strtoupper(Application::getInstance()->getContext()->getLanguage());
	$prop = CIBlockElement::getByID($locationID)->getNextElement()->getProperty($code);
	
	foreach ($prop['~VALUE'] as $i => $doc) {
		$arResult['DOCUMENTS'] []= [
			'ID'	=> $prop['PROPERTY_VALUE_ID'][$i],
			'TITLE' => $prop['DESCRIPTION'][$i],
			'HTML'  => $doc['TEXT']
		];
	}
}	
	
