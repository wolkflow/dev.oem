<?
/** @global CMain $APPLICATION */
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
CModule::IncludeModule("iblock");
CModule::IncludeModule("wolk.oem");
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/iblock/admin/iblock_element_search.php');


function getfilehtml($file, $pid, $pbid)
{
	ob_start();
	include ($file);
	return ob_get_clean();
}


// Init variables
$reloadParams = array();

$eventID = '';
if (isset($_GET['event'])) {
	$eventID = preg_replace("/[^a-zA-Z0-9_:\\[\\]]/", "", $_GET['event']);
}

$func = '';
if (isset($_GET['func'])) {
	$func = preg_replace("/[^a-zA-Z0-9_:\\[\\]]/", "", $_GET['func']);
}

$language = '';
if (isset($_GET['language'])) {
	$language = preg_replace("/[^a-zA-Z0-9_:\\[\\]]/", "", $_GET['language']);
}

$currency = '';
if (isset($_GET['currency'])) {
	$currency = preg_replace("/[^a-zA-Z0-9_:\\[\\]]/", "", $_GET['currency']);
}

$typestand = '';
if (isset($_GET['typestand'])) {
	$typestand = preg_replace("/[^a-zA-Z0-9_:\\[\\]]/", "", $_GET['typestand']);
}

$basketID = '';
if (isset($_GET['bid'])) {
	$basketID = preg_replace("/[^a-zA-Z0-9_:\\[\\]]/", "", $_GET['bid']);
} else {
	$basketID = 'n' . md5(uniqid(time()));
}

// Контекст.
$context = new Wolk\OEM\Context($eventID, $typestand, $language);

// Мероприятие.
$event = new Wolk\OEM\Event($eventID);

$basket = null;
if (!empty($basketID)) {
    $basket = new Wolk\OEM\BasketItem($event->getCode(), $basketID);
}


$n = '';
if (isset($_GET['n']))
	$n = preg_replace("/[^a-zA-Z0-9_:\\[\\]]/", "", $_GET['n']);
if ($n != '')
	$reloadParams['n'] = $n;

$k = '';
if (isset($_GET['k']))
	$k = preg_replace("/[^a-zA-Z0-9_:]/", "", $_GET['k']);
if ($k != '')
	$reloadParams['k'] = $k;

$lookup = '';
if (isset($_GET['lookup']))
	$lookup = preg_replace("/[^a-zA-Z0-9_:]/", "", $_GET['lookup']);
if ($lookup != '')
	$reloadParams['lookup'] = $lookup;

$m = (isset($_GET["m"]) && $_GET["m"] === "y");
if ($m)
	$reloadParams['m'] = 'y';

$get_xml_id = (isset($_GET["get_xml_id"]) && $_GET["get_xml_id"] === "Y");
if ($get_xml_id)
	$reloadParams['get_xml_id'] = 'Y';

$strWarning = "";

$boolDiscount = (isset($_REQUEST['discount']) && 'Y' == $_REQUEST['discount']);
if ($boolDiscount)
	$reloadParams['discount'] = 'Y';

$sTableID = ($boolDiscount ? "tbl_iblock_el_search".md5('discount') : "tbl_iblock_el_search".md5($n));

$lAdmin = new CAdminList($sTableID);
$lAdmin->InitFilter(array("filter_iblock_id"));
$IBLOCK_ID = intval($_GET["IBLOCK_ID"]) > 0 ? intval($_GET["IBLOCK_ID"]): intval($filter_iblock_id);

$iblockFix = isset($_GET['iblockfix']) && $_GET['iblockfix'] == 'y';
$showIblockList = true;
if ($iblockFix)
{
	$showIblockList = !(isset($_GET['IBLOCK_ID']) && (int)$_GET['IBLOCK_ID'] > 0);
	$filter_iblock_id = $IBLOCK_ID;
}

$reloadUrl = 'iblock_element_search.php?lang='.LANGUAGE_ID;
foreach ($reloadParams as $key => $value)
	$reloadUrl .= '&'.$key.'='.$value;
unset($key, $value);

$arIBTYPE = false;
if ($IBLOCK_ID > 0) {
	$arIBlock = CIBlock::GetArrayByID($IBLOCK_ID);

	if ($arIBlock) {
		$arIBTYPE = CIBlockType::GetByIDLang($arIBlock["IBLOCK_TYPE_ID"], LANGUAGE_ID);
		if(!$arIBTYPE)
			$APPLICATION->AuthForm(GetMessage("IBLOCK_BAD_BLOCK_TYPE_ID"));

		$bBadBlock = !CIBlockRights::UserHasRightTo($IBLOCK_ID, $IBLOCK_ID, "iblock_admin_display");
	} else {
		$bBadBlock = true;
	}

	if($bBadBlock)
		$APPLICATION->AuthForm(GetMessage("IBLOCK_BAD_IBLOCK"));
} else {
	$arIBlock = array(
		"ID" => 0,
		"ELEMENTS_NAME" => GetMessage("IBLOCK_ELSEARCH_ELEMENTS"),
	);
}

$APPLICATION->SetTitle(GetMessage("IBLOCK_ELSEARCH_TITLE"));

CModule::IncludeModule('fileman');
$minImageSize = array("W" => 1, "H" => 1);
$maxImageSize = array(
	"W" => COption::GetOptionString("iblock", "list_image_size"),
	"H" => COption::GetOptionString("iblock", "list_image_size"),
);

$dbrFProps = CIBlockProperty::GetList(
		array(
			"SORT" => "ASC",
			"NAME" => "ASC",
		),
		array(
			"IBLOCK_ID"=>$IBLOCK_ID,
			"ACTIVE"=>"Y",
		)
	);

