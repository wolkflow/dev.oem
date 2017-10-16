<?
// We have to explicitly call calendar and editor functions because
// first output may be discarded by form settings

$tabControl->BeginPrologContent();
CJSCore::Init(array('date'));

if ($arTranslit['TRANSLITERATION'] == 'Y') {
    CJSCore::Init(array('translit'));
    ?>
    <script type="text/javascript">
		var linked=<?if($bLinked) echo 'true'; else echo 'false';?>;
		function set_linked()
		{
			linked=!linked;

			var name_link = document.getElementById('name_link');
			if (name_link) {
				if(linked)
					name_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
				else
					name_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
			}
			var code_link = document.getElementById('code_link');
			if (code_link) {
				if(linked)
					code_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
				else
					code_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
			}
			var linked_state = document.getElementById('linked_state');
			if (linked_state) {
				if(linked)
					linked_state.value='Y';
				else
					linked_state.value='N';
			}
		}
		var oldValue = '';
		function transliterate()
		{
			if (linked) {
				var from = document.getElementById('NAME');
				var to = document.getElementById('CODE');
				if (from && to && oldValue != from.value) {
					BX.translit(from.value, {
						'max_len' : <?= intval($arTranslit['TRANS_LEN'])?>,
						'change_case' : '<?= $arTranslit['TRANS_CASE']?>',
						'replace_space' : '<?= $arTranslit['TRANS_SPACE']?>',
						'replace_other' : '<?= $arTranslit['TRANS_OTHER']?>',
						'delete_repeat_replace' : <?= $arTranslit['TRANS_EAT'] == 'Y'? 'true': 'false'?>,
						'use_google' : <?= $arTranslit['USE_GOOGLE'] == 'Y'? 'true': 'false'?>,
						'callback' : function(result){to.value = result; setTimeout('transliterate()', 250); }
					});
					oldValue = from.value;
				} else {
					setTimeout('transliterate()', 250);
				}
			} else {
				setTimeout('transliterate()', 250);
			}
		}
		transliterate();
		</script>
		<?
	}
	?>
	<script type="text/javascript">
		var InheritedPropertiesTemplates = new JCInheritedPropertiesTemplates(
			'<?= $tabControl->GetName()?>_form',
			'/bitrix/admin/iblock_templates.ajax.php?ENTITY_TYPE=E&IBLOCK_ID=<?= intval($IBLOCK_ID)?>&ENTITY_ID=<?= intval($ID)?>'
		);
		BX.ready(function(){
			setTimeout(function(){
				InheritedPropertiesTemplates.updateInheritedPropertiesTemplates(true);
			}, 1000);
		});
	</script>
<?
	$tabControl->EndPrologContent();

	$tabControl->BeginEpilogContent();

echo bitrix_sessid_post();
echo GetFilterHiddens("find_");?>
<input type="hidden" name="linked_state" id="linked_state" value="<?if($bLinked) echo 'Y'; else echo 'N';?>">
<input type="hidden" name="Update" value="Y">
<input type="hidden" name="from" value="<?= htmlspecialcharsbx($from)?>">
<input type="hidden" name="WF" value="<?= htmlspecialcharsbx($WF)?>">
<input type="hidden" name="return_url" value="<?= htmlspecialcharsbx($return_url)?>">
<?if($ID>0 && !$bCopy)
{
	?><input type="hidden" name="ID" value="<?= $ID?>"><?
}
if ($bCopy) {
	?><input type="hidden" name="copyID" value="<?= $ID; ?>"><?
} elseif ($copyID > 0) {
	?><input type="hidden" name="copyID" value="<?= $copyID; ?>"><?
}

if ($bCatalog)
	CCatalogAdminTools::showFormParams();
?>
<input type="hidden" name="IBLOCK_SECTION_ID" value="<?= intval($IBLOCK_SECTION_ID)?>">
<input type="hidden" name="TMP_ID" value="<?= intval($TMP_ID)?>">
<?
$tabControl->EndEpilogContent();

$customTabber->SetErrorState($bVarsFromForm);

$arEditLinkParams = array(
	"find_section_section" => intval($find_section_section)
);
if ($bAutocomplete)
{
	$arEditLinkParams['lookup'] = $strLookup;
}

