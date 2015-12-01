<?php namespace Wolk\OEM\Events;

use Wolk\OEM\Properties\ColorpickerPropertyType;
use Wolk\OEM\Properties\StandsWithEquipmentPropertyType;
use Wolk\OEM\Properties\UsingEquipmentPropertyType;

class Iblock
{
	public function colorPickerPropertyType() {
		return ColorpickerPropertyType::getUserTypeDescription();
	}

	public function standsWithEquipmentPropertyType() {
		return StandsWithEquipmentPropertyType::GetUserTypeDescription();
	}

	public function UsingEquipmentPropertyType() {
		return UsingEquipmentPropertyType::GetUserTypeDescription();
	}
}