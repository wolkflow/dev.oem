<?php

use Bitrix\Highloadblock\HighloadBlockTable;

$arResult['COLORS'] = [];

if (\Bitrix\Main\Loader::includeModule('highloadblock')) {
	$hlblock = HighloadBlockTable::getByID(COLORS_ENTITY_ID)->fetch();
	$entity  = HighloadBlockTable::compileEntity($hlblock);
	$class   = $entity->getDataClass();
	$result  = $class::getList(['order' => ['UF_NUM' => 'ASC']]);
	
	while ($color = $result->fetch()) {
		$arResult['COLORS'][$color['UF_XML_ID']] = $color;
	}
	unset($hlblock, $entity, $class, $result, $color);
}