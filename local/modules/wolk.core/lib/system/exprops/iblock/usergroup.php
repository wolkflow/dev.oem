<?php

namespace Wolk\Core\System\Exprops\IBlock;

class UserGroup
{
    static $groups = array();
	

    public function GetUserTypeDescription()
    {
        // Массив с группами пользователей.
        self::getUserGroups();

        return array(
            'PROPERTY_TYPE'  => 'E',
            'USER_TYPE'      => 'group',
            'DESCRIPTION'    => 'Привязка к группе пользователей',
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
    public function GetEditField($property, $value, $htmlElement)
    {
        $html  = '<select name="' . $htmlElement['VALUE'] . '" style="margin-bottom:10px;">';
        $html .= '<option value="">Не выбрано</option>';
        foreach (self::$groups as $id => $element) {
            $selected = ($value['VALUE'] == $id) ? ' selected' : '';
            $html .= '<option value="' . $id . '"' . $selected . '>' . $element . '</option>';
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
        $id = (int)$value['VALUE'];
        if (isset(self::$groups[$id])) {
            return self::$groups[$id];
        }
        return '';
    }
	
	
    /**
     * Массив с группами пользователей.
	 *
     * @return array
     */
    private static function getUserGroups()
    {
        if (!isset(self::$groups)) {
            $result = \CGroup::GetList(($by = "c_sort"), ($order = "desc"), array());
            if (intval($result->SelectedRowsCount()) > 0) {
                while ($item = $result->Fetch()) {
                    self::$groups[$item['ID']] = $item['NAME'];
                }
            }
        }
        return self::$groups;
    }
}
