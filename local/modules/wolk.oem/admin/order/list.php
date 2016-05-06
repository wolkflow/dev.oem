<?php

use Bitrix\Sale\Helpers\Admin\OrderEdit;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Helpers\Admin\Blocks;

// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

// подключим языковой файл
IncludeModuleLangFile(__FILE__);


$permission = $APPLICATION->GetGroupRight('wolk.oem');

if ($permission == 'D') {
	$APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));
}


$groups = $USER->GetUserGroupArray();

$ismanager = false;
if (in_array(GROUP_MANAGERS_ID, $groups)) {
	$ismanager = true;
}


if (!\Bitrix\Main\Loader::includeModule('wolk.core')) {
    ShowError('Модуль wolk.core не устанволен.');
    return;
}

if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    ShowError('Модуль iblock не устанволен.');
    return;
}

if (!\Bitrix\Main\Loader::includeModule('sale')) {
    ShowError('Модуль sale не устанволен.');
    return;
}


function CheckFilter()
{
	global $filters, $ladmin;
  
	foreach ($filters as $f) {
		global $$f;
	}
	return (count($ladmin->arFilterErrors) == 0);
}


$table = "table_ordes";
//$sorting = new CAdminSorting($table, "LAST_LOGIN", "desc");
$ladmin  = new CAdminList($table, $sorting);

$filters = array(
	"find_id",
	"find_status",
	"find_company",
	"find_event",
	"find_date_order_from",
	"find_date_order_to",
	"find_bill",
);

$ladmin->InitFilter($filters);


// Статусы заказа.
$statuses = Wolk\Core\Helpers\SaleOrder::getStatuses();



// Фильтр.
$filter = array();

if (CheckFilter()) {
	
	// Фильтр по номеру.
	if (!empty($find_id)) {
		$filter['ID'] = (int) $find_id;
	}
	
	// Фильтр по статусу.
	if (!empty($find_status)) {
		$filter['STATUS_ID'] = array_map('strval', (array) $find_status);
	}
	
	// Фильтр по названию компании.
	if (!empty($find_company)) {
		$rids = array();
		$result = CUser::GetList($b='ID', $o='ASC', ['WORK_COMPANY' => '%'.$find_company.'%'], ['SELECT' => ['ID']]);
		while ($item = $result->Fetch()) {
			$rids []= (int) $item['ID'];
		}
		unset($result, $item);
		
		if (empty($rids)) {
			$filter['ID'] = '0';
		} else {
			$filter['USER_ID'] = array_unique($rids);
		}
	}
	
	// Фильтр по названию выставки.
	if (!empty($find_event)) {
		$rids = array();
		$result = CSaleOrderPropsValue::GetList([], ['CODE' => 'eventName', '%VALUE' => $find_event], false, false, ['ORDER_ID']);
		while ($item = $result->Fetch()) {
			$rids []= (int) $item['ORDER_ID'];
		}
		unset($result, $item);
		
		$rids = array_unique(array_filter($rids));
		
		if (empty($rids)) {
			$filter['ID'] = '0';
		} else {
			$filter['ID'] = (!empty($filter['ID'])) ? (array_intersect((array) $filter['ID'], $rids)) : ($rids);
		}
	}
	
	// Фильтр по дате создания заказа (от).
	if (!empty($find_date_order_from)) {
		$filter['>=DATE_INSERT'] = $find_date_order_from;
	}
	
	// Фильтр по дате создания заказа (до).
	if (!empty($find_date_order_to)) {
		$filter['<=DATE_INSERT'] = $find_date_order_to;
	}
	
	// Фильтр по номеру счета.
	if (!empty($find_bill)) {
		$rids = array();
		$result = CSaleOrderPropsValue::GetList([], ['CODE' => 'BILL', '%VALUE' => $find_bill], false, false, ['ORDER_ID']);
		while ($item = $result->Fetch()) {
			$rids []= (int) $item['ORDER_ID'];
		}
		unset($result, $item);
		
		$rids = array_unique(array_filter($rids));
		
		if (empty($rids)) {
			$filter['ID'] = '0';
		} else {
			$filter['ID'] = (!empty($filter['ID'])) ? (array_intersect((array) $filter['ID'], $rids)) : ($rids);
		}
	}
}

if ($ismanager) {
	$eids = array();
	$result = CIBlockElement::GetList([], ['IBLOCK_ID' => EVENTS_IBLOCK_ID, 'ACTIVE' => 'Y', 'PROPERTY_MANAGERS' => $USER->getID()], false, false, ['ID']);
	while ($item = $result->Fetch()) {
		$eids []= (int) $item['ID'];
	}
	unset($result, $item);
	
	$eids = array_unique(array_filter($eids));
	
	$rids = array();
	$result = CSaleOrderPropsValue::GetList([], ['CODE' => 'eventId', '@VALUE' => $eids], false, false, ['ORDER_ID']);
	while ($item = $result->Fetch()) {
		$rids []= (int) $item['ORDER_ID'];
	}
	unset($result, $item, $eids);
	
	$rids = array_unique(array_filter($rids));
	
	if (empty($rids)) {
		$filter['ID'] = '0';
	} else {
		$filter['ID'] = (!empty($filter['ID'])) ? (array_intersect((array) $filter['ID'], $rids)) : ($rids);
	}
}



$params = array(
	'order'	 => array('ID' => 'DESC'),
	'filter' => $filter
);

// Список заказов.
$result = new CAdminResult(\Bitrix\Sale\Internals\OrderTable::getList($params), $table);

// Аналогично CDBResult инициализируем постраничную навигацию.
$result->NavStart();

// Отправим вывод переключателя страниц в основной объект.
$ladmin->NavText($result->GetNavPrint('Заказы'));