$arProps = array();
while ($arFProps = $dbrFProps->GetNext()) {
	if (strlen($arFProps["USER_TYPE"]) > 0) {
		$arFProps["PROPERTY_USER_TYPE"] = CIBlockProperty::GetUserType($arFProps["USER_TYPE"]);
	} else {
		$arFProps["PROPERTY_USER_TYPE"] = array();
    }
	$arProps []= $arFProps;
}

$arFilterFields = array(
	"filter_iblock_id",
	"filter_section",
	"filter_subsections",
	"filter_id_start",
	"filter_id_end",
	"filter_external_id",
	"filter_type",
	"filter_timestamp_from",
	"filter_timestamp_to",
	"filter_modified_user_id",
	"filter_modified_by",
	"filter_status_id",
	"filter_status",
	"filter_active",
	"filter_intext",
	"filter_name",
	"filter_code"
);

foreach( $arProps as $prop) {
	if ($prop["FILTRABLE"] == "Y" && $prop["PROPERTY_TYPE"] != "F") {
		$arFilterFields[] = "find_el_property_".$prop["ID"];
    }
}

$oSort = new CAdminSorting($sTableID, "NAME", "asc");
$arOrder = (strtoupper($by) === "ID"? array($by => $order): array($by => $order, "ID" => "ASC"));
$lAdmin = new CAdminList($sTableID, $oSort);

$lAdmin->InitFilter($arFilterFields);

$arFilter = array(
	"IBLOCK_TYPE" => $filter_type,
	"SECTION_ID" => $filter_section,
	"MODIFIED_USER_ID" => $filter_modified_user_id,
	"MODIFIED_BY" => $filter_modified_by,
	"ACTIVE" => $filter_active,
	"EXTERNAL_ID" => $filter_external_id,
	"?NAME" => $filter_name,
	"?CODE" => $filter_code,
	"?SEARCHABLE_CONTENT" => $filter_intext,
	"SHOW_NEW" => "Y"
);

if($filter_iblock_id > 0)
	$arFilter["IBLOCK_ID"] = $filter_iblock_id;
elseif($IBLOCK_ID > 0)
	$arFilter["IBLOCK_ID"] = $IBLOCK_ID;
else
	$arFilter["IBLOCK_ID"] = -1;

if (IntVal($filter_section)<0 || strlen($filter_section) <= 0) {
	unset($arFilter["SECTION_ID"]);
} elseif($filter_subsections=="Y") {
	if($arFilter["SECTION_ID"]==0)
		unset($arFilter["SECTION_ID"]);
	else
		$arFilter["INCLUDE_SUBSECTIONS"] = "Y";
}

if (!empty($filter_id_start)) $arFilter[">=ID"] = $filter_id_start;
if (!empty($filter_id_end)) $arFilter["<=ID"] = $filter_id_end;
if (!empty($filter_timestamp_from)) $arFilter["DATE_MODIFY_FROM"] = $filter_timestamp_from;
if (!empty($filter_timestamp_to)) $arFilter["DATE_MODIFY_TO"] = $filter_timestamp_to;
if (!empty($filter_status_id)) $arFilter["WF_STATUS"] = $filter_status_id;
if (!empty($filter_status) && strcasecmp($filter_status, "NOT_REF")) $arFilter["WF_STATUS"] = $filter_status;

foreach ($arProps as $prop) {
	if ($prop["FILTRABLE"]=="Y" && $prop["PROPERTY_TYPE"]!="F" && !empty(${"find_el_property_".$prop["ID"]}))
		$arFilter["?PROPERTY_".$prop["ID"]] = ${"find_el_property_".$prop["ID"]};
}

$arFilter["CHECK_PERMISSIONS"]="Y";

$arHeader = array();
$arHeader[] = array("id"=>"ID", "content"=>GetMessage("IBLOCK_FIELD_ID"), "sort"=>"id", "align"=>"right", "default"=>true);
$arHeader[] = array("id"=>"TIMESTAMP_X", "content"=>GetMessage("IBLOCK_FIELD_TIMESTAMP_X"), "sort"=>"timestamp_x", "default"=>true);
$arHeader[] = array("id"=>"USER_NAME", "content"=>GetMessage("IBLOCK_FIELD_USER_NAME"), "sort"=>"modified_by", "default"=>true);
$arHeader[] = array("id"=>"ACTIVE", "content"=>GetMessage("IBLOCK_FIELD_ACTIVE"), "sort"=>"active", "align"=>"center", "default"=>true);
$arHeader[] = array("id"=>"NAME", "content"=>GetMessage("IBLOCK_FIELD_NAME"), "sort"=>"name", "default"=>true);

$arHeader[] = array("id"=>"ACTIVE_FROM", "content"=>GetMessage("IBLOCK_FIELD_ACTIVE_FROM"), "sort"=>"date_active_from");
$arHeader[] = array("id"=>"ACTIVE_TO", "content"=>GetMessage("IBLOCK_FIELD_ACTIVE_TO"), "sort"=>"date_active_to");
$arHeader[] = array("id"=>"SORT", "content"=>GetMessage("IBLOCK_FIELD_SORT"), "sort"=>"sort", "align"=>"right");
$arHeader[] = array("id"=>"DATE_CREATE", "content"=>GetMessage("IBLOCK_FIELD_DATE_CREATE"), "sort"=>"created");
$arHeader[] = array("id"=>"CREATED_USER_NAME", "content"=>GetMessage("IBLOCK_FIELD_CREATED_USER_NAME"), "sort"=>"created_by");

$arHeader[] = array("id"=>"CODE", "content"=>GetMessage("IBLOCK_FIELD_CODE"), "sort"=>"code");
$arHeader[] = array("id"=>"EXTERNAL_ID", "content"=>GetMessage("IBLOCK_FIELD_XML_ID"), "sort"=>"external_id");

