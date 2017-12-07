<?php

$baskets = $arResult['OEMORDER']->getFormHandingBaskets();

$arResult['FORMS'] = [];

if (!empty($baskets))  {
	foreach ($baskets as $basket) {
		$param = json_decode($basket['PROPS']['PARAMS']['VALUE'], true);
		if (!empty($param[\Wolk\OEM\Basket::PARAM_FORM_HANGING_STRUCTURE])) {
			$arResult['FORMS'][$basket['ID']] = $param[\Wolk\OEM\Basket::PARAM_FORM_HANGING_STRUCTURE];
		}
	}
}

if (!empty($arParams['BID'])) {
	$arResult['FORM'] = $arResult['FORMS'][$arParams['BID']];
} else {
	$arResult['FORM'] = reset($arResult['FORMS']);
}