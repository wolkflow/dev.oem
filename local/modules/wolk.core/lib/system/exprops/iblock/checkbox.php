<?php

namespace Wolk\Core\System\Exprops\IBlock;

class CheckBox
{

    public function GetUserTypeDescription()
    {
        return array(
            'PROPERTY_TYPE'  => 'S',
            'USER_TYPE'      => 'checkbox',
            'DESCRIPTION'    => 'Чекбокс (да / нет)',
            'CheckFields'           => array(__CLASS__, 'CheckFields'),
            'GetLength'             => array(__CLASS__, 'GetLength'),
            'GetPublicViewHTML'     => array(__CLASS__, 'GetFieldView'),
            'GetPropertyFieldHtml'  => array(__CLASS__, 'GetEditField'),
            'GetPublicEditHTML'     => array(__CLASS__, 'GetEditField'),
			'GetAdminListViewHTML'  => array(__CLASS__, 'GetAdminListViewHTML'),
        );
    }
	

    /**
     * Проверка корректности значения свойства.
     *
     * @param $property
     * @param $value
     * @return array
     */
    public function CheckFields($property, $value)
    {
        return array();
    }

	
    /**
     * Фактическую длину значения свойства.
	 *
     * @param $property
     * @param $value
     * @return int
     */
    public function GetLength($property, $value)
    {
        return strlen($value['VALUE']);
    }
	
	
    /**
     * Вернуть HTML отображения элемента управления для редактирования значений свойства в административной части
     * @param $property
     * @param $value
     * @param $htmlElement
     * @return string
     */
    public function GetEditField($property, $value, $htmlElement)
    {
		$html  = '<input type="hidden" name="' . $htmlElement['VALUE'] . '" value="N" />';
		$html .= '<input type="checkbox" name="' . $htmlElement['VALUE'] . '" value="Y"' . ($value['VALUE'] == 'Y' ? ' checked="checked"' : '') . ' />';
		$html .= (isset($arProperty["WITH_DESCRIPTION"]) && $arProperty["WITH_DESCRIPTION"] == 'Y')
					? ('&nbsp;<input type="text" size="20" name="'.$htmlElement["DESCRIPTION"].'" value="'.htmlspecialchars($value["DESCRIPTION"]).'">')
					: ('');
		
		return $html;
    }

	
	/**
     * Вернуть HTML отображения значения свойства в списке элементов административной части
     * @param $property
     * @param $value
     * @param $strHTMLControlName
     * @return mixed
     */
	public function GetAdminListViewHTML($property, $value, $strHTMLControlName)
    {
        return (($value['VALUE'] == 'Y') ? ('Да') : ('Нет'));
    }
	
	
    /**
     * Вернуть HTML отображения значения свойства в списке элементов административной части
     * @param $property
     * @param $value
     * @param $htmlElement
     * @return mixed
     */
    public function GetFieldView($property, $value, $htmlElement)
    {
        return (($value['VALUE'] == 'Y') ? ('Да') : ('Нет'));
    }
}

