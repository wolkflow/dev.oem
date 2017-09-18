<?php

namespace Wolk\OEM\Properties;


class UsingEquipmentPropertyType extends \CIBlockPropertyElementList
{
    
	public static function GetUserTypeDescription()
    {
		return [
			'PROPERTY_TYPE'        => 'E',
			'USER_TYPE'            => 'usingEquipment',
			'DESCRIPTION'          => 'Используемое оборудование',
			'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
		];
	}
	
    
	public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $settings = \CIBlockPropertyElementList::PrepareSettings($arProperty);
        
        if ($settings["size"] > 1) {
            $size = ' size="'.$settings["size"].'"';
        } else {
            $size = '';
        }
        
        if ($settings['width'] > 0) {
            $width = ' style="width:'.$settings["width"].'px"';
        } else {
            $width = '';
        }
        
        $selected = false;
        
        $options = \CIBlockPropertyElementList::GetOptionsHtml($property, array($value["VALUE"]), $selected);

        $html = '<select style="margin: 15px 0 5px 0;" data-test="test" name="'.$strHTMLControlName["VALUE"].'"'. $size . $width . '>';
        if ($property['IS_REQUIRED'] != 'Y') {
            $html .= '<option value=""' . (!$selected? ' selected': '') . '>' . GetMessage("IBLOCK_PROP_ELEMENT_LIST_NO_VALUE") . '</option>';
        }
        $html .= $options;
        $html .= '</select>';
        $html .= '<input type="text" value="' . $value['DESCRIPTION'] . '" name="' . $strHTMLControlName['DESCRIPTION'] . '" /><br/>';
        
		return $html;
	}
}
