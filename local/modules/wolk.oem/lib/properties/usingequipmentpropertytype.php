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

	public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName) {
        $settings = \CIBlockPropertyElementList::PrepareSettings($arProperty);
        if($settings["size"] > 1)
            $size = ' size="'.$settings["size"].'"';
        else
            $size = '';

        if($settings["width"] > 0)
            $width = ' style="width:'.$settings["width"].'px"';
        else
            $width = '';

        $bWasSelect = false;
        $options = \CIBlockPropertyElementList::GetOptionsHtml($arProperty, array($value["VALUE"]), $bWasSelect);

        $html = '<select data-test="test" name="'.$strHTMLControlName["VALUE"].'"'.$size.$width.'>';
        if($arProperty["IS_REQUIRED"] != "Y")
            $html .= '<option value=""'.(!$bWasSelect? ' selected': '').'>'.GetMessage("IBLOCK_PROP_ELEMENT_LIST_NO_VALUE").'</option>';
        $html .= $options;
        $html .= '</select>';
		ob_start(); ?>
		<input type="text" value="<?=$value['DESCRIPTION']?>" name="<?=$strHTMLControlName['DESCRIPTION']?>">
		<?
		$html .= ob_get_clean();

		return $html;
	}
}