$tabControl->Begin(array(
	"FORM_ACTION" => "/bitrix/admin/".CIBlock::GetAdminElementEditLink($IBLOCK_ID, null, $arEditLinkParams)
));

$tabControl->BeginNextFormTab();
if ($ID > 0 && !$bCopy) {
    $p = CIblockElement::GetByID($ID);
    $pr = $p->ExtractFields("prn_");
} else {
    $pr = array();
}
$tabControl->AddCheckBoxField("ACTIVE", GetMessage("IBLOCK_FIELD_ACTIVE").":", false, array("Y","N"), $str_ACTIVE=="Y");
$tabControl->BeginCustomField("ACTIVE_FROM", GetMessage("IBLOCK_FIELD_ACTIVE_PERIOD_FROM"), $arIBlock["FIELDS"]["ACTIVE_FROM"]["IS_REQUIRED"] === "Y");
?>
	<tr id="tr_ACTIVE_FROM">
		<td><?= $tabControl->GetCustomLabelHTML()?>:</td>
		<td><?= CAdminCalendar::CalendarDate("ACTIVE_FROM", $str_ACTIVE_FROM, 19, true)?></td>
	</tr>
<?
$tabControl->EndCustomField("ACTIVE_FROM", '<input type="hidden" id="ACTIVE_FROM" name="ACTIVE_FROM" value="'.$str_ACTIVE_FROM.'">');
$tabControl->BeginCustomField("ACTIVE_TO", GetMessage("IBLOCK_FIELD_ACTIVE_PERIOD_TO"), $arIBlock["FIELDS"]["ACTIVE_TO"]["IS_REQUIRED"] === "Y");
?>
	<tr id="tr_ACTIVE_TO">
		<td><?= $tabControl->GetCustomLabelHTML()?>:</td>
		<td><?= CAdminCalendar::CalendarDate("ACTIVE_TO", $str_ACTIVE_TO, 19, true)?></td>
	</tr>

<?
$tabControl->EndCustomField("ACTIVE_TO", '<input type="hidden" id="ACTIVE_TO" name="ACTIVE_TO" value="'.$str_ACTIVE_TO.'">');

if($arTranslit["TRANSLITERATION"] == "Y")
{
	$tabControl->BeginCustomField("NAME", GetMessage("IBLOCK_FIELD_NAME").":", true);
	?>
		<tr id="tr_NAME">
			<td><?= $tabControl->GetCustomLabelHTML()?></td>
			<td style="white-space: nowrap;">
				<input type="text" size="50" name="NAME" id="NAME" maxlength="255" value="<?= $str_NAME?>"><img id="name_link" title="<?= GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
			</td>
		</tr>
	<?
	$tabControl->EndCustomField("NAME",
		'<input type="hidden" name="NAME" id="NAME" value="'.$str_NAME.'">'
	);

	$tabControl->BeginCustomField("CODE", GetMessage("IBLOCK_FIELD_CODE").":", $arIBlock["FIELDS"]["CODE"]["IS_REQUIRED"] === "Y");
	?>
		<tr id="tr_CODE">
			<td><?= $tabControl->GetCustomLabelHTML()?></td>
			<td style="white-space: nowrap;">
				<input type="text" size="50" name="CODE" id="CODE" maxlength="255" value="<?= $str_CODE?>"><img id="code_link" title="<?= GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
			</td>
		</tr>
	<?
	$tabControl->EndCustomField("CODE",
		'<input type="hidden" name="CODE" id="CODE" value="'.$str_CODE.'">'
	);
}
else
{
	$tabControl->AddEditField("NAME", GetMessage("IBLOCK_FIELD_NAME").":", true, array("size" => 50, "maxlength" => 255), $str_NAME);
	$tabControl->AddEditField("CODE", GetMessage("IBLOCK_FIELD_CODE").":", $arIBlock["FIELDS"]["CODE"]["IS_REQUIRED"] === "Y", array("size" => 20, "maxlength" => 255), $str_CODE);
}

