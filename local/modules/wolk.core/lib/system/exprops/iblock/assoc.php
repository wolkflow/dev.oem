<?php

namespace Wolk\Core\System\Exprops\IBlock;

class Assoc
{

    public function GetUserTypeDescription()
    {
        return array(
            'PROPERTY_TYPE'  => 'S',
            'USER_TYPE'      => 'assoc',
            'DESCRIPTION'    => 'Ассоциативный массив',
            'CheckFields'           => array(__CLASS__, 'CheckFields'),
            'GetLength'             => array(__CLASS__, 'GetLength'),
            'GetPublicViewHTML'     => array(__CLASS__, 'GetFieldView'),
            'GetPropertyFieldHtml'  => array(__CLASS__, 'GetEditField'),
            'GetPublicEditHTML'     => array(__CLASS__, 'GetEditField'),
			'GetAdminListViewHTML'  => array(__CLASS__, 'GetAdminListViewHTML'),
			'GetSettingsHTML' 		=> array(__CLASS__, 'GetSettingsHTML'),
			'PrepareSettings' 		=> array(__CLASS__, 'PrepareSettings'),
			'ConvertToDB' 			=> array(__CLASS__, 'ConvertToDB'),
			'ConvertFromDB' 		=> array(__CLASS__, 'ConvertFromDB'),
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
		$errors = array();
		
        return $errors;
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
     * @param $element
     * @return string
     */
    public function GetEditField($property, $value, $element)
    {
		$settings = $property['USER_TYPE_SETTINGS'];
		
		$fields = $property['USER_TYPE_SETTINGS']['FIELDS'];
		
		array_shift($fields);
		
		$element['VALUE'] = str_replace("[VALUE]", "", $element['VALUE']);
		
		$html  = '<table cellspacing="5" style="border: 1px dotted #a4b9cc; border-radius: 10px; margin: 3px; padding: 6px;">';
		foreach ($fields as $field) {
			$html .= '<tr><td>'.$field['UF_NAME'].'</td><td><input type="text" name="' . $element['VALUE'] . '['.$field['UF_CODE'].']" value="'.$value['VALUE'][$field['UF_CODE']].'" /></td></tr>';
		}
		$html .= '</table>';
		
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
        return json_encode($value);// '["'.$value['VALUE']['KEY'].'" => "'.$value['VALUE']['VALUE'].'"]';
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
        return ''; // '["'.$value['VALUE']['KEY'].'" => "'.$value['VALUE']['VALUE'].'"]';
    }
	
	
	/**
	 * Свойства.
	 */
	public function GetSettingsHTML($property, $strHTMLControlName, &$fields)
    {
        $fields = array(
            'HIDE' => array('FILTRABLE', 'ROW_COUNT', 'COL_COUNT', 'DEFAULT_VALUE'),
            'SET'  => array('FILTRABLE' => 'N'),
            'USER_TYPE_SETTINGS_TITLE' => 'Настройки ассоциативного массива'
        );
		
		$settings = $property['USER_TYPE_SETTINGS']['FIELDS'];
		
		array_shift($settings);
		
		$html = '
		<tr>
			<script src="https://yastatic.net/jquery/2.1.4/jquery.min.js" type="text/javascript"></script>
			<script type="text/javascript">
				function RowAssocAppend()
				{
					var $row = $("#assoc-field-row-id").clone();
					var $cnt = $(".js-row-assoc").length;
					console.log($row.html(), $cnt);
					$row.html($row.html().replace(/#CNT#/g, $cnt));
					$row.attr("id", "");
					$row.attr("class", "js-row-assoc");
					$row.css("display", "table-row");
					$("#table-assoc-fields-id tbody").append($row);
				}
			</script>
			<table id="table-assoc-fields-id" class="internal" style="margin: 0 auto;">
				<tbody>
					<tr class="heading">
						<td></td>
						<td>Название</td>
						<td>Символьный код</td>
					</tr>
					
					<tr id="assoc-field-row-id" style="display: none;">
						<td style="vertical-align: top;">
							<div onclick="$(this).parent().parent().remove();" style="background: url(/bitrix/panel/main/images/bx-admin-sprite-small-1.png) no-repeat 6px -2446px; display: inline-block; cursor: pointer; height: 23px; margin:0; opacity: 0.7; vertical-align: middle; width: 23px;"></div>
						</td>
						<td style="vertical-align: top;">
							<input type="text" name="'.$strHTMLControlName['NAME'].'[FIELDS][#CNT#][UF_NAME]" size="35" maxlength="255" />
						</td>
						<td style="vertical-align: top;">
							<input type="text" name="'.$strHTMLControlName['NAME'].'[FIELDS][#CNT#][UF_CODE]" size="25" maxlength="255" />
						</td>
					</tr>
		';
		
		if (!empty($settings)) {
			$i = 0;
			foreach ($settings as $setting) {
				$html .= '
					<tr class="js-row-assoc">
						<td style="vertical-align: top;">
							<div onclick="$(this).parent().parent().remove();" style="background: url(/bitrix/panel/main/images/bx-admin-sprite-small-1.png) no-repeat 6px -2446px; display: inline-block; cursor: pointer; height: 23px; margin:0; opacity: 0.7; vertical-align: middle; width: 23px;"></div>
						</td>
						<td style="vertical-align: top;">
							<input type="text" name="'.$strHTMLControlName['NAME'].'[FIELDS]['.$i.'][UF_NAME]" size="35" maxlength="255" value="'.$setting['UF_NAME'].'" />
						</td>
						<td style="vertical-align: top;">
							<input type="text" name="'.$strHTMLControlName['NAME'].'[FIELDS]['.$i.'][UF_CODE]" size="25" maxlength="255" value="'.$setting['UF_CODE'].'" />
						</td>
					</tr>
				';
				$i++;
			}
		} else {
			$html .= '
				<tr class="js-row-assoc">
					<td style="vertical-align: top;">
						<div onclick="$(this).parent().parent().remove();" style="background: url(/bitrix/panel/main/images/bx-admin-sprite-small-1.png) no-repeat 6px -2446px; display: inline-block; cursor: pointer; height: 23px; margin:0; opacity: 0.7; vertical-align: middle; width: 23px;"></div>
					</td>
					<td style="vertical-align: top;">
						<input type="text" name="'.$strHTMLControlName['NAME'].'[FIELDS][0][UF_NAME]" size="35" maxlength="255" />
					</td>
					<td style="vertical-align: top;">
						<input type="text" name="'.$strHTMLControlName['NAME'].'[FIELDS][0][UF_CODE]" size="25" maxlength="255" />
					</td>
				</tr>
			';
		}
		
		$html .= '
				</tbody>
			</table>
		<tr>
		</tr>
			<div style="width: 100%; text-align: center; margin: 10px 0;">
				<input type="button" value="Еще" onclick="javascript: RowAssocAppend();" class="adm-btn-big" />
			</div>
		</tr>		
		';
		
		return $html;
    }
	
	
	/**
	 * Настройки для типа данных.
	 *
	 * @param array $fields
	 * @return array
	 */
	public function PrepareSettings($fields)
    {
        return $fields['USER_TYPE_SETTINGS'];
    }
	
	
	/**
	 * Конвертация при сохранении в БД.
	 */
	public function ConvertToDB($property, $value)
    {
		$value['VALUE'] = array_filter($value['VALUE'], function($item) { return (is_numeric($item)); });
		
		if (empty($value['VALUE'])) {
			return '';
		}
		$value = json_encode($value);
		
		return $value;
	}
	
	
	/**
	 * Конвертация при получении из БД.
	 */
	public function ConvertFromDB($property, $value)
	{
		$value = json_decode($value['VALUE'], true);
		
		return $value;
	}
}

