<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);
$curLang = strtoupper($this->getLanguageId());
$eventId = \Bitrix\Iblock\ElementTable::getRow([
    'filter' =>
        [
            'IBLOCK_ID' => $arParams["IBLOCK_ID"],
            "CODE"      => $arResult["VARIABLES"]["ELEMENT_CODE"]
        ],
    'select' =>
        [
            'ID'
        ]
])['ID'];
$obElement = CIBlockElement::GetByID($eventId);
if($obEvent = $obElement->GetNextElement()) {
    $event = $obEvent->GetFields();
    $event['PROPS'] = $obEvent->GetProperties();

    $logo = false;
    if($event['PROPS']['LANG_LOGO_'.$curLang]['VALUE']) {
        $logo = CFile::ResizeImageGet($event['PROPS']['LANG_LOGO_'.$curLang]['VALUE'], ['width' => 220, 'height' => 65])['src'];
    }
    $APPLICATION->AddViewContent('EVENT_LINK', '/events/'.$event['CODE'].'/');
    $APPLICATION->AddViewContent('EVENT_LOGO', $logo ?: '/local/templates/.default/build/images/header-logo.png');
    if($event['PROPS']['COLOR']['VALUE']) {
        $APPLICATION->AddViewContent('custom_color_styles', '.customizable {background: '.$event['PROPS']['COLOR']['VALUE'].'!important;} .customizable_border {border-color:'.$event['PROPS']['COLOR']['VALUE'].';}');
    }
}

$_SESSION['REGEVENT'] = $arResult['VARIABLES']['ELEMENT_CODE'];

?>
<? if (
    (isset($_REQUEST['ORDER_TYPE'])
        &&
        in_array($_REQUEST['ORDER_TYPE'], ['standart', 'individual']))
    ||
    (isset($_REQUEST['ORDER_ID']) && intval($_REQUEST['ORDER_ID']))
): ?>
    <? $APPLICATION->IncludeComponent(
        "wolk:event.detail",
        "",
        [
            "EVENT_ID"   => $eventId,
            "WIDTH"      => isset($_REQUEST['WIDTH']) ? intval($_REQUEST['WIDTH']) : null,
            "DEPTH"      => isset($_REQUEST['DEPTH']) ? intval($_REQUEST['DEPTH']) : null,
            "TYPE"       => isset($_REQUEST['standtype']) ? trim($_REQUEST['standtype']) : null,
            "ORDER_ID"   => isset($_REQUEST['ORDER_ID']) ? intval($_REQUEST['ORDER_ID']) : null,
            "ORDER_TYPE" => isset($_REQUEST['ORDER_TYPE']) ? $_REQUEST['ORDER_TYPE'] : null
        ]
    ); ?>
