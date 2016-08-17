<?
/** @var $this CBitrixComponent */
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use Bitrix\Main\Web\Json;
use Wolk\Core\Helpers\ArrayHelper;

$curLang = strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage());

$event = ArrayHelper::only($arResult['EVENT'], [
    'ID',
    'NAME',
    'CODE',
    'SURCHARGE',
    'CURRENCY',
    'ALL_PRICES'
]);
$event['SCHEDULE'] = $arResult['EVENT']['PROPS']["LANG_SCHEDULE_{$curLang}"]['~VALUE']['TYPE'] == 'text'
    ? nl2br($arResult['EVENT']['PROPS']["LANG_SCHEDULE_{$curLang}"]['~VALUE']['TEXT'])
    : $arResult['EVENT']['PROPS']["LANG_SCHEDULE_{$curLang}"]['~VALUE']['TEXT'];
$event['MANAGER_CONTACTS'] = $arResult['EVENT']['PROPS']["LANG_MANAGER_CONTACTS_{$curLang}"]['~VALUE'];
$event['MARGIN_DATES'] = array_combine(
    $arResult['EVENT']['PROPS']["MARGIN_DATES"]['VALUE'],
    $arResult['EVENT']['PROPS']["MARGIN_DATES"]['DESCRIPTION']
);

$event['LOCATION'] = $arResult['EVENT']['PROPS']["LANG_LOCATION_{$curLang}"]['VALUE'];


$event = Json::encode($event);

if ($arResult['INDIVIDUAL_STAND']) {
    $preselect = 0; // "'" . 'individual' . "'";
    $arResult['ITEMS']['individual'] = [
        'ID'        => 0,
        'NAME'      => Loc::getMessage('Individual Stand'),
        'EQUIPMENT' => []
    ];
} else {
    if ($arResult['ORDER']) {
        $preselect = $arResult['ORDER']['selectedStand']['PRODUCT_ID'];
    } else {
        if (
            $arResult['EVENT']['PROPS']['PRESELECT']['VALUE']
            && array_key_exists($arResult['EVENT']['PROPS']['PRESELECT']['VALUE'], $arResult['ITEMS'])
        ) {
            $preselect = $arResult['EVENT']['PROPS']['PRESELECT']['VALUE'];
        } else {
            $preselect = 0; // 'null';
        }
    }
}

if (empty($preselect)) {
	$preselect = 0; // 'null';
}
// $preselect = 0; // json_encode(['ID' => 0]);

$stands = array_map(function ($val) use ($curLang) {
    $item = ArrayHelper::only($val, [
        'ID',
        'NAME',
        'PREVIEW_PICTURE',
        'PREVIEW_TEXT',
        'PROPS'
    ]);
    $item['PRICE'] = ArrayHelper::only($val['PRICE'], ['PRICE', 'CURRENCY']);
    $item['BASE_PRICE'] = ArrayHelper::only($val['BASE_PRICE'], ['PRICE', 'CURRENCY']);

    $item['EQUIPMENT'] = array_map(function ($eq) use ($val, $curLang) {
        return ArrayHelper::only($eq,
            [
                'ID',
                'COUNT',
                'NAME',
                'SORT',
                'PREVIEW_PICTURE',
                'PREVIEW_PICTURE_SMALL',
                'PREVIEW_TEXT',
                'PRICE',
                'SKETCH_IMAGE',
                'SKETCH_TYPE',
                'WIDTH',
                'HEIGHT',
                'PROPS'
            ]
        ) + ['QUANTITY' => $eq['COUNT']];
    }, $val['OFFER']['EQUIPMENT']) ?: [];

    $item['SERVICES'] = new ArrayObject;
    $item['OPTIONS']  = new ArrayObject;

    return $item;
}, $arResult['ITEMS']);

/*
foreach ($stands as &$stand) {
	foreach ($stand['EQUIPMENT'] as $eq => $equipment) {
		$stand['EQUIPMENT'][$eq]['PROPS']['LANG_DESCRIPTION_'.$curLang]['~VALUE']['TEXT'] = nl2br($equipment['PROPS']['LANG_DESCRIPTION_'.$curLang]['VALUE']['TEXT']);
	}
}
*/

if ($arResult['ORDER']) {
    foreach ($arResult['ORDER']['EQUIPMENT'] as $orderedEq) {
        foreach ($stands[$arResult['ORDER']['selectedStand']['PRODUCT_ID']]['EQUIPMENT'] as &$eq) {
            if ($eq['ID'] == $orderedEq['PRODUCT_ID']) {
                $eq['QUANTITY'] = intval($orderedEq['QUANTITY']);
            }
        }
    }
    $stands[$arResult['ORDER']['selectedStand']['PRODUCT_ID']]['SERVICES'] = $arResult['ORDER']['selectedStand']['SERVICES'];
    $stands[$arResult['ORDER']['selectedStand']['PRODUCT_ID']]['OPTIONS'] = $arResult['ORDER']['selectedStand']['OPTIONS'];
    unset($eq);
}

// Добавление обработчика в случае отсутствия предвыбранного стенда.
$stands[0] = ['ID' => 0, 'PRICE' => ['PRICE' => 0], 'EQUIPMENT' => [], 'SERVICES' => []];
 
$stands = Json::encode($stands);

#dump($arResult['ORDER']);

