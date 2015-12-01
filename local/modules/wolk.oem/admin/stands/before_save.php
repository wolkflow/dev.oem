<?
function BXIBlockAfterSave($arFields)
{
    $setParams = [
        "TYPE"    => 1,
        "SET_ID"  => 0,
        "ITEM_ID" => $arFields['ID']
    ];

    foreach($arFields['PROPERTY_VALUES'][$eqPropertyId])
}