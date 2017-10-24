<?php

use Bitrix\Sale\Helpers\Admin\OrderEdit;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Helpers\Admin\Blocks;
use Bitrix\Highloadblock\HighloadBlockTable;

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

// подключим языковой файл.
IncludeModuleLangFile(__FILE__);

// Подключение модулей.
Bitrix\Main\Loader::includeModule('iblock');
Bitrix\Main\Loader::includeModule('sale');
Bitrix\Main\Loader::includeModule('wolk.oem');

// Уровни доступа.
$permission = $APPLICATION->GetGroupRight('wolk.oem');

if ($permission == 'D') {
	$APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));
}
$groups = $USER->GetUserGroupArray();

$ismanager = false;
if (in_array(GROUP_MANAGERS_ID, $groups)) {
	$ismanager = true;
}

// ID мероприятия.
$eid = (int) $_REQUEST['ID'];

$event = null;
if (!empty($eid)) {
    $event = new Wolk\OEM\Event($eid);
}

if (empty($event)) {
	LocalRedirect('/bitrix/admin/wolk_oem_event_list.php');
}

// Валюты.
$result = CCurrency::GetList(($b="NAME"), ($o="ASC"), LANGUAGE_ID);
$currencies = array();
while($currency = $result->fetch()) {
	$currencies[$currency['CURRENCY']] = $currency;
}

// Продукция.
$products = $event->getProducts();


// Статистика.
$stat = array();


$result = CSaleOrder::getList(
	array(),
	array('PROPERTY_VAL_BY_CODE_EVENT_ID' => $event->getID()),
	false,
	false,
	array()
);

$stat['ORDERS'] = (int) $result->selectedRowsCount();
$stat['USERS']  = array();
$stat['PRICES'] = array('PRICE' => array(), 'SURCHARGE' => array());

while ($item = $result->fetch()) {
	$order = new Wolk\OEM\Order($item['ID']);
	
	$stat['USERS'][$order->getUserID()] = $order->getUserID();
	
	foreach ($currencies as $currency) {
		$code = $currency['CURRENCY'];
		
		$stat['PRICES']['PRICE'][$code] += (float) CCurrencyRates::ConvertCurrency(
			$order->getPrice(), 
			$order->getCurrency(),
			$code
		);
		
		$stat['PRICES']['VAT'][$code] += (float) CCurrencyRates::ConvertCurrency(
			$order->getTAX(), 
			$order->getCurrency(),
			$code
		);
		
		$stat['PRICES']['SURCHARGE'][$code] += (float) CCurrencyRates::ConvertCurrency(
			$order->getSurcharge(), 
			$order->getCurrency(),
			$code
		);
	}
}

$stat['USERS'] = array_unique($stat['USERS']);


// Подключение модуля.
Bitrix\Main\Loader::includeModule('wolk.oem');


$APPLICATION->SetAdditionalCSS('/assets/bootstrap/css/bootstrap.min.css');

Bitrix\Main\Page\Asset::getInstance()->addJs('https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js');
Bitrix\Main\Page\Asset::getInstance()->addJs('/assets/bootstrap/js/bootstrap.min.js');

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

?>

<? if (!empty($errors)) { ?>
	<div id="js-error-message-id" class="alert alert-danger" role="alert">
		<?= implode('<br/>', $errors) ?>
	</div>
<? } ?>