if (CModule::IncludeModule("workflow")) {
	$arHeader[] = array("id"=>"WF_STATUS_ID", "content"=>GetMessage("IBLOCK_FIELD_STATUS"), "sort"=>"status", "default"=>true);
	$arHeader[] = array("id"=>"LOCKED_USER_NAME", "content"=>GetMessage("IBLOCK_ELSEARCH_LOCK_BY"));
}

$arHeader[] = array("id"=>"SHOW_COUNTER", "content"=>GetMessage("IBLOCK_FIELD_SHOW_COUNTER"), "sort"=>"show_counter", "align"=>"right");
$arHeader[] = array("id"=>"SHOW_COUNTER_START", "content"=>GetMessage("IBLOCK_FIELD_SHOW_COUNTER_START"), "sort"=>"show_counter_start", "align"=>"right");
$arHeader[] = array("id"=>"PREVIEW_PICTURE", "content"=>GetMessage("IBLOCK_FIELD_PREVIEW_PICTURE"), "align"=>"right");
$arHeader[] = array("id"=>"PREVIEW_TEXT", "content"=>GetMessage("IBLOCK_FIELD_PREVIEW_TEXT"));
$arHeader[] = array("id"=>"DETAIL_PICTURE", "content"=>GetMessage("IBLOCK_FIELD_DETAIL_PICTURE"), "align"=>"center");
$arHeader[] = array("id"=>"DETAIL_TEXT", "content"=>GetMessage("IBLOCK_FIELD_DETAIL_TEXT"));

foreach ($arProps as $prop) {
	$arHeader[] = array("id"=>"PROPERTY_".$prop['ID'], "content"=>$prop['NAME'], "align"=>($prop["PROPERTY_TYPE"]=='N'?"right":"left"), "sort" => ($prop["MULTIPLE"]!='Y'? "PROPERTY_".$prop['ID'] : ""));
}

$lAdmin->AddHeaders($arHeader);

$arSelectedFields = $lAdmin->GetVisibleHeaderColumns();

$arSelectedProps = array();
foreach ($arProps as $prop) {
	if ($key = array_search("PROPERTY_".$prop['ID'], $arSelectedFields)) {
		$arSelectedProps[] = $prop;
		$arSelect[$prop['ID']] = array();
		$props = CIBlockProperty::GetPropertyEnum($prop['ID']);
		while($res = $props->Fetch())
			$arSelect[$prop['ID']][$res["ID"]] = $res["VALUE"];
		unset($arSelectedFields[$key]);
	}
}

if (!in_array("ID", $arSelectedFields))
	$arSelectedFields[] = "ID";

$arSelectedFields[] = "LANG_DIR";
$arSelectedFields[] = "LID";
$arSelectedFields[] = "WF_PARENT_ELEMENT_ID";

if(in_array("LOCKED_USER_NAME", $arSelectedFields))
	$arSelectedFields[] = "WF_LOCKED_BY";
if(in_array("USER_NAME", $arSelectedFields))
	$arSelectedFields[] = "MODIFIED_BY";
if(in_array("CREATED_USER_NAME", $arSelectedFields))
	$arSelectedFields[] = "CREATED_BY";
if(in_array("PREVIEW_TEXT", $arSelectedFields))
	$arSelectedFields[] = "PREVIEW_TEXT_TYPE";
if(in_array("DETAIL_TEXT", $arSelectedFields))
	$arSelectedFields[] = "DETAIL_TEXT_TYPE";

$arSelectedFields[] = "LOCK_STATUS";
$arSelectedFields[] = "WF_NEW";
$arSelectedFields[] = "WF_STATUS_ID";
$arSelectedFields[] = "DETAIL_PAGE_URL";
$arSelectedFields[] = "SITE_ID";
$arSelectedFields[] = "CODE";
$arSelectedFields[] = "EXTERNAL_ID";
$arSelectedFields[] = "NAME";
$arSelectedFields[] = "XML_ID";
$arSelectedFields[] = "PREVIEW_PICTURE";
$arSelectedFields[] = "DETAIL_PICTURE";

if ($IBLOCK_ID == IBLOCK_PRODUCTS_ID) {
    $arSelectedFields[] = 'PROPERTY_LANG_TITLE_'.strtoupper($language);
}
    
if ($IBLOCK_ID == IBLOCK_STANDS_ID) {
    $arSelectedFields[] = 'PROPERTY_LANG_NAME_'.strtoupper($language);
}

$result = CIBlockElement::GetList($arOrder, $arFilter, false, array("nPageSize"=>CAdminResult::GetNavSize($sTableID)), $arSelectedFields);
$result = new CAdminResult($result, $sTableID);
$result->NavStart();
$lAdmin->NavText($result->GetNavPrint($arIBlock["ELEMENTS_NAME"]));

function GetElementName($ID)
{
	$ID = IntVal($ID);
	static $cache = array();
	if (!array_key_exists($ID, $cache) && $ID > 0) {
		$rsElement = CIBlockElement::GetList(array(), array("ID"=>$ID, "SHOW_HISTORY"=>"Y"), false, false, array("ID","IBLOCK_ID","NAME"));
		$cache[$ID] = $rsElement->GetNext();
	}
	return $cache[$ID];
}

function GetSectionName($ID)
{
	$ID = IntVal($ID);
	static $cache = array();
	if (!array_key_exists($ID, $cache) && $ID > 0) {
		$rsSection = CIBlockSection::GetList(array(), array("ID"=>$ID), false, array("ID","IBLOCK_ID","NAME"));
		$cache[$ID] = $rsSection->GetNext();
	}
	return $cache[$ID];
}

function GetIBlockTypeID($IBLOCK_ID)
{
	$IBLOCK_ID = IntVal($IBLOCK_ID);
	static $cache = array();
	if (!array_key_exists($IBLOCK_ID, $cache)) {
		$rsIBlock = CIBlock::GetByID($IBLOCK_ID);
		if (!($cache[$IBLOCK_ID] = $rsIBlock->GetNext())) {
			$cache[$IBLOCK_ID] = array("IBLOCK_TYPE_ID" => "");
        }
	}
	return $cache[$IBLOCK_ID]["IBLOCK_TYPE_ID"];
}