// Заголовки списка.
$ladmin->AddHeaders(array(
	array( 
		"id"    	=> 'ID',
		"content"   => Loc::getMessage('HEADER_ID'),
		"sort"      => 'ID',
		"default"   => true,
	),
	array( 
		"id"    	=> 'EVENT',
		"content"   => Loc::getMessage('HEADER_EVENT'),
		"sort"      => 'EVENT',
		"default"   => true,
	),
	array( 
		"id"    	=> 'COMPANY',
		"content"   => Loc::getMessage('HEADER_COMPANY'),
		"sort"      => false,
		"default"   => true,
	),
	array( 
		"id"    	=> 'PRICE',
		"content"   => Loc::getMessage('HEADER_PRICE'),
		"sort"      => false,
		"default"   => true,
	),
	array( 
		"id"    	=> 'BILL',
		"content"   => Loc::getMessage('HEADER_BILL'),
		"sort"      => false,
		"default"   => true,
	),
	array( 
		"id"    	=> 'STATUS',
		"content"   => Loc::getMessage('HEADER_STATUS'),
		"sort"      => 'STATUS',
		"default"   => true,
	),
	array( 
		"id"    	=> 'DATE',
		"content"   => Loc::getMessage('HEADER_DATE'),
		"sort"      => 'DATE',
		"default"   => true,
	),
));



while ($item = $result->NavNext(true, "f_")) {
    
	// создаем строку. результат - экземпляр класса CAdminListRow
	$row =& $ladmin->AddRow($item['ID'], $item); 
	
	
	$oemorder = new Wolk\OEM\Order($item['ID']);
	$props    = Wolk\Core\Helpers\SaleOrder::getProperties($item['ID']);
	$user	  = CUser::getByID($item['USER_ID'])->Fetch();
	$event    = $oemorder->getEvent();
	
	$row->AddViewField('EVENT', $event['NAME']);
	$row->AddViewField('COMPANY', $user['WORK_COMPANY']);
	$row->AddViewField('PRICE', CurrencyFormat($item['PRICE'], $item['CURRENCY']));
	$row->AddViewField('BILL', ((!empty($props['BILL']['VALUE'])) ? ($props['BILL']['VALUE']) : ('&mdash;')));
	$row->AddViewField('STATUS', $statuses[$item['STATUS_ID']]['NAME']);
	$row->AddViewField('DATE', $item['DATE_INSERT']);
  
	// Сформируем контекстное меню.
	$actions = array();

	// редактирование элемента
	$actions []= array(
		"ICON"		=> 'view',
		"DEFAULT"	=> true,
		"TEXT"		=> 'Просмотр',
		"ACTION"	=> $ladmin->ActionRedirect("/bitrix/admin/wolk_oem_order_index.php?ID=".$f_ID)
	);
  
	$row->AddActions($actions);
}



$context = array();

$ladmin->AddAdminContextMenu($context);
$ladmin->CheckListMode();



// Сформируем меню.
$context = array();

// Прикрепим его к списку
$ladmin->AddAdminContextMenu($context);


// альтернативный вывод
$ladmin->CheckListMode();

// установим заголовок страницы
$APPLICATION->SetTitle(Loc::GetMessage('ORDERS'));

// не забудем разделить подготовку данных и вывод
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");


$formfilter = new CAdminFilter(
	$table."_filter",
	array(
		Loc::getMessage('ORDER_ID'),
		Loc::getMessage('ORDER_STATUS'),
		Loc::getMessage('ORDER_COMPANY'),
		Loc::getMessage('ORDER_EVENT'),
		Loc::getMessage('ORDER_DATE'),
		Loc::getMessage('ORDER_BILL'),
	)
);

?>
<form name="find_form" method="get" action="<?= $APPLICATION->GetCurPage() ?>">
	<? $formfilter->Begin() ?>
	<tr>
		<td><b><?= Loc::getMessage('ORDER_ID') ?>:</b></td>
		<td>
			<input type="text" size="25" name="find_id" value="<?= htmlspecialchars($find_id) ?>" />
		</td>
	</tr>
	<tr>
		<td><?= Loc::getMessage('ORDER_STATUS') ?>:</td>
		<td>
			<select name="find_status[]" multiple="multiple" size="6">
				<? foreach ($statuses as $status) { ?>
					<option value="<?= $status['ID'] ?>" <?= (in_array($status['ID'], $find_status)) ? ('selected') : ('') ?>>
						<?= $status['NAME'] ?>
					</option>
				<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td><b><?= Loc::getMessage('ORDER_COMPANY') ?>:</b></td>
		<td>
			<input type="text" size="25" name="find_company" value="<?= htmlspecialchars($find_company) ?>" />
		</td>
	</tr>
	<tr>
		<td><b><?= Loc::getMessage('ORDER_EVENT') ?>:</b></td>
		<td>
			<input type="text" size="25" name="find_event" value="<?= htmlspecialchars($find_event) ?>" />
		</td>
	</tr>
	<tr>
		<td><b><?= Loc::getMessage('ORDER_DATE') ?>:</b></td>
		<td>
			<?= CalendarPeriod('find_date_order_from', htmlspecialcharsex($find_date_order_from), 'find_date_order_to', htmlspecialcharsex($find_date_order_to), 'find_form', 'Y') ?>
		</td>
	</tr>
	<tr>
		<td><b><?= Loc::getMessage('ORDER_BILL') ?>:</b></td>
		<td>
			<input type="text" size="25" name="find_bill" value="<?= htmlspecialchars($find_bill) ?>" />
		</td>
	</tr>
	
	<? $formfilter->Buttons(array("table_id" => $table, "url" => $APPLICATION->GetCurPage(), "form" => "find_form")) ?>
	<? $formfilter->End() ?>
</form>

<? $ladmin->DisplayList(); ?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>