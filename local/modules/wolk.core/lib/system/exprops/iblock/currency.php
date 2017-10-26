<?php

namespace Wolk\Core\System\Exprops\IBlock;

class Currency
{
	static $currencies = array();
	

    public function GetUserTypeDescription()
    {
        // Массив с валютами.
        self::getCurrencies();

        return array(
            'PROPERTY_TYPE'         => 'S',
            'USER_TYPE'             => 'currency',
            'DESCRIPTION'           => 'Привязка к валюте',
            'CheckFields'           => array(__CLASS__, 'CheckFields'),
            'GetLength'             => array(__CLASS__, 'GetLength'),
            'GetAdminListViewHTML'  => array(__CLASS__, 'GetFieldView'),
            'GetPublicViewHTML'     => array(__CLASS__, 'GetFieldView'),
            'GetPropertyFieldHtml'  => array(__CLASS__, 'GetEditField'),
            'GetPublicEditHTML'     => array(__CLASS__, 'GetEditField'),
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
    public function GetEditField($property, $value, $element)
    {
		self::getCurrencies();
		
        $html  = '<select name="' . $element['VALUE'] . '" style="margin-bottom:10px;">';
        $html .= '<option value="">- не выбрано -</option>';
		foreach (self::$currencies as $currency) {
            $selected = ($value['VALUE'] == $currency) ? ' selected' : '';
            $html .= '<option value="' . $currency . '"' . $selected . '>' . $currency . '</option>';
        }
        $html .= '</select>';
		
        return $html;
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
        return $value['VALUE'];
    }
	
	
    /**
     * Массив с группами пользователей.
	 *
     * @return array
     */
    private static function getCurrencies()
    {
		if (!\Bitrix\Main\Loader::includeModule('currency')) {
	    	return array();
    	}
		
        if (empty(self::$currencies)) {
			$result = \CCurrency::GetList(($by = "c_sort"), ($order = "asc"));
            if (intval($result->SelectedRowsCount()) > 0) {
                while ($item = $result->Fetch()) {
                    self::$currencies[$item['CURRENCY']] = $item['CURRENCY'];
                }
            }
        }
        return self::$currencies;
    }
}