if ($IBLOCK_ID <= 0) {
	$lAdmin->BeginPrologContent();
	$message = new CAdminMessage(array("MESSAGE"=>GetMessage("IBLOCK_ELSEARCH_CHOOSE_IBLOCK"), "TYPE"=>"OK"));
	echo $message->Show();
	$lAdmin->EndPrologContent();
}




$element = CIBlockElement::getByID($eventID)->getNextElement();

$event = null;
if ($element) {
    $event = $element->getFields();
    $event['PROPS'] = $element->getProperties();
    
    $currencies = ['EQUIPMENTS' => [], 'STANDS' => []];
    foreach ($event['PROPS'] as $code => $prop) {
        if (strpos($code, 'LANG_CURRENCY_') !== false) {
            $currecny_lang = str_replace('LANG_CURRENCY_', '', $code);
            
            $currencies['EQUIPMENTS'][$prop['VALUE']][$currecny_lang] = $currecny_lang;
        }
        if (strpos($code, 'LANG_STAND_CURRENCY_') !== false) {
            $currecny_lang = str_replace('LANG_STAND_CURRENCY_', '', $code);
            
            $currencies['STANDS'][$prop['VALUE']][$currecny_lang] = $currecny_lang;
        }
    }
}


// Цены.
$prices = [];

switch ($IBLOCK_ID) {
    
    // Цены для продукции.
    case (IBLOCK_PRODUCTS_ID):
        $oprices = Wolk\OEM\Prices\Product::getList([
            'filter' => [
                Wolk\OEM\Prices\Product::FIELD_EVENT => $context->getEventID(),
                Wolk\OEM\Prices\Product::FIELD_TYPE  => $context->getType(),
                Wolk\OEM\Prices\Product::FIELD_LANG  => $context->getLang(),
            ]
        ]);
        
        foreach ($oprices as $oprice) {
            $prices[$oprice->getProductID()] = $oprice->getPrice();
        }
        break;
        
    
    // Цены для стендов.
    case (IBLOCK_STANDS_ID):
        $oprices = Wolk\OEM\Prices\Stand::getList([
            'filter' => [
                Wolk\OEM\Prices\Stand::FIELD_EVENT => $context->getEventID(),
                Wolk\OEM\Prices\Stand::FIELD_TYPE  => $context->getType(),
                Wolk\OEM\Prices\Stand::FIELD_LANG  => $context->getLang(),
            ]
        ]);
        
        foreach ($oprices as $oprice) {
            $prices[$oprice->getStandID()] = $oprice->getPrice();
        }
        break;
}


