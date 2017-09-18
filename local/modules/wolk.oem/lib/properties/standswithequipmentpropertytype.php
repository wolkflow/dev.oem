<?php namespace Wolk\OEM\Properties;

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use Wolk\OEM\EventsStandsSizesTable;

class StandsWithEquipmentPropertyType
{
	public static function GetUserTypeDescription()
	{
		return [
			'PROPERTY_TYPE'        => 'E',
			'USER_TYPE'            => 'standsWithEquipment',
			'DESCRIPTION'          => 'Стенды с оборудованием',
			'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
			'ConvertToDB'          => [__CLASS__, 'ConvertToDB'],
			'ConvertFromDB'        => [__CLASS__, 'ConvertFromDB'],

		];
	}

	
	public static function ConvertToDB($arProperty, $value)
	{
		$value['DESCRIPTION'] = serialize($value['DESCRIPTION']);
		$value['VALUE'] = $value['VALUE']['STAND'];

		return $value;
	}
	

	public static function ConvertFromDB($arProperty, $value)
	{
		return $value;
	}

	
	public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
	{
		$valueId = intval(preg_replace('#PROP\['.$arProperty['ID'].'\]\[([\d]+)\]\[VALUE\]#', '$1', $strHTMLControlName['VALUE']));
		$standWidth = $standDepth = null;
		if($valueId) {
			if($row = EventsStandsSizesTable::getRow([
				'filter' =>
					[
						'ENUM_ID' => $valueId
					]
			])) {
				list($standWidth, $standDepth) = [$row['WIDTH'], $row['DEPTH']];
			}

		}
		\CJSCore::init(['jquery']);
		$am = Asset::getInstance();
		$am->addString(<<<JS
			<script>
						function handleEqCheck(el) {
						var input = $("input#"+$(el).data('ident'));
							if(el.checked) {
								input.removeAttr('disabled');
								input.val(1);
							} else {
								input.attr('disabled', true);
								input.val('');
							}
						}
			</script>
JS
			, true, AssetLocation::AFTER_JS);
		$equipment = self::getAvailableEquipment();
		$curEquipment = unserialize($value['DESCRIPTION']);

		$settings = self::PrepareSettings($arProperty);
		if($settings["size"] > 1) {
			$size = ' size="' . $settings["size"] . '"';
		} else {
			$size = '';
		}

		if($settings["width"] > 0) {
			$width = ' style="width:' . $settings["width"] . 'px"';
		} else {
			$width = '';
		}

		$bWasSelect = false;
		$options = self::GetOptionsHtml($arProperty, [$value["VALUE"]], $bWasSelect);

		$html = '<select name="' . $strHTMLControlName["VALUE"] . '[STAND]"' . $size . $width . '>';
		if($arProperty["IS_REQUIRED"] != "Y") {
			$html .= '<option value=""' . (!$bWasSelect ? ' selected' : '') . '>' . GetMessage("IBLOCK_PROP_ELEMENT_LIST_NO_VALUE") . '</option>';
		}
		$html .= $options;
		$html .= '</select>';
		ob_start(); ?>
		Длина <input size="5" type="text" name="<?= $strHTMLControlName['VALUE'] ?>[WIDTH]" value="<?=$standWidth?>">
		Ширина <input size="5" type="text" name="<?= $strHTMLControlName['VALUE'] ?>[DEPTH]" value="<?=$standDepth?>">
		<a href="javascript:void(0)" onclick="$(this).next().toggle()">Оборудование</a>
		<div id="<?= str_replace(['[', ']', '__'], '_', $strHTMLControlName['VALUE']) ?>" style="display: none;"
		     class="b_stand_equipment_list">
			<? foreach($equipment as $eqId => $eqName): ?>
				<? $value = (array_key_exists($eqId, $curEquipment) ? $curEquipment[$eqId] : '') ?>
				<div class="b_stand_equipment_row">
					<input<? if(in_array($eqId, array_keys($curEquipment))): ?> checked<? endif; ?>
						data-ident="count<?= $eqId ?>_<?= str_replace([
							'[',
							']',
							'__'
						], '_', $strHTMLControlName['DESCRIPTION']) ?>" type="checkbox"
						onchange="return handleEqCheck(this);">
					<label for=""><?= $eqName ?></label>
					<input<? if(!$value): ?> disabled<? endif; ?> class="adm-input"
					                                              id="count<?= $eqId ?>_<?= str_replace([
						                                              '[',
						                                              ']',
						                                              '__'
					                                              ], '_', $strHTMLControlName['DESCRIPTION']) ?>"
					                                              name="<?= $strHTMLControlName['DESCRIPTION'] ?>[<?= $eqId ?>]"
					                                              type="text" size="4" value="<?= $value ?>">
				</div>
			<? endforeach; ?>
		</div>
		<?
		$html .= ob_get_clean();

		return $html;
	}

	protected static function getAvailableEquipment() {
		$equipment = [];
		$obEquipment = \CIBlockElement::GetList([], [
			'IBLOCK_ID' => EQUIPMENT_IBLOCK_ID,
			'ACTIVE'    => 'Y'
		], false, false, [
			'ID',
			'NAME'
		]);
		while($arEquipment = $obEquipment->Fetch()) {
			$equipment[$arEquipment['ID']] = $arEquipment['NAME'];
		}

		return $equipment;
	}

	public static function PrepareSettings($arProperty) {
		$size = 0;
		if(is_array($arProperty["USER_TYPE_SETTINGS"])) {
			$size = intval($arProperty["USER_TYPE_SETTINGS"]["size"]);
		}
		if($size <= 0) {
			$size = 1;
		}

		$width = 0;
		if(is_array($arProperty["USER_TYPE_SETTINGS"])) {
			$width = intval($arProperty["USER_TYPE_SETTINGS"]["width"]);
		}
		if($width <= 0) {
			$width = 0;
		}

		if(is_array($arProperty["USER_TYPE_SETTINGS"]) && $arProperty["USER_TYPE_SETTINGS"]["group"] === "Y") {
			$group = "Y";
		} else {
			$group = "N";
		}

		if(is_array($arProperty["USER_TYPE_SETTINGS"]) && $arProperty["USER_TYPE_SETTINGS"]["multiple"] === "Y") {
			$multiple = "Y";
		} else {
			$multiple = "N";
		}

		return [
			"size"     => $size,
			"width"    => $width,
			"group"    => $group,
			"multiple" => $multiple,
		];
	}

	public static function GetOptionsHtml($arProperty, $values, &$bWasSelect) {
		$options = "";
		$settings = self::PrepareSettings($arProperty);
		$bWasSelect = false;

		if($settings["group"] === "Y") {
			$arElements = self::GetElements($arProperty["LINK_IBLOCK_ID"]);
			$arTree = self::GetSections($arProperty["LINK_IBLOCK_ID"]);
			foreach($arElements as $i => $arElement) {
				if(
					$arElement["IN_SECTIONS"] == "Y"
					&& array_key_exists($arElement["IBLOCK_SECTION_ID"], $arTree)
				) {
					$arTree[$arElement["IBLOCK_SECTION_ID"]]["E"][] = $arElement;
					unset($arElements[$i]);
				}
			}

			foreach($arTree as $arSection) {
				$options .= '<optgroup label="' . str_repeat(" . ", $arSection["DEPTH_LEVEL"] - 1) . $arSection["NAME"] . '">';
				if(isset($arSection["E"])) {
					foreach($arSection["E"] as $arItem) {
						$options .= '<option value="' . $arItem["ID"] . '"';
						if(in_array($arItem["~ID"], $values)) {
							$options .= ' selected';
							$bWasSelect = true;
						}
						$options .= '>' . $arItem["NAME"] . '</option>';
					}
				}
				$options .= '</optgroup>';
			}
			foreach($arElements as $arItem) {
				$options .= '<option value="' . $arItem["ID"] . '"';
				if(in_array($arItem["~ID"], $values)) {
					$options .= ' selected';
					$bWasSelect = true;
				}
				$options .= '>' . $arItem["NAME"] . '</option>';
			}

		} else {
			foreach(self::GetElements($arProperty["LINK_IBLOCK_ID"]) as $arItem) {
				$options .= '<option value="' . $arItem["ID"] . '"';
				if(in_array($arItem["~ID"], $values)) {
					$options .= ' selected';
					$bWasSelect = true;
				}
				$options .= '>' . $arItem["NAME"] . '</option>';
			}
		}

		return $options;
	}

	public static function GetElements($IBLOCK_ID) {
		static $cache = [];
		$IBLOCK_ID = intval($IBLOCK_ID);

		if(!array_key_exists($IBLOCK_ID, $cache)) {
			$cache[$IBLOCK_ID] = [];
			if($IBLOCK_ID > 0) {
				$arSelect = [
					"ID",
					"NAME",
					"IN_SECTIONS",
					"IBLOCK_SECTION_ID",
				];
				$arFilter = [
					"IBLOCK_ID"         => $IBLOCK_ID,
					//"ACTIVE" => "Y",
					"CHECK_PERMISSIONS" => "Y",
				];
				$arOrder = [
					"NAME" => "ASC",
					"ID"   => "ASC",
				];
				$rsItems = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
				while($arItem = $rsItems->GetNext()) {
					$cache[$IBLOCK_ID][] = $arItem;
				}
			}
		}

		return $cache[$IBLOCK_ID];
	}

	public static function GetSections($IBLOCK_ID) {
		static $cache = [];
		$IBLOCK_ID = intval($IBLOCK_ID);

		if(!array_key_exists($IBLOCK_ID, $cache)) {
			$cache[$IBLOCK_ID] = [];
			if($IBLOCK_ID > 0) {
				$arSelect = [
					"ID",
					"NAME",
					"DEPTH_LEVEL",
				];
				$arFilter = [
					"IBLOCK_ID"         => $IBLOCK_ID,
					//"ACTIVE" => "Y",
					"CHECK_PERMISSIONS" => "Y",
				];
				$arOrder = [
					"LEFT_MARGIN" => "ASC",
				];
				$rsItems = \CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);
				while($arItem = $rsItems->GetNext()) {
					$cache[$IBLOCK_ID][$arItem["ID"]] = $arItem;
				}
			}
		}

		return $cache[$IBLOCK_ID];
	}
}