$order = $arResult['ORDER'] ? Json::encode($arResult['ORDER']) : "null";

$steps = [
    [
        'NUM' => 1,
        'NAME' => Loc::getMessage('stand type')
    ],
	/*
    [
        'NUM' => 2,
        'NAME' => Loc::getMessage('standard equipment')
    ],*/
    [
        'NUM' => 2,
        'NAME' => Loc::getMessage('equipment')
    ],
    [
        'NUM' => 3,
        'NAME' => Loc::getMessage('services')
    ],
    [
        'NUM' => 4,
        'NAME' => Loc::getMessage('sketch')
    ],
    [
        'NUM' => 5,
        'NAME' => Loc::getMessage('order')
    ]
];

$steps = Json::encode($steps);

$selectedParams = $arParams['ORDER_ID'] ?
    [
        'WIDTH'  => $arResult['ORDER']['PROPS']['width']['VALUE'],
        'DEPTH'  => $arResult['ORDER']['PROPS']['depth']['VALUE'],
        'TYPE'   => $arResult['ORDER']['PROPS']['standType']['VALUE'],
        'SKETCH' => isset($arResult['ORDER']['PROPS']['sketch'])
            ? Json::decode($arResult['ORDER']['PROPS']['sketch']['VALUE'])
            : ''
    ]
    : ArrayHelper::only($arParams, ['WIDTH', 'DEPTH', 'TYPE']);


$colors = ArrayHelper::index($arResult['EVENT']['COLORS_PALETTE'], 'UF_XML_ID');
foreach ($colors as &$color) {
    $color['UF_SORT'] = (int)$color['UF_SORT'];
    if ($color['UF_BACKGROUND']) {
        $color['UF_BACKGROUND'] = CFile::GetFileArray($color['UF_BACKGROUND'])['SRC'];
    }
}
unset($color);


$extents = ArrayHelper::index($arResult['EVENT']['EXTENTS'], 'UF_XML_ID');


// file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log.txt', print_r($arResult['SERVICES'][ADDITIONAL_EQUIPMENT_SECTION_ID], true));

$colors      = Json::encode($colors);
$extents     = Json::encode($extents);
$options     = Json::encode($arResult['SERVICES'][ADDITIONAL_EQUIPMENT_SECTION_ID]);
$services    = Json::encode($arResult['SERVICES'][ADDITIONAL_SERVICES_SECTION_ID]);
$allServices = Json::encode($arResult['EVENT']['ALL_SERVICES']);
$vat         = VAT_DEFAULT;


$langs = Json::encode([
	'filePlaceholder' => Loc::getMessage('file_placeholder'),
	'fileNumber' => Loc::getMessage('file_number'),
	'selectPlaceholder' => Loc::getMessage('select_placeholder'),
	'selectSearchNotFound' => Loc::getMessage('search_not_found'),
	'selectSearchPlaceholder' => Loc::getMessage('search_placeholder'),
	'equipment' => Loc::getMessage('equipment'),
	'ordered' => Loc::getMessage('ordered'),
	'placed' => Loc::getMessage('placed'),
	'shelfPopupLabel' => Loc::getMessage('shelf_popup_label'),
]);

$standTypes = ['row', 'head', 'corner', 'island'];
$langMessages = Json::encode([
    array_combine(
        $standTypes,
        array_map(function($val) {
            return Loc::getMessage($val);
        }, $standTypes)
    )
]);

$individual = (int) $arResult['INDIVIDUAL_STAND'];

//$orderDesc = str_replace(['"', "\n"], ['\"', " "], $arResult['ORDER']['ORDER_DATA']['USER_DESCRIPTION']);

$selectedParams = Json::encode($selectedParams);

$am = Asset::getInstance();



$am->addString(<<<JS
	<script>
	    var curEvent = $event,
	        selected = $preselect,
			steps = $steps,
			stands = $stands,
			order = $order,
			selectedParams = $selectedParams,
			curLang = "$curLang",
			colors = $colors,
			services = $services,
			options = $options,
			allServices = $allServices,
			extents = $extents,
			vat = $vat,
			langs = $langs,
			langMessages = $langMessages,
            individual = $individual;
        
        curEvent.LOCATION = curEvent.LOCATION.replace(/&quot;/g, '"');
	</script>
JS
, true, AssetLocation::AFTER_JS_KERNEL);

$am->addString('<script src="/local/templates/.default/javascripts/vue.js"></script>');
$am->addJs('/local/templates/.default/javascripts/jquery-ui.min.js');
$am->addJs('/local/templates/.default/build/js/jquery.fileupload.js');
$am->addJs('/local/templates/.default/build/js/jquery.iframe-transport.js');
$am->addJs('/local/templates/.default/build/js/jquery.tooltipster.min.js');
$am->addJs('/local/templates/.default/build/js/jquery.airStickyBlock.min.js');
$am->addJs('/local/templates/.default/build/js/jquery.inputmask.bundle.js');
$am->addJs('/local/templates/.default/build/js/jquery.pickmeup.min.js');
$am->addCss('/local/templates/.default/build/css/mdp.css');
$am->addCss('/local/templates/.default/build/css/jquery-ui.css');
$am->addCss('/local/templates/.default/build/css/pepper-ginder-custom.css');
$am->addCss('/assets/css/sketch.css');
$am->addCss('/local/templates/.default/build/css/pickmeup.css');
