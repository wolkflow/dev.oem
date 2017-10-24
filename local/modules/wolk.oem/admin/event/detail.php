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

// Стенды.
$stands = $event->getStands();

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

$stat['ORDERS']   = (int) $result->selectedRowsCount();
$stat['USERS']    = array();
$stat['PRICES']   = array('PRICE' => array(), 'SURCHARGE' => array());
$stat['STANDS']   = array();
$stat['PRODUCTS'] = array();

foreach ($stands as $stand) {
	$stat['STANDS'][$stand->getID()] = array(
		'TITLE'    => $stand->getTitle(),
		'PRICES'   => array(),
		'ORDERS'   => array(),
		'QUANTITY' => 0,
	);
}

foreach ($products as $product) {
	$stat['PRODUCTS'][$product->getID()] = array(
		'TITLE'    => $product->getTitle(),
		'PRICES'   => array(),
		'ORDERS'   => array(),
		'QUANTITY' => 0,
	);
}


// Проход по всем заказам и получение информации о продажах.
while ($item = $result->fetch()) {
	$order = new Wolk\OEM\Order($item['ID']);
	
	// Покупатели.
	$stat['USERS'][$order->getUserID()] = $order->getUserID();
	
	// Заказанные товары.
	$baskets = $order->getBaskets();
	
	foreach ($baskets as $basket) {
		
		if ($basket['PRICE'] == 0) {
			continue;
		}
		
		
		// Стенды или продукция.
		if ($basket['PROPS']['STAND']['VALUE'] == 'Y') {
			$key = 'STANDS';
		} else {
			$key = 'PRODUCTS';
		}
		
		
		// Количество заказов.
		$stat[$key][$basket['PRODUCT_ID']]['ORDERS'][$order->getID()] = $order->getID();
		
		// Общее количество.
		$stat[$key][$basket['PRODUCT_ID']]['QUANTITY'] += $basket['QUANTITY'];
		
		foreach ($currencies as $currency) {
			$code = $currency['CURRENCY'];
			
			$stat[$key][$basket['PRODUCT_ID']]['PRICES'][$code] += (float) CCurrencyRates::ConvertCurrency(
				$basket['PRICE'], 
				$basket['CURRENCY'],
				$code
			);
		}
	}
	
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
						<?= Loc::getMessage('EVENT') ?> 
						<b><?= $event->getTitle() ?></b>
					</h2>
					<a class="glyphicon glyphicon-edit"  target="_blank" href="/bitrix/admin/iblock_element_edit.php?type=events&IBLOCK_ID=<?= EVENTS_IBLOCK_ID ?>&ID=<?= $event->getID() ?>"></a>
					<a class="glyphicon glyphicon-share" target="_blank" href="/events/<?= $event->getCode() ?>/"></a>
				</div>
				<div class="panel-body">
					<h4><?= Loc::getMessage('STAT_EVENT') ?></h4>
					<table class="table table-bordered table-condensed  stat-table">
						<tbody>
							<tr>
								<td><?= Loc::getMessage('TOTAL_ORDERS') ?></td>
								<td><?= $stat['ORDERS'] ?></td>
							</tr>
							<tr>
								<td><?= Loc::getMessage('TOTAL_USERS') ?></td>
								<td><?= count($stat['USERS']) ?></td>
							</tr>
							<tr>
								<td><?= Loc::getMessage('TOTAL_SALE_SUM') ?></td>
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
					
					<h4>Статистика по стендам</h4>
					<table class="table table-bordered table-condensed stat-table">
						<thead>
							<tr>
								<th>Название</th>
								<th>Общая стоимость</th>
								<th>Количество заказов с позицией</th>
								<th>Общее количество (м<sup>2</sup>)</th>
							</tr>
						</thead>
						<tbody>
							<? foreach ($stands as $stand) { ?>
								<tr>
									<td>
										<a href="/bitrix/admin/iblock_element_edit.php?type=events&IBLOCK_ID=<?= STANDS_IBLOCK_ID ?>&&ID=<?= $stand->getID() ?>"><?= $stand->getName() ?></a>
										<br/>
										<?= $stand->getTitle() ?>
									</td>
									<td>
										<? foreach ($currencies as $currency) { ?>
											<? $code = $currency['CURRENCY'] ?>
											<li>
												<?= CurrencyFormat($stat['STANDS'][$stand->getID()]['PRICES'][$code], $code) ?>
											</li>
										<? } ?>
									</td>
									<td>
										<? if (!empty($stat['STANDS'][$stand->getID()]['ORDERS'])) { ?>
											<?	// Список заказов. 
												$content = [];
												foreach ($stat['STANDS'][$stand->getID()]['ORDERS'] as $oid) {
													$content []= "<a href='/bitrix/admin/wolk_oem_order_edit.php?ID=" . $oid . "' target='_blank'>" . $oid . "</a>";
												}
												$content = implode(', ', $content);
											?>										
											<a href="javascript:void(0)" class="js-popover" data-html="true" data-container="body" data-toggle="popover" data-placement="right" data-title="Номера заказов" data-content="<?= $content ?>">
												<?= count(array_unique($stat['STANDS'][$stand->getID()]['ORDERS'])) ?>
											</a>
										<? } else { ?>
											&mdash;
										<? } ?>
									</td>
									<td>
										<?= $stat['STANDS'][$stand->getID()]['QUANTITY'] ?>
									</td>
								</tr>
							<? } ?>
						</tbody>
					</table>
					
					<hr/>
					
					<h4>Статистика по позициям</h4>
					<table class="table table-bordered table-condensed stat-table">
						<thead>
							<tr>
								<th>Название</th>
								<th>Общая стоимость</th>
								<th>Количество заказов с позицией</th>
								<th>
									Общее количество 
									<span class="glyphicon glyphicon-info-sign js-tooltip" data-toggle="tooltip" data-placement="top" title="Единица измерения зависит от позиции."></span>
								</th>
							</tr>
						</thead>
						<tbody>
							<? foreach ($products as $product) { ?>
								<tr>
									<td>
										<?= $product->getTitle() ?>
									</td>
									<td>
										<? foreach ($currencies as $currency) { ?>
											<? $code = $currency['CURRENCY'] ?>
											<li>
												<?= CurrencyFormat($stat['PRODUCTS'][$product->getID()]['PRICES'][$code], $code) ?>
											</li>
										<? } ?>
									</td>
									<td>
										<? if (!empty($stat['PRODUCTS'][$product->getID()]['ORDERS'])) { ?>
											<?	// Список заказов. 
												$content = [];
												foreach ($stat['PRODUCTS'][$product->getID()]['ORDERS'] as $oid) {
													$content []= "<a href='/bitrix/admin/wolk_oem_order_edit.php?ID=" . $oid . "' target='_blank'>" . $oid . "</a>";
												}
												$content = implode(', ', $content);
											?>										
											<a href="javascript:void(0)" class="js-popover" data-html="true" data-container="body" data-toggle="popover" data-placement="right" data-title="Номера заказов" data-content="<?= $content ?>">
												<?= count(array_unique($stat['PRODUCTS'][$product->getID()]['ORDERS'])) ?>
											</a>
										<? } else { ?>
											&mdash;
										<? } ?>
									</td>
									<td>
										<?= $stat['PRODUCTS'][$product->getID()]['QUANTITY'] ?>
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
        // Bootstrap.
        $('html').addClass('wolk_admin_pages_no_conflict');
		
		// Popover.
		$('.js-popover').popover();
		
		// Tooltip.
		$('.js-tooltip').tooltip();
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
	
	.stat-table {
		border: 2px #dddddd solid;;
	}
	
	.stat-table tbody tr td {
		padding-left: 10px;
		vertical-align: middle;
	}
	
	.stat-table tbody tr td:first-child {
		width: 35%;
	}
</style>


<? require ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>