<? else: ?>
    <? $APPLICATION->IncludeComponent(
        "bitrix:catalog.element",
        "",
        [
            "IBLOCK_TYPE"                => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID"                  => $arParams["IBLOCK_ID"],
            "PROPERTY_CODE"              => $arParams["DETAIL_PROPERTY_CODE"],
            "META_KEYWORDS"              => $arParams["DETAIL_META_KEYWORDS"],
            "META_DESCRIPTION"           => $arParams["DETAIL_META_DESCRIPTION"],
            "BROWSER_TITLE"              => $arParams["DETAIL_BROWSER_TITLE"],
            "SET_CANONICAL_URL"          => $arParams["DETAIL_SET_CANONICAL_URL"],
            "BASKET_URL"                 => $arParams["BASKET_URL"],
            "ACTION_VARIABLE"            => $arParams["ACTION_VARIABLE"],
            "PRODUCT_ID_VARIABLE"        => $arParams["PRODUCT_ID_VARIABLE"],
            "SECTION_ID_VARIABLE"        => $arParams["SECTION_ID_VARIABLE"],
            "CHECK_SECTION_ID_VARIABLE"  => (isset($arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"]) ? $arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"] : ''),
            "PRODUCT_QUANTITY_VARIABLE"  => $arParams["PRODUCT_QUANTITY_VARIABLE"],
            "PRODUCT_PROPS_VARIABLE"     => $arParams["PRODUCT_PROPS_VARIABLE"],
            "CACHE_TYPE"                 => $arParams["CACHE_TYPE"],
            "CACHE_TIME"                 => $arParams["CACHE_TIME"],
            "CACHE_GROUPS"               => $arParams["CACHE_GROUPS"],
            "SET_TITLE"                  => $arParams["SET_TITLE"],
            "SET_LAST_MODIFIED"          => $arParams["SET_LAST_MODIFIED"],
            "MESSAGE_404"                => $arParams["MESSAGE_404"],
            "SET_STATUS_404"             => $arParams["SET_STATUS_404"],
            "SHOW_404"                   => $arParams["SHOW_404"],
            "FILE_404"                   => $arParams["FILE_404"],
            "PRICE_CODE"                 => $arParams["PRICE_CODE"],
            "USE_PRICE_COUNT"            => $arParams["USE_PRICE_COUNT"],
            "SHOW_PRICE_COUNT"           => $arParams["SHOW_PRICE_COUNT"],
            "PRICE_VAT_INCLUDE"          => $arParams["PRICE_VAT_INCLUDE"],
            "PRICE_VAT_SHOW_VALUE"       => $arParams["PRICE_VAT_SHOW_VALUE"],
            "USE_PRODUCT_QUANTITY"       => $arParams['USE_PRODUCT_QUANTITY'],
            "PRODUCT_PROPERTIES"         => $arParams["PRODUCT_PROPERTIES"],
            "ADD_PROPERTIES_TO_BASKET"   => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
            "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
            "LINK_IBLOCK_TYPE"           => $arParams["LINK_IBLOCK_TYPE"],
            "LINK_IBLOCK_ID"             => $arParams["LINK_IBLOCK_ID"],
            "LINK_PROPERTY_SID"          => $arParams["LINK_PROPERTY_SID"],
            "LINK_ELEMENTS_URL"          => $arParams["LINK_ELEMENTS_URL"],

            "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
            "OFFERS_FIELD_CODE"      => $arParams["DETAIL_OFFERS_FIELD_CODE"],
            "OFFERS_PROPERTY_CODE"   => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
            "OFFERS_SORT_FIELD"      => $arParams["OFFERS_SORT_FIELD"],
            "OFFERS_SORT_ORDER"      => $arParams["OFFERS_SORT_ORDER"],
            "OFFERS_SORT_FIELD2"     => $arParams["OFFERS_SORT_FIELD2"],
            "OFFERS_SORT_ORDER2"     => $arParams["OFFERS_SORT_ORDER2"],

            "ELEMENT_ID"               => $eventId,
            "SECTION_ID"               => $arResult["VARIABLES"]["SECTION_ID"],
            "SECTION_CODE"             => $arResult["VARIABLES"]["SECTION_CODE"],
            "SECTION_URL"              => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
            "DETAIL_URL"               => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
            'CONVERT_CURRENCY'         => $arParams['CONVERT_CURRENCY'],
            'CURRENCY_ID'              => $arParams['CURRENCY_ID'],
            'HIDE_NOT_AVAILABLE'       => $arParams["HIDE_NOT_AVAILABLE"],
            'USE_ELEMENT_COUNTER'      => $arParams['USE_ELEMENT_COUNTER'],
            'SHOW_DEACTIVATED'         => $arParams['SHOW_DEACTIVATED'],
            "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],

            'ADD_PICT_PROP'             => $arParams['ADD_PICT_PROP'],
            'LABEL_PROP'                => $arParams['LABEL_PROP'],
            'OFFER_ADD_PICT_PROP'       => $arParams['OFFER_ADD_PICT_PROP'],
            'OFFER_TREE_PROPS'          => $arParams['OFFER_TREE_PROPS'],
            'PRODUCT_SUBSCRIPTION'      => $arParams['PRODUCT_SUBSCRIPTION'],
            'SHOW_DISCOUNT_PERCENT'     => $arParams['SHOW_DISCOUNT_PERCENT'],
            'SHOW_OLD_PRICE'            => $arParams['SHOW_OLD_PRICE'],
            'SHOW_MAX_QUANTITY'         => $arParams['DETAIL_SHOW_MAX_QUANTITY'],
            'MESS_BTN_BUY'              => $arParams['MESS_BTN_BUY'],
            'MESS_BTN_ADD_TO_BASKET'    => $arParams['MESS_BTN_ADD_TO_BASKET'],
            'MESS_BTN_SUBSCRIBE'        => $arParams['MESS_BTN_SUBSCRIBE'],
            'MESS_BTN_COMPARE'          => $arParams['MESS_BTN_COMPARE'],
            'MESS_NOT_AVAILABLE'        => $arParams['MESS_NOT_AVAILABLE'],
            'USE_VOTE_RATING'           => $arParams['DETAIL_USE_VOTE_RATING'],
            'VOTE_DISPLAY_AS_RATING'    => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
            'USE_COMMENTS'              => $arParams['DETAIL_USE_COMMENTS'],
            'BLOG_USE'                  => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
            'BLOG_URL'                  => (isset($arParams['DETAIL_BLOG_URL']) ? $arParams['DETAIL_BLOG_URL'] : ''),
            'BLOG_EMAIL_NOTIFY'         => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : ''),
            'VK_USE'                    => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
            'VK_API_ID'                 => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
            'FB_USE'                    => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
            'FB_APP_ID'                 => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
            'BRAND_USE'                 => (isset($arParams['DETAIL_BRAND_USE']) ? $arParams['DETAIL_BRAND_USE'] : 'N'),
            'BRAND_PROP_CODE'           => (isset($arParams['DETAIL_BRAND_PROP_CODE']) ? $arParams['DETAIL_BRAND_PROP_CODE'] : ''),
            'DISPLAY_NAME'              => (isset($arParams['DETAIL_DISPLAY_NAME']) ? $arParams['DETAIL_DISPLAY_NAME'] : ''),
            'ADD_DETAIL_TO_SLIDER'      => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
            'TEMPLATE_THEME'            => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
            "ADD_SECTIONS_CHAIN"        => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
            "ADD_ELEMENT_CHAIN"         => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
            "DISPLAY_PREVIEW_TEXT_MODE" => (isset($arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE']) ? $arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] : ''),
            "DETAIL_PICTURE_MODE"       => (isset($arParams['DETAIL_DETAIL_PICTURE_MODE']) ? $arParams['DETAIL_DETAIL_PICTURE_MODE'] : ''),
            'ADD_TO_BASKET_ACTION'      => $basketAction,
            'SHOW_CLOSE_POPUP'          => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
            'DISPLAY_COMPARE'           => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
            'COMPARE_PATH'              => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['compare'],
            'SHOW_BASIS_PRICE'          => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y'),
            'BACKGROUND_IMAGE'          => (isset($arParams['DETAIL_BACKGROUND_IMAGE']) ? $arParams['DETAIL_BACKGROUND_IMAGE'] : '')
        ],
        $component
    ); ?>
<? endif; ?>