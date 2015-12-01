<?

use Bitrix\Iblock\PropertyEnumerationTable;
use Wolk\OEM\EventsStandsSizesTable;

function BXIBlockAfterSave($arFields)
{
//	$standsPropertyId = 12;
//	foreach($arFields['PROPERTY_VALUES'][$standsPropertyId] as $valueId => $value) {
//		if(is_numeric($valueId)) {
//			$row = EventsStandsSizesTable::getRow([
//				'filter' =>
//					[
//						'ENUM_ID' => $valueId
//					]
//			]);
//			if($row && ($row['WIDTH'] != $value['VALUE']['WIDTH'] || $row['DEPTH'] != $value['VALUE']['DEPTH'])) {
//				EventsStandsSizesTable::update($row['ID'], [
//					'WIDTH' => $value['VALUE']['WIDTH'],
//					'DEPTH' => $value['VALUE']['DEPTH']
//				]);
//			}
//		} else {
//			$row = \Bitrix\Main\Application::getConnection()
//				->query('select ID from b_iblock_element_property order by ID desc limit 1')
//				->fetch();
//			EventsStandsSizesTable::add([
//				'ENUM_ID' => $row['ID'],
//				'WIDTH'   => $value['VALUE']['WIDTH'],
//				'DEPTH'   => $value['VALUE']['DEPTH']
//			]);
//		}
//	}
}