while ($item = $result->GetNext()) {
	foreach ($arSelectedProps as $aProp) {
		if ($item["PROPERTY_".$aProp['ID'].'_ENUM_ID'] > 0) {
			$item["PROPERTY_".$aProp['ID']] = $item["PROPERTY_".$aProp['ID'].'_ENUM_ID'];
		} else {
			$item["PROPERTY_".$aProp['ID']] = $item["PROPERTY_".$aProp['ID'].'_VALUE'];
        }
	}

	$row =& $lAdmin->AddRow($item["ID"], $item);

	$row->AddViewField("NAME", $item["NAME"]."<input type=hidden name='n".$item["ID"]."' id='name_".$item["ID"]."' value='".CUtil::JSEscape(htmlspecialcharsbx($item["NAME"]))."'>");
	$row->AddViewField("USER_NAME", "[<a target=\"_blank\" href=\"user_edit.php?lang=".LANGUAGE_ID."&ID=".$item["MODIFIED_BY"]."\">".$item["MODIFIED_BY"]."</a>]&nbsp;".$item["USER_NAME"]);
	$row->AddCheckField("ACTIVE", false);
	$row->AddViewField("CREATED_USER_NAME", "[<a target=\"_blank\" href=\"user_edit.php?lang=".LANGUAGE_ID."&ID=".$item["CREATED_BY"]."\">".$item["CREATED_BY"]."</a>]&nbsp;".$item["CREATED_USER_NAME"]);
	$row->AddViewFileField("PREVIEW_PICTURE", array(
			"IMAGE" => "Y",
			"PATH" => "Y",
			"FILE_SIZE" => "Y",
			"DIMENSIONS" => "Y",
			"IMAGE_POPUP" => "Y",
			"MAX_SIZE" => $maxImageSize,
			"MIN_SIZE" => $minImageSize,
		)
	);
	$row->AddViewFileField("DETAIL_PICTURE", array(
			"IMAGE" => "Y",
			"PATH" => "Y",
			"FILE_SIZE" => "Y",
			"DIMENSIONS" => "Y",
			"IMAGE_POPUP" => "Y",
			"MAX_SIZE" => $maxImageSize,
			"MIN_SIZE" => $minImageSize,
		)
	);

	$row->AddViewField("WF_STATUS_ID", htmlspecialcharsbx(CIBlockElement::WF_GetStatusTitle($item["WF_STATUS_ID"]))."<input type=hidden name='n".$item["ID"]."' value='".CUtil::JSEscape($item["NAME"])."'>");
	$row->AddViewField("LOCKED_USER_NAME", '&nbsp;<a href="user_edit.php?lang='.LANGUAGE_ID.'&ID='.$item["WF_LOCKED_BY"].'" title="'.GetMessage("IBLOCK_ELSEARCH_USERINFO").'">'.$item["LOCKED_USER_NAME"].'</a>');

	$arProperties = array();
	if (count($arSelectedProps) > 0) {
		$rsProperties = CIBlockElement::GetProperty($IBLOCK_ID, $item["ID"]);
		while ($ar = $rsProperties->GetNext()) {
			if (!array_key_exists($ar["ID"], $arProperties)) {
				$arProperties[$ar["ID"]] = array();
            }
			$arProperties[$ar["ID"]][$ar["PROPERTY_VALUE_ID"]] = $ar;
		}
	}

	foreach ($arSelectedProps as $aProp) {
		if (strlen($aProp["USER_TYPE"]) > 0) {
			$arUserType = CIBlockProperty::GetUserType($aProp["USER_TYPE"]);
		} else {
			$arUserType = array();
        }
		$v = '';
		foreach ($arProperties[$aProp['ID']] as $property_value_id => $property_value) {
			$property_value['PROPERTY_VALUE_ID'] = intval($property_value['PROPERTY_VALUE_ID']);
			$VALUE_NAME = 'FIELDS['.$item["ID"].'][PROPERTY_'.$property_value['ID'].']['.$property_value['PROPERTY_VALUE_ID'].'][VALUE]';
			$DESCR_NAME = 'FIELDS['.$item["ID"].'][PROPERTY_'.$property_value['ID'].']['.$property_value['PROPERTY_VALUE_ID'].'][DESCRIPTION]';
			$res = '';
			if (array_key_exists("GetAdminListViewHTML", $arUserType)) {
				$res = call_user_func_array($arUserType["GetAdminListViewHTML"],
					array(
						$property_value,
						array(
							"VALUE" => $property_value["~VALUE"],
							"DESCRIPTION" => $property_value["~DESCRIPTION"]
						),
						array(
							"VALUE" => $VALUE_NAME,
							"DESCRIPTION" => $DESCR_NAME,
							"MODE"=>"iblock_element_admin",
							"FORM_NAME"=>"form_".$sTableID,
						),
					));
			} elseif ($aProp['PROPERTY_TYPE']=='F') {
				$res = CFileInput::Show('NO_FIELDS['.$property_value_id.']', $property_value["VALUE"], array(
					"IMAGE" => "Y",
					"PATH" => "Y",
					"FILE_SIZE" => "Y",
					"DIMENSIONS" => "Y",
					"IMAGE_POPUP" => "Y",
					"MAX_SIZE" => $maxImageSize,
					"MIN_SIZE" => $minImageSize,
					), array(
						'upload' => false,
						'medialib' => false,
						'file_dialog' => false,
						'cloud' => false,
						'del' => false,
						'description' => false,
					)
				);
			} elseif($aProp['PROPERTY_TYPE']=='G') {
				$t = GetSectionName($property_value["VALUE"]);
				if($t)
					$res = $t['NAME'].' [<a href="'.htmlspecialcharsbx(CIBlock::GetAdminSectionEditLink($t['IBLOCK_ID'], $t['ID'])).'" title="'.GetMessage("IBLOCK_ELSEARCH_SECTION_EDIT").'">'.$t['ID'].'</a>]';
			} elseif($aProp['PROPERTY_TYPE']=='E') {
				$t = GetElementName($property_value["VALUE"]);
				if ($t) {
					$res = $t['NAME'].' [<a href="'.htmlspecialcharsbx(CIBlock::GetAdminElementEditLink($t['IBLOCK_ID'], $t['ID'])).'" title="'.GetMessage("IBLOCK_ELSEARCH_ELEMENT_EDIT").'">'.$t['ID'].'</a>]';
				}
			} elseif($property_value['PROPERTY_TYPE']=='L') {
				$res = $property_value["VALUE_ENUM"];
			} else {
				$res = $property_value["VALUE"];
			}

			if ($res != "")
				$v .= ($v!=''?' / ':'').$res;
		}

		if ($v != "") {
			$row->AddViewField("PROPERTY_".$aProp['ID'], $v);
        }
		unset($arSelectedProps[$aProp['ID']]["CACHE"]);
	}
    
    $picture = null;
    if (!empty($item['PREVIEW_PICTURE'])) {
        $picture = CFile::getPath($item['PREVIEW_PICTURE']);
        if (!is_readable($_SERVER['DOCUMENT_ROOT'] . $picture)) {
            $picture = null;
        }
    }
        
    $item['PICTURE'] = $picture;
    $item['PRICE']   = (float) $prices[$item['ID']];
    
    switch ($IBLOCK_ID) {
        case (IBLOCK_PRODUCTS_ID):
            $title = $item['PROPERTY_LANG_TITLE_'.strtoupper($language).'_VALUE'];
            if (!empty($title)) {
                $item['NAME'] = $title;
            }
            break;
        case (IBLOCK_STANDS_ID):
            $title = $item['PROPERTY_LANG_NAME_'.strtoupper($language).'_VALUE'];
            if (!empty($title)) {
                $item['NAME'] = $title;
            }
            break;
    }
    
    $item['BASKET_ID'] = $basketID;
    
    if ($IBLOCK_ID == IBLOCK_PRODUCTS_ID) {
        
        $item['PRODUCT'] = [];
        
        // Раздел продукции.
        $section = new Wolk\OEM\Products\Section($item['IBLOCK_SECTION_ID']);
        
        // Тип цены.
        $item['PRODUCT']['PRICETYPE'] = $section->getPriceType();
        
        // Свойства продукта.
        $item['PRODUCT']['PROPS'] = array_filter($section->getProperties());
        
        $html = '';
        if (!empty($item['PRODUCT']['PROPS'])) {
            $html = '<div class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Свойства продукции</h4>
                                </div>
                                <div class="modal-body">';
            
            foreach ($item['PRODUCT']['PROPS'] as $prop) {
                $propfile = $_SERVER['DOCUMENT_ROOT'].'/local/modules/wolk.oem/admin/order/props/' . strtolower($prop) . '.php';
                
                if (is_readable($propfile)) {
                    $pid  = $item['ID'];
                    $pbid = $basketID;
                    
                    $prophtml = getfilehtml($propfile, $pid, $pbid);
                    if (!empty($prophtml)) {
                        $html .= $prophtml . '<ht/>';
                    }
                }
            }
            
            $html .= '  </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>';
        }
        
        $item['PRODUCT']['HTML'] = $html;
    }
    
    
	$row->AddActions(array(
		array(
			"DEFAULT" => "Y",
			"TEXT"    => GetMessage("IBLOCK_ELSEARCH_SELECT"),
			"ACTION"  => "javascript:SelEl('".CUtil::JSEscape($get_xml_id? $item['XML_ID'] : $item['ID'])."', '".CUtil::JSEscape($item["NAME"])."', '".$picture."', '".CUtil::JSEscape(json_encode($item))."')",
		),
	));
}

