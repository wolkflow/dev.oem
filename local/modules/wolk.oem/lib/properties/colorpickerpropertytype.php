<?php namespace Wolk\OEM\Properties;

class ColorpickerPropertyType
{
	public static function GetUserTypeDescription() {
		return array(
			'PROPERTY_TYPE'        => 'S',
			'USER_TYPE'            => 'colorpicker',
			'DESCRIPTION'          => 'Выбор цвета',
			'GetPropertyFieldHtml' => array(__CLASS__, 'GetPropertyFieldHtml')
		);
	}

	public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName) {
		global $APPLICATION;
		\CJSCore::init('jquery');
		$replacedName = str_replace(array('[', ']'), '_', $strHTMLControlName['VALUE']);
		ob_start();
		?>
		<input id="<?=$replacedName?>" type="text" name="<?=$strHTMLControlName['VALUE']?>" value="<?=$value['VALUE']?>">
		<?
		$APPLICATION->IncludeComponent("bitrix:main.colorpicker", "", Array(
			"SHOW_BUTTON" => "Y",
			"ID" => $replacedName,
			"NAME" => "Выбор цвета",
			"ONSELECT" => "function(color) {
				$('input#{$replacedName}').val(color)
			}"
		));
		return ob_get_clean();
	}
}