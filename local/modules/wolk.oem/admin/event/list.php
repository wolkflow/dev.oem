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
if (in_array(GROUP_MANAGERS_ID, $groups) || in_array(GROUP_PARTNERS_ID, $groups)) {
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
	"find_title",
);

$ladmin->InitFilter($filters);



// Фильтр.
$filter = array();

if (CheckFilter()) {
	
	// Фильтр по номеру.
	if (!empty($find_id)) {
		$filter['ID'] = (int) $find_id;
	}
	
	// Фильтр по статусу.
	if (!empty($find_title)) {
		$filter []= [
			'LOGIC' => 'OR',
			['NAME' => '%'.$find_title.'%'],
			['PROPERTY_LANG_TITLE_RU' => '%'.$find_title.'%'],
			['PROPERTY_LANG_TITLE_EN' => '%'.$find_title.'%'],
		];
	}
}

if ($ismanager) {
	$filter['PROPERTY_MANAGER'] = $USER->getID();
}



$params = array(
	'order'	 => array('ID' => 'DESC'),
	'filter' => $filter
);

// Список заказов.
$result = new CAdminResult(\Wolk\OEM\Event::getList($params, false), $table);

// Аналогично CDBResult инициализируем постраничную навигацию.
$result->NavStart();

// Отправим вывод переключателя страниц в основной объект.
$ladmin->NavText($result->GetNavPrint(Loc::getMessage('EVENTS')));



// Заголовки списка.
$ladmin->AddHeaders(array(
	array( 
		"id"    	=> 'ID',
		"content"   => Loc::getMessage('HEADER_ID'),
		"sort"      => 'ID',
		"default"   => true,
	),
	array( 
		"id"    	=> 'TITLE',
		"content"   => Loc::getMessage('HEADER_TITLE'),
		"sort"      => 'TITLE',
		"default"   => true,
	)
));



while ($item = $result->NavNext(true, "f_")) {
	
	// Мероприятие.
	$event = new Wolk\OEM\Event($item['ID']);
	
	// создаем строку. результат - экземпляр класса CAdminListRow
	$row =& $ladmin->AddRow($event->getID()); 
	
	$row->AddViewField('ID',    $event->getID());
	$row->AddViewField('TITLE', $event->getName());
	
	// Контекстное меню.
	$actions = array();

	// Просмотр элемента.
	$actions []= array(
		"ICON"		=> '',
		"DEFAULT"	=> true,
		"TEXT"		=> Loc::getMessage('ACTION_PRICELIST'),
		"ACTION"	=> "javascript: location.href = '/bitrix/admin/wolk_oem_remote.php?action=pricelist&eid=" . $event->getID() . "';",
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
$APPLICATION->SetTitle(Loc::GetMessage('EVENTS'));

// не забудем разделить подготовку данных и вывод
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");


$formfilter = new CAdminFilter(
	$table."_filter",
	array(
		Loc::getMessage('EVENT_ID'),
		Loc::getMessage('EVENT_TITLE'),
	)
);

?>

<form name="find_form" method="get" action="<?= $APPLICATION->GetCurPage() ?>">
	<? $formfilter->Begin() ?>
	<tr>
		<td><b><?= Loc::getMessage('EVENT_ID') ?>:</b></td>
		<td>
			<input type="text" size="25" name="find_id" value="<?= htmlspecialchars($find_id) ?>" />
		</td>
	</tr>
	<tr>
		<td><?= Loc::getMessage('EVENT_TITLE') ?>:</td>
		<td>
			<input type="text" size="25" name="find_title" value="<?= htmlspecialchars($find_title) ?>" />
		</td>
	</tr>
    
	<? $formfilter->Buttons(array("table_id" => $table, "url" => $APPLICATION->GetCurPage(), "form" => "find_form")) ?>
	<? $formfilter->End() ?>
</form>

<? $ladmin->DisplayList(); ?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>