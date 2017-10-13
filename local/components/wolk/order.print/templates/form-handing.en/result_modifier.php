<?php

$baskets = $arResult['OEMORDER']->getFormHandingBaskets();

$arResult['FORM'] = null;

if (!empty($baskets))  {
	foreach ($baskets as $basket) {
		$param = json_decode($basket['PROPS']['PARAMS']['VALUE'], true);
		if (!empty($param[\Wolk\OEM\Basket::PARAM_FORM_HANGING_STRUCTURE])) {
			$arResult['FORM'] = $param[\Wolk\OEM\Basket::PARAM_FORM_HANGING_STRUCTURE];
			break;
		}
	}
}