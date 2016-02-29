<?php

use Bitrix\Sale\Helpers\Admin\OrderEdit;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Helpers\Admin\Blocks;

// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

// подключим языковой файл
IncludeModuleLangFile(__FILE__);


$permission = $APPLICATION->GetGroupRight('sale');

if ($permission == 'D') {
	$APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));
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
	"find_bill",
	"find_status",
);

$ladmin->InitFilter($filters);




// Статусы заказа.
$statuses = Wolk\Core\Helpers\SaleOrder::getStatuses();




// Фильтр.
$filter = array();

if (CheckFilter()) {
	if (!empty($find_id)) {
		$filter['ID'] = (int) $find_id;
	}

	if (!empty($find_bill)) {
		//$filter['ID'] = (string) $find_bill;
	}
	
	if (!empty($find_status)) {
		$filter['STATUS_ID'] = array_map('strval', (array) $find_status);
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
));



while ($item = $result->NavNext(true, "f_")) {
    
	// создаем строку. результат - экземпляр класса CAdminListRow
	$row =& $ladmin->AddRow($item['ID'], $item); 
	
	
	$oemorder = new Wolk\OEM\Order($item['ID']);
	$props    = Wolk\Core\Helpers\SaleOrder::getProperties($item['ID']);
	$user	  = CUser::getByID($item['USER_ID'])->Fetch();
	
	
	$row->AddViewField('COMPANY', $user['WORK_COMPANY']);
	$row->AddViewField('PRICE', CurrencyFormat($item['PRICE'], $item['CURRENCY']));
	$row->AddViewField('BILL', ((!empty($props['BILL']['VALUE'])) ? ($props['BILL']['VALUE']) : ('&mdash;')));
	$row->AddViewField('STATUS', $statuses[$item['STATUS_ID']]['NAME']); 
  
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
		//Loc::getMessage('ORDER_BILL'),
		Loc::getMessage('ORDER_STATUS'),
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
	<? /*
	<tr>
		<td><?= Loc::getMessage('ORDER_BILL') ?>:</td>
		<td>
			<input type="text" name="find_bill" size="40" value="<?= htmlspecialchars($find_bill) ?>" />
		</td>
	</tr>
	*/ ?>
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
	<? $formfilter->Buttons(array("table_id" => $table, "url" => $APPLICATION->GetCurPage(), "form" => "find_form")) ?>
	<? $formfilter->End() ?>
</form>

<? $ladmin->DisplayList(); ?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>