<div class="container-fluid" id="js-order-form-od">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading event-heading">
                    <h2>
						Выставка 
						<b><?= $event->getTitle() ?></b>
					</h2>
					<a class="glyphicon glyphicon-edit"  target="_blank" href="/bitrix/admin/iblock_element_edit.php?type=events&IBLOCK_ID=<?= EVENTS_IBLOCK_ID ?>&ID=<?= $event->getID() ?>"></a>
					<a class="glyphicon glyphicon-share" target="_blank" href="/events/<?= $event->getCode() ?>/"></a>
				</div>
				<div class="panel-body">
					<h4>Статистика по выставке</h4>
					<table class="table table-bordered table-condensed">
						<tbody>
							<tr>
								<td width="30%">Всего заказов</td>
								<td><?= $stat['ORDERS'] ?></td>
							</tr>
							<tr>
								<td width="30%">Всего пользователей</td>
								<td><?= count($stat['USERS']) ?></td>
							</tr>
							<tr>
								<td width="30%">Общая сумма продаж</td>
								<td>
									<ul>
										<? foreach ($currencies as $currency) { ?>
											<? $code = $currency['CURRENCY'] ?>
											<li>
												<?= CurrencyFormat($stat['PRICES']['PRICE'][$code], $code) ?>
											</li>
										<? } ?>
									</ul>
								</td>
							</tr>
							<tr>
								<td width="30%">Общая сумма НДС</td>
								<td>
									<ul>
										<? foreach ($currencies as $currency) { ?>
											<? $code = $currency['CURRENCY'] ?>
											<li>
												<?= CurrencyFormat($stat['PRICES']['VAT'][$code], $code) ?>
											</li>
										<? } ?>
									</ul>
								</td>
							</tr>
							<tr>
								<td width="30%">Общая сумма наценок</td>
								<td>
									<ul>
										<? foreach ($currencies as $currency) { ?>
											<? $code = $currency['CURRENCY'] ?>
											<li>
												<?= CurrencyFormat($stat['PRICES']['SURCHARGE'][$code], $code) ?>
											</li>
										<? } ?>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
					
					<hr/>
					
					<h4>Статистика по позициям</h4>
					<table class="table table-bordered table-condensed">
						<tbody>
							<? foreach ($products as $product) { ?>
								<tr>
									<td>
										<?= $product->getTitle() ?>
									</td>
									<td>
										-
									</td>
								</tr>
							<? } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
				
				
<script>
    var invoices = <?= json_encode($invoices) ?>;
    
    $(document).ready(function() {
        // bootstrap
        $('html').addClass('wolk_admin_pages_no_conflict');
	});
</script>

<style>
	#js-form-user-select-id {
        cursor: pointer;
    }

    .wolk_oem_list *,
    .wolk_oem_mainadmin *,
    .wolk_admin_pages_no_conflict *{
        -webkit-box-sizing: initial !important;
        -moz-box-sizing: initial !important;
        box-sizing: initial !important;
    }
    .wolk_oem_list .adm-workarea .adm-filter-box-sizing .adm-select,
    .wolk_oem_mainadmin .adm-workarea .adm-filter-box-sizing .adm-select,
    .wolk_admin_pages_no_conflict .adm-workarea .adm-filter-box-sizing .adm-select,
    .wolk_oem_list .adm-workarea input[type="submit"], .wolk_oem_list .adm-workarea input[type="button"], .wolk_oem_list .adm-workarea input[type="reset"],
    .wolk_oem_mainadmin .adm-workarea input[type="submit"], .wolk_oem_mainadmin .adm-workarea input[type="button"], .wolk_oem_mainadmin .adm-workarea input[type="reset"],
    .wolk_admin_pages_no_conflict .adm-workarea input[type="submit"], .wolk_admin_pages_no_conflict .adm-workarea input[type="button"], .wolk_admin_pages_no_conflict .adm-workarea input[type="reset"],
    .wolk_admin_pages_no_conflict input, .wolk_admin_pages_no_conflict button, .wolk_admin_pages_no_conflict select, .wolk_admin_pages_no_conflict textarea {
        box-sizing: border-box!important;
    }


    .wolk_oem_mainadmin .adm-workarea { background: #f2f5f7; padding-bottom: 30px}

    .wolk_admin_pages_no_conflict .adm-workarea input.form-control,
    .wolk_admin_pages_no_conflict .adm-workarea input[type="text"].form-control,
    .wolk_admin_pages_no_conflict .adm-workarea input[type="password"].form-control,
    .wolk_admin_pages_no_conflict .adm-workarea input[type="email"].form-control,
    .wolk_admin_pages_no_conflict .adm-workarea select,
    .wolk_admin_pages_no_conflict .adm-workarea textarea{
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    }
    .wolk_admin_pages_no_conflict .adm-workarea textarea{
        height: auto;
    }
    
    .event-heading h2 {
		display: inline-block;
		margin-right: 10px;
	}
	.event-heading .glyphicon {
		margin-right: 5px;
		text-decoration: none;
	}
</style>


<? require ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>