$lAdmin->AddFooter(
	array(
		array("title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value" => $result->SelectedRowsCount()),
		array("counter" => true, "title" => GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value" => "0"),
	)
);

if ($m) {
	$lAdmin->AddGroupActionTable(array(
		array(
			"action" => "SelAll()",
			"value" => "select",
			"type" => "button",
			"name" => GetMessage("IBLOCK_ELSEARCH_SELECT"),
			)
	), array("disable_action_target"=>true));
}

$lAdmin->AddAdminContextMenu(array(), false);

$lAdmin->CheckListMode();

/***************************************************************************
				HTML form
****************************************************************************/
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_popup_admin.php");
?>

<form name="form1" method="GET" action="<?= $APPLICATION->GetCurPage() ?>">
<?
function _ShowGroupPropertyField($name, $property_fields, $values)
{
	if(!is_array($values)) $values = array();

	$res = "";
	$bWas = false;
	$sections = CIBlockSection::GetTreeList(array("IBLOCK_ID"=>$property_fields["LINK_IBLOCK_ID"]), array("ID", "NAME", "DEPTH_LEVEL"));
	while ($ar = $sections->GetNext()) {
		$res .= '<option value="'.$ar["ID"].'"';
		if (in_array($ar["ID"], $values)) {
			$bWas = true;
			$res .= ' selected';
		}
		$res .= '>'.str_repeat(" . ", $ar["DEPTH_LEVEL"]).$ar["NAME"].'</option>';
	}
	echo '<select name="'.$name.'[]">';
	echo '<option value=""'.(!$bWas?' selected':'').'>'.GetMessage("IBLOCK_ELSEARCH_NOT_SET").'</option>';
	echo $res;
	echo '</select>';
}
$arFindFields = array();
if (!$iblockFix)
	$arFindFields['IBLOCK_ID'] = GetMessage('IBLOCK_ELSEARCH_IBLOCK');
$arFindFields["id"] = "ID";
$arFindFields["date"] = GetMessage("IBLOCK_ELSEARCH_F_DATE");
$arFindFields["chn"] = GetMessage("IBLOCK_ELSEARCH_F_CHANGED");

if (CModule::IncludeModule("workflow"))
	$arFindFields["stat"] = GetMessage("IBLOCK_ELSEARCH_F_STATUS");

if (is_array($arIBTYPE) && ($arIBTYPE["SECTIONS"] == "Y"))
	$arFindFields["sec"] = GetMessage("IBLOCK_ELSEARCH_F_SECTION");

$arFindFields["act"] = GetMessage("IBLOCK_ELSEARCH_F_ACTIVE");
$arFindFields["ext_id"] = GetMessage("IBLOCK_FIELD_EXTERNAL_ID");
$arFindFields["tit"] = GetMessage("IBLOCK_ELSEARCH_F_TITLE");
$arFindFields["code"] = GetMessage("IBLOCK_FIELD_CODE");
$arFindFields["dsc"] = GetMessage("IBLOCK_ELSEARCH_F_DSC");

foreach($arProps as $prop)
	if($prop["FILTRABLE"]=="Y" && $prop["PROPERTY_TYPE"]!="F")
		$arFindFields["p".$prop["ID"]] = $prop["NAME"];

$oFilter = new CAdminFilter($sTableID."_filter", $arFindFields);

$filterPath = $APPLICATION->GetCurPage().'?lang='.LANGUAGE_ID.'&get_xml_id='.($get_xml_id ? 'Y': 'N').'&k='.urlencode($k).'&n='.urlencode($n).'&m='.($m ? 'y': 'n').($boolDiscount ? '&discount=Y' : '').'&';
?>
<script type="text/javascript">
var arClearHiddenFields = [],
	blockedFilter = false;

function applyFilter(el)
{
	if (blockedFilter)
		return false;
	BX.adminPanel.showWait(el);
	<?= $sTableID."_filter"; ?>.OnSet('<?= CUtil::JSEscape($sTableID)?>', '<?= CUtil::JSEscape($filterPath); ?>');
	return false;
}

function deleteFilter(el)
{
	if (blockedFilter)
		return false;
	BX.adminPanel.showWait(el);
	if (0 < arClearHiddenFields.length) {
		for (var index = 0; index < arClearHiddenFields.length; index++) {
			if (undefined != window[arClearHiddenFields[index]]) {
				if ('ClearForm' in window[arClearHiddenFields[index]]) {
					window[arClearHiddenFields[index]].ClearForm();
				}
			}
		}
	}
	<?= $sTableID."_filter"?>.OnClear('<?= CUtil::JSEscape($sTableID) ?>', '<?= CUtil::JSEscape($APPLICATION->GetCurPage().'?type='.urlencode($type).'&IBLOCK_ID='.urlencode($IBLOCK_ID).'&lang='.LANGUAGE_ID.'&') ?>');
	return false;
}

function SelEl(id, name, image, element)
{
    window.opener.window['<?= $func ?>'](element);
    window.close();
    
    <? /*
	<? if ('' != $lookup) { ?>
        <? if ('' != $m) { ?>
			window.opener.<?= $lookup; ?>.AddValue(id);
        <? } else { ?>
            window.opener.<?= $lookup; ?>.AddValue(id);
            window.close();
        <? } ?>
	<? } else { ?>
        <?  if ($m) { ?>
            window.opener.InS<?= md5($n)?>(id, name);
        <? } else { ?>
            el = window.opener.document.getElementById('<?= $n?>[<?= $k?>]');
            console.log(el);
            if(!el)
                el = window.opener.document.getElementById('<?= $n?>');
            if (el) {
                el.value = id;
                if (window.opener.BX)
                    window.opener.BX.fireEvent(el, 'change');
            }
            el = window.opener.document.getElementById('sp_<?= md5($n) ?>_<?= $k ?>');
            if(!el)
                el = window.opener.document.getElementById('sp_<?= $n ?>');
            if(!el)
                el = window.opener.document.getElementById('<?= $n ?>_link');
            if(el)
                el.innerHTML = name + ' ' + image;
            window.close();
        <? } ?>
    <? } ?>
    */ ?>
}

function SelAll()
{
	var frm = document.getElementById('form_<?= $sTableID ?>');
	if (frm) {
		var e = frm.elements['ID[]'];
		if (e && e.nodeName) {
			var v = e.value;
			var n = document.getElementById('name_'+v).value;
			SelEl(v, n);
		} else if(e) {
			var l = e.length;
			for(i=0;i<l;i++)
			{
				var a = e[i].checked;
				if (a == true)
				{
					var v = e[i].value;
					var n = document.getElementById('name_'+v).value;
					SelEl(v, n);
				}
			}
		}
		window.close();
	}
}

function reloadFilter(el)
{
	var newUrl = '<?= CUtil::JSEscape($reloadUrl); ?>',
		iblockID = 0,
		btnSet = BX();

	if (!el)
		return;
	if (el.selectedIndex > 0) {
		iblockID = parseInt(el.value, 10);
		if (isNaN(iblockID))
			iblockID = 0;
		if (iblockID > 0 && iblockID != <?= $IBLOCK_ID; ?>) {
			blockedFilter = true;
			newUrl += ('&IBLOCK_ID=' + iblockID) + ('&filter_iblock_id=' + iblockID) + '&set_filter=y';
			location.href = newUrl;
		}
	}
}
</script>

<input type="hidden" name="func"      value="<?= $func; ?>" />
<input type="hidden" name="event"     value="<?= $eventID; ?>" />
<input type="hidden" name="language"  value="<?= $language; ?>" />
<input type="hidden" name="currency"  value="<?= $currency; ?>" />
<input type="hidden" name="typestand" value="<?= $typestand ?>" />

<? if ($iblockFix) { ?>
    <input type="hidden" name="IBLOCK_ID" value="<?= $IBLOCK_ID; ?>" />
	<input type="hidden" name="filter_iblock_id" value="<?= $IBLOCK_ID; ?>" />
<? } ?>
<? $oFilter->Begin(); ?>
<? if (!$iblockFix) { ?>
	<tr>
		<td><b><?= GetMessage("IBLOCK_ELSEARCH_IBLOCK")?></b></td>
		<td><?= GetIBlockDropDownListEx(
				$IBLOCK_ID,
				"filter_type",
				"filter_iblock_id",
				array('MIN_PERMISSION' => 'S'),
				'',
				'reloadFilter(this)'
			);?></td>
	</tr>
<?
}
?>
	<tr>
		<td><?= GetMessage("IBLOCK_ELSEARCH_FROMTO_ID")?></td>
		<td>
			<input type="text" name="filter_id_start" size="10" value="<?= htmlspecialcharsex($filter_id_start)?>">
			...
			<input type="text" name="filter_id_end" size="10" value="<?= htmlspecialcharsex($filter_id_end)?>">
		</td>
	</tr>

	<tr>
		<td  nowrap><? echo GetMessage("IBLOCK_FIELD_TIMESTAMP_X").":"?></td>
		<td nowrap><? echo CalendarPeriod("filter_timestamp_from", htmlspecialcharsex($filter_timestamp_from), "filter_timestamp_to", htmlspecialcharsex($filter_timestamp_to), "form1")?></td>
	</tr>

	<tr>
		<td nowrap><?=GetMessage("IBLOCK_FIELD_MODIFIED_BY")?>:</td>
		<td>
			<?= FindUserID(
				/*$tag_name=*/"filter_modified_user_id",
				/*$tag_value=*/$filter_modified_user_id,
				/*$user_name=*/"",
				/*$form_name=*/"form1",
				/*$tag_size=*/"5",
				/*$tag_maxlength=*/"",
				/*$button_value=*/" ... ",
				/*$tag_class=*/"",
				/*$button_class=*/""
			);?>
		</td>
	</tr>
	<? if (CModule::IncludeModule("workflow")) { ?>
        <tr>
            <td nowrap><?=GetMessage("IBLOCK_FIELD_STATUS")?>:</td>
            <td nowrap><input type="text" name="filter_status_id" value="<?= htmlspecialcharsex($filter_status_id)?>" size="3">
            <select name="filter_status">
            <option value=""><?=GetMessage("IBLOCK_VALUE_ANY")?></option>
            <? $rs = CWorkflowStatus::GetDropDownList("Y"); ?>
            <? while($arRs = $rs->GetNext()) { ?>
                <option value="<?=$arRs["REFERENCE_ID"]?>"<?if($filter_status == $arRs["~REFERENCE_ID"])echo " selected"?>><?=$arRs["REFERENCE"]?></option>
            <? } ?>
            </select></td>
        </tr>
	<? } ?>

	<?if(is_array($arIBTYPE) && ($arIBTYPE["SECTIONS"] == "Y")):?>
	<tr>
		<td nowrap><?= GetMessage("IBLOCK_FIELD_SECTION_ID")?>:</td>
		<td nowrap>
			<select name="filter_section">
				<option value=""><?= GetMessage("IBLOCK_VALUE_ANY")?></option>
				<option value="0"<?if($filter_section=="0")echo" selected"?>><?= GetMessage("IBLOCK_UPPER_LEVEL")?></option>
				<?
				$bsections = CIBlockSection::GetTreeList(array("IBLOCK_ID"=>$IBLOCK_ID), array("ID", "NAME", "DEPTH_LEVEL"));
				while($arSection = $bsections->GetNext()):
					?><option value="<?= $arSection["ID"]?>"<?if($arSection["ID"]==$filter_section)echo " selected"?>><?= str_repeat("&nbsp;.&nbsp;", $arSection["DEPTH_LEVEL"])?><?= $arSection["NAME"]?></option><?
				endwhile;
				?>
			</select><br>

			<input type="checkbox" name="filter_subsections" value="Y"<?if($filter_subsections=="Y")echo" checked"?>> <?= GetMessage("IBLOCK_ELSEARCH_INCLUDING_SUBSECTIONS")?>

		</td>
	</tr>
	<?endif?>

	<tr>
		<td nowrap><?= GetMessage("IBLOCK_FIELD_ACTIVE")?>:</td>
		<td nowrap>
			<select name="filter_active">
				<option value=""><?=htmlspecialcharsex(GetMessage('IBLOCK_VALUE_ANY'))?></option>
				<option value="Y"<?if($filter_active=="Y")echo " selected"?>><?=htmlspecialcharsex(GetMessage("IBLOCK_YES"))?></option>
				<option value="N"<?if($filter_active=="N")echo " selected"?>><?=htmlspecialcharsex(GetMessage("IBLOCK_NO"))?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?=GetMessage("IBLOCK_FIELD_EXTERNAL_ID")?>:</td>
		<td><input type="text" name="filter_external_id" value="<?= htmlspecialcharsex($filter_external_id)?>" size="30"></td>
	</tr>
	<tr>
		<td nowrap><?= GetMessage("IBLOCK_FIELD_NAME")?>:</td>
		<td nowrap>
			<input type="text" name="filter_name" value="<?= htmlspecialcharsex($filter_name)?>" size="30">
		</td>
	</tr>
	<tr>
		<td nowrap><?= GetMessage("IBLOCK_FIELD_CODE")?>:</td>
		<td nowrap>
			<input type="text" name="filter_code" value="<?= htmlspecialcharsex($filter_code)?>" size="30">
		</td>
	</tr>
	<tr>
		<td nowrap><?= GetMessage("IBLOCK_ELSEARCH_DESC")?></td>
		<td nowrap>
			<input type="text" name="filter_intext" size="50" value="<?= htmlspecialcharsex($filter_intext)?>" size="30">&nbsp;<?=ShowFilterLogicHelp()?>
		</td>
	</tr>
	<?
	foreach($arProps as $prop):
		if($prop["FILTRABLE"]!="Y" || $prop["PROPERTY_TYPE"]=="F")
			continue;
	?>
	<tr>
		<td><?=$prop["NAME"]?>:</td>
		<td>
			<?if(array_key_exists("GetAdminFilterHTML", $prop["PROPERTY_USER_TYPE"])):
			echo call_user_func_array($prop["PROPERTY_USER_TYPE"]["GetAdminFilterHTML"], array(
				$prop,
				array(
					"VALUE" => "find_el_property_".$prop["ID"],
					"TABLE_ID" => $sTableID,
				),
			));
			elseif($prop["PROPERTY_TYPE"]=='L'):?>
				<select name="find_el_property_<?=$prop["ID"]?>">
					<option value=""><?= GetMessage("IBLOCK_VALUE_ANY")?></option><?
					$dbrPEnum = CIBlockPropertyEnum::GetList(array("SORT"=>"ASC", "NAME"=>"ASC"), array("PROPERTY_ID"=>$prop["ID"]));
					while($arPEnum = $dbrPEnum->GetNext()):
					?>
						<option value="<?=$arPEnum["ID"]?>"<?if(${"find_el_property_".$prop["ID"]} == $arPEnum["ID"])echo " selected"?>><?=$arPEnum["VALUE"]?></option>
					<?
					endwhile;
			?></select>
			<?
			elseif($prop["PROPERTY_TYPE"]=='G'):
				_ShowGroupPropertyField('find_el_property_'.$prop["ID"], $prop, ${'find_el_property_'.$prop["ID"]});
			else:
				?>
				<input type="text" name="find_el_property_<?=$prop["ID"]?>" value="<?= htmlspecialcharsex(${"find_el_property_".$prop["ID"]})?>" size="30">&nbsp;<?=ShowFilterLogicHelp()?>
				<?
			endif;
			?>
		</td>
	</tr>
	<?endforeach;

$oFilter->Buttons();
?>
<span class="adm-btn-wrap"><input type="submit"  class="adm-btn" name="set_filter" value="<? echo GetMessage("admin_lib_filter_set_butt"); ?>" title="<? echo GetMessage("admin_lib_filter_set_butt_title"); ?>" onClick="return applyFilter(this);"></span>
<span class="adm-btn-wrap"><input type="submit"  class="adm-btn" name="del_filter" value="<? echo GetMessage("admin_lib_filter_clear_butt"); ?>" title="<? echo GetMessage("admin_lib_filter_clear_butt_title"); ?>" onClick="deleteFilter(this); return false;"></span>
<?$oFilter->End();?>
</form>
<?
$lAdmin->DisplayList();

if ($strWarning != '')
	ShowError($strWarning);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_popup_admin.php");