if (
	$arShowTabs['sections']
	&& $arIBlock["FIELDS"]["IBLOCK_SECTION"]["DEFAULT_VALUE"]["KEEP_IBLOCK_SECTION_ID"] === "Y"
)
{
	$arDropdown = array();
	if ($str_IBLOCK_ELEMENT_SECTION)
	{
		$sectionList = CIBlockSection::GetList(
			array("left_margin"=>"asc"),
			array("=ID"=>$str_IBLOCK_ELEMENT_SECTION),
			false,
			array("ID", "NAME")
		);
		while ($section = $sectionList->Fetch())
			$arDropdown[$section["ID"]] = $section["NAME"];
	}
	$tabControl->BeginCustomField("IBLOCK_ELEMENT_SECTION_ID", GetMessage("IBEL_E_MAIN_IBLOCK_SECTION_ID").":", false);
	?>
		<tr id="tr_IBLOCK_ELEMENT_SECTION_ID">
			<td class="adm-detail-valign-top"><?= $tabControl->GetCustomLabelHTML()?></td>
			<td>
				<div id="RESULT_IBLOCK_ELEMENT_SECTION_ID">
				<select name="IBLOCK_ELEMENT_SECTION_ID" id="IBLOCK_ELEMENT_SECTION_ID" onchange="InheritedPropertiesTemplates.updateInheritedPropertiesValues(false, true)">
				<?foreach($arDropdown as $key => $val):?>
					<option value="<?= $key?>" <?if ($str_IBLOCK_SECTION_ID == $key) echo 'selected'?>><?= $val?></option>
				<?endforeach?>
				</select>
				</div>
				<script type="text/javascript">
					window.ipropTemplates[window.ipropTemplates.length] = {
						"ID": "IBLOCK_ELEMENT_SECTION_ID",
						"INPUT_ID": "IBLOCK_ELEMENT_SECTION_ID",
						"RESULT_ID": "RESULT_IBLOCK_ELEMENT_SECTION_ID",
						"TEMPLATE": ""
					};
					window.ipropTemplates[window.ipropTemplates.length] = {
						"ID": "CODE",
						"INPUT_ID": "CODE",
						"RESULT_ID": "",
						"TEMPLATE": ""
					};
					<? if (COption::GetOptionString('iblock', 'show_xml_id') == 'Y') { ?>
                        window.ipropTemplates[window.ipropTemplates.length] = {
                            "ID": "XML_ID",
                            "INPUT_ID": "XML_ID",
                            "RESULT_ID": "",
                            "TEMPLATE": ""
                        };
					<? } ?>
				</script>
			</td>
		</tr>
	<?
	$tabControl->EndCustomField("IBLOCK_ELEMENT_SECTION_ID",
		'<input type="hidden" name="IBLOCK_ELEMENT_SECTION_ID" id="IBLOCK_ELEMENT_SECTION_ID" value="'.$str_IBLOCK_SECTION_ID.'">'
	);
}

if(COption::GetOptionString("iblock", "show_xml_id", "N")=="Y")
	$tabControl->AddEditField("XML_ID", GetMessage("IBLOCK_FIELD_XML_ID").":", $arIBlock["FIELDS"]["XML_ID"]["IS_REQUIRED"] === "Y", array("size" => 20, "maxlength" => 255, "id" => "XML_ID"), $str_XML_ID);

$tabControl->AddEditField("SORT", GetMessage("IBLOCK_FIELD_SORT").":", $arIBlock["FIELDS"]["SORT"]["IS_REQUIRED"] === "Y", array("size" => 7, "maxlength" => 10), $str_SORT);

if(!empty($PROP)):
	if ($arIBlock["SECTION_PROPERTY"] === "Y" || defined("CATALOG_PRODUCT"))
	{
		$arPropLinks = array("IBLOCK_ELEMENT_PROP_VALUE");
		if(is_array($str_IBLOCK_ELEMENT_SECTION) && !empty($str_IBLOCK_ELEMENT_SECTION))
		{
			foreach($str_IBLOCK_ELEMENT_SECTION as $section_id)
			{
				foreach(CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $section_id) as $PID => $arLink)
					$arPropLinks[$PID] = "PROPERTY_".$PID;
			}
		}
		else
		{
			foreach(CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, 0) as $PID => $arLink)
				$arPropLinks[$PID] = "PROPERTY_".$PID;
		}
		$tabControl->AddFieldGroup("IBLOCK_ELEMENT_PROPERTY", GetMessage("IBLOCK_ELEMENT_PROP_VALUE"), $arPropLinks, $bPropertyAjax);
	}

	$tabControl->AddSection("IBLOCK_ELEMENT_PROP_VALUE", GetMessage("IBLOCK_ELEMENT_PROP_VALUE"));

	foreach($PROP as $prop_code=>$prop_fields):
		$prop_values = $prop_fields["VALUE"];
		$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
		?>
		<tr id="tr_PROPERTY_<?= $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
			<td class="adm-detail-valign-top" width="40%"><?if($prop_fields["HINT"]!=""):
				?><span id="hint_<?= $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?= $prop_fields["ID"];?>'), '<?= CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
			endif;?><?= $tabControl->GetCustomLabelHTML();?>:</td>
			<td width="60%"><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
		
			<? if (strpos($prop_code, 'LANG_ACTIVE_') !== false) { ?>
                <script>
                    var langActiveElement<?= $prop_fields['ID'] ?> = document.querySelector('input[type=checkbox][name=PROP\\[<?= $prop_fields['ID'] ?>\\]\\[<?= str_replace(':',
                        '\\\:', key($prop_fields['VALUE'])) ?: 'n0'?>\\]\\[VALUE\\]]');
                    if (langActiveElement<?= $prop_fields['ID'] ?>) {
                        langActiveElement<?= $prop_fields['ID'] ?>.addEventListener('change', function() {
                            CheckLangFields(this);
                        });
                    }
                    $(function () {
                        CheckLangFields(langActiveElement<?=$prop_fields["ID"]?>);
                    });
                </script>
            <? } ?>
		</tr>
		<?
			$hidden = "";
			if(!is_array($prop_fields["~VALUE"]))
				$values = Array();
			else
				$values = $prop_fields["~VALUE"];
			$start = 1;
			foreach($values as $key=>$val)
			{
				if($bCopy)
				{
					$key = "n".$start;
					$start++;
				}

				if(is_array($val) && array_key_exists("VALUE",$val))
				{
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
				}
				else
				{
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
					$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
				}
			}
		$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);
	endforeach;?>
<?endif;

	if (!$bAutocomplete && ($ID > 0 && !$bCopy))
	{
		$rsLinkedProps = CIBlockProperty::GetList(array(), array(
			"PROPERTY_TYPE" => "E",
			"LINK_IBLOCK_ID" => $IBLOCK_ID,
			"ACTIVE" => "Y",
			"FILTRABLE" => "Y",
		));
		$arLinkedProp = $rsLinkedProps->GetNext();
		if ($arLinkedProp)
		{
			$linkedTitle = '';
			$tabControl->BeginCustomField("LINKED_PROP", GetMessage("IBLOCK_ELEMENT_EDIT_LINKED"));
			?>
			<tr class="heading" id="tr_LINKED_PROP">
				<td colspan="2"><?= $tabControl->GetCustomLabelHTML();?></td>
			</tr>
			<?
			if (defined('BX_PUBLIC_MODE') && BX_PUBLIC_MODE == 1)
				$linkedTitle = htmlspecialcharsbx(GetMessage('IBLOCK_LINKED_ELEMENT_TITLE'));
			do {
				$elements_name = CIBlock::GetArrayByID($arLinkedProp["IBLOCK_ID"], "ELEMENTS_NAME");
				if(strlen($elements_name) <= 0)
					$elements_name = GetMessage("IBLOCK_ELEMENT_EDIT_ELEMENTS");
			?>
			<tr id="tr_LINKED_PROP<?= $arLinkedProp["ID"]?>">
				<td colspan="2"><a title="<?= $linkedTitle; ?>" href="/bitrix/admin/<?= htmlspecialcharsbx(CIBlock::GetAdminElementListLink($arLinkedProp["IBLOCK_ID"], array('set_filter'=>'Y', 'find_el_property_'.$arLinkedProp["ID"]=>$ID, 'find_section_section' => -1)))?>"><?= CIBlock::GetArrayByID($arLinkedProp["IBLOCK_ID"], "NAME").": ".$elements_name?></a></td>
			</tr>
			<?
			} while ($arLinkedProp = $rsLinkedProps->GetNext());
			unset($linkedTitle);
			$tabControl->EndCustomField("LINKED_PROP", "");
		}
	}
    
