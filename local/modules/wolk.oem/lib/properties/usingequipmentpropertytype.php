<?php namespace Wolk\OEM\Properties;

class UsingEquipmentPropertyType extends \CIBlockPropertyElementList
{
	public static function GetUserTypeDescription() {
		return [
			'PROPERTY_TYPE'        => 'E',
			'USER_TYPE'            => 'usingEquipment',
			'DESCRIPTION'          => 'Используемое оборудование',
			'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],

		];
	}

	public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName) {
		$html = parent::GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName);
		ob_start(); ?>
		<input type="text" value="<?=$value['DESCRIPTION']?>" name="<?=$strHTMLControlName['DESCRIPTION']?>">
		<?
		$html .= ob_get_clean();

		return $html;
	}
}