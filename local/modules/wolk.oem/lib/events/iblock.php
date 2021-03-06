<?php 

namespace Wolk\OEM\Events;

use Bitrix\Main\Localization\Loc;
use Wolk\OEM\Render;
use Wolk\OEM\Properties\ColorpickerPropertyType;
use Wolk\OEM\Properties\StandsWithEquipmentPropertyType;
use Wolk\OEM\Properties\UsingEquipmentPropertyType;

class Iblock
{
    public function colorPickerPropertyType()
    {
        return ColorpickerPropertyType::getUserTypeDescription();
    }

    public function standsWithEquipmentPropertyType()
    {
        return StandsWithEquipmentPropertyType::GetUserTypeDescription();
    }

    public function UsingEquipmentPropertyType()
    {
        return UsingEquipmentPropertyType::GetUserTypeDescription();
    }
	
	
	public function checkSectionDepth($fields)
	{
		global $APPLICATION, $USER;
		
		// Логирование изменения продукции.
		if ($fields['IBLOCK_ID'] == IBLOCK_EVENTS_ID) {
			$alarm = '';
			if (empty($fields['PROPERTY_VALUES'][ELEMENT_EVENTS_PROPERTY_PRODUCTS_STANDARD_ID])) {
				$alarm .= ' ALARM (standard)';
			}
			if (empty($fields['PROPERTY_VALUES'][ELEMENT_EVENTS_PROPERTY_PRODUCTS_INDIVIDUAL_ID])) {
				$alarm .= ' ALARM (individual)';
			}
			
			file_put_contents(
				$_SERVER['DOCUMENT_ROOT'].'/../events.log', 
				date('d.m.Y H:i:s') . ' | User ' . $USER->getID() . ': ' . $alarm . PHP_EOL . print_r($fields, true) . PHP_EOL, 
				FILE_APPEND
			);
		}
		
		if ($fields['IBLOCK_ID'] == IBLOCK_PRODUCTS_ID) {
			// Каждый привязанный раздел должен находится на 3-м уровне.
			foreach ($fields['IBLOCK_SECTION'] as $sid) {
				$sections = \Wolk\Core\Helpers\IBlockSection::GetSectionPathByParentID($sid);
				
				if (count($sections) != 3) {
					$APPLICATION->throwException(Loc::getMessage('ERROR_PRODUCT_SECTIONS_DEPTH'));
					return false;
				}
			}			
		}
	}
	
	
	public function setModelRender($fields)
	{
		// Конвертирование.
		if ($fields['IBLOCK_ID'] == IBLOCK_PRODUCTS_ID && $fields['RESULT'] == 'ID') {
			$file = reset($fields['PROPERTY_VALUES'][SECTION_PRODUCTS_PROPERTY_MODEL_ID]);
			$file = $file['VALUE'];
			
			if (empty($file['tmp_name'])) {
				$render = Render::remove($fields['ID']);
			} else {
				$render = Render::convert($fields['ID'], $file);
			}
		}
	}
	
	
    public function saveProductsSet($arFields)
    {
        if ($arFields['IBLOCK_ID'] == STANDS_OFFERS_IBLOCK_ID) {
            $setParams = [
                "TYPE"     => \CCatalogProductSet::TYPE_SET,
                "SET_ID"   => 0,
                "QUANTITY" => 1,
                "ITEM_ID"  => $arFields['ID']
            ];

            foreach ($arFields['PROPERTY_VALUES'][EQUIPMENT_PROPERTY_ID] as $value) {
                if ($value['VALUE']) {
                    $setParams['ITEMS'][] = [
                        "ITEM_ID"  => $value['VALUE'],
                        "QUANTITY" => $value['DESCRIPTION']
                    ];
                }
            }
            $arSets = \CCatalogProductSet::getAllSetsByProduct($arFields['ID'], \CCatalogProductSet::TYPE_SET);
            if (!empty($arSets)) {
                foreach ($arSets as $id => $arSet) {
                    if (!\CCatalogProductSet::update($id, $setParams)) {
                        global $APPLICATION;
                        echo $APPLICATION->GetException()->GetString();
                        die;
                    }
                }
            } else {
                \CCatalogProductSet::add($setParams);
            }
        }
    }
}