<?php namespace Wolk\OEM\Events;

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
                foreach($arSets as $id => $arSet) {
                    if(!\CCatalogProductSet::update($id, $setParams)) {
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