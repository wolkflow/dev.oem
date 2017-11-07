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
$result = CCurrency::GetList(($b="SORT"), ($o="ASC"), LANGUAGE_ID);
$currencies = array();
while ($currency = $result->fetch()) {
	$currency['CODE'] = strtolower($currency['CURRENCY']);
	
	$currencies[$currency['CURRENCY']] = $currency;
}

// Стенды.
$stands = $event->getStands();

// Цены на стенды.
/*
$result = StandPrices::getList(
    array(
        'filter' => [StandPrices::FIELD_EVENT => $event->getID(), StandPrices::FIELD_STAND => $selected_stands]
    ),
    false
);

$prices_stands = array();
while ($item = $result->fetch()) {
    $prices_stands
		[$item[StandPrices::FIELD_STAND]] 
        [$item[StandPrices::FIELD_TYPE]]
        [$item[StandPrices::FIELD_LANG]]
    = $item;
}
*/


// Продукция.
$products = $event->getProducts();

/*
// Цены на продукцию (стандартная застройка).
$result = ProductPrices::getList(['filter' => [ProductPrices::FIELD_EVENT => $event->getID()]], false);

$prices_products = array();
while ($item = $result->fetch()) {
    $prices_products
		[$item[ProductPrices::FIELD_PRODUCT]]
        [$item[ProductPrices::FIELD_TYPE]]
        [$item[ProductPrices::FIELD_LANG]]
    = $item;
}
*/


// Статистика.
$stat = array();


$result = CSaleOrder::getList(
	array(),
	array('PROPERTY_VAL_BY_CODE_EVENT_ID' => $event->getID()),
	false,
	false,
	array('ID')
);

$stat['ORDERS']   = (int) $result->SelectedRowsCount();
$stat['USERS']    = array();
$stat['PRICES']   = array('PRICE' => array(), 'SURCHARGE' => array());
$stat['STANDS']   = array();
$stat['PRODUCTS'] = array();

/*
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
*/


// Проход по всем заказам и получение информации о продажах.
while ($item = $result->fetch()) {
	
	// Заказ.
	$order = new Wolk\OEM\Order($item['ID']);
	
	// Покупатели.
	$stat['USERS'][$order->getUserID()] = $order->getUserID();
	
	// Заказанные товары.
	$baskets = $order->getBaskets();
	
	foreach ($baskets as $basket) {
		
		if ($basket['PRICE'] <= 0) {
			continue;
		}
		
		// Стенды или продукция.
		if ($basket['PROPS']['STAND']['VALUE'] == 'Y') {
			$key = 'STANDS';
		} else {
			$key = 'PRODUCTS';
		}
		
		//if ($key == 'PRODUCTS' && !array_key_exists($basket['PRODUCT_ID'], $products)) {
		//	continue;
		//}
		
		// Процент наценки.
		$surcharge = (float) $order->getSurchargePercent();
		$surfactor = (1 + $surcharge / 100);
		
		// Стоимость.
		$cost = $basket['PRICE'] * $basket['QUANTITY'];
		
		// Элемент.
		$bitem = &$stat[$key]['CURRENCIES'][$basket['CURRENCY']]['ITEMS'][$basket['PRODUCT_ID']][$surcharge];
		
		
		// Количество заказов.
		$bitem['ORDERS'][$order->getID()] = $order->getID();
		
		// Общее количество.
		$bitem['QUANTITY'] += $basket['QUANTITY'];
		
		// Цена товара.
		$bitem['PRICE_ORIGINAL'] = (float) $basket['PRICE'];
		
		// Цена товара с наценкой.
		$bitem['PRICE_SURCHARGE'] = (float) $basket['PRICE'] * $surfactor;
		
		// Стоимость товара.
		$bitem['TOTAL'] += (float) $cost * $surfactor;
		
		
		
		// Общая сумма.
		$stat[$key]['CURRENCIES'][$basket['CURRENCY']]['TOTAL'] += (float) $cost * $surfactor;
		
		
		// Общее количество товаров.
		$stat[$key]['STATS'][$basket['PRODUCT_ID']]['QUANTITY'] += $basket['QUANTITY'];
		
		// Общее количество заказов.
		$stat[$key]['STATS'][$basket['PRODUCT_ID']]['ORDERS'][$order->getID()] = $order->getID();
	}
	
	// Цены.
	$stat['PRICES']['PRICE'][$order->getCurrency()] += (float) $order->getPrice();
	
	// НДС.
	$stat['PRICES']['VAT'][$order->getCurrency()] += (float) $order->getTAX();
	
	// Наценки.
	$stat['PRICES']['SURCHARGE'][$order->getCurrency()] += (float) $order->getSurcharge();
}

$stat['USERS'] = array_unique($stat['USERS']);

/*  
echo '<pre>';
print_r($stat['PRODUCTS']);
echo '</pre>';
die();

   */

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
					
					<div class="common-stat">
						<div class="common-stat-line">
							<?= Loc::getMessage('TOTAL_ORDERS') ?>: <b><?= $stat['ORDERS'] ?></b>
						</div>
						<div class="common-stat-line">
							<?= Loc::getMessage('TOTAL_USERS') ?>: <b><?= count($stat['USERS']) ?></b>
						</div>
						<? if (is_object($event->getLocation())) { ?>
							<div class="common-stat-line">
								Место проведения: <b><?= $event->getLocation()->getName() ?></b>
							</div>
						<? } ?>
					</div>
					
					<table class="table table-bordered table-condensed  stat-table">
						<thead>
							<th>Название</th>
							<? foreach ($currencies as $currency) { ?>
								<th class="currency-column">
									Сумма по <?= $currency['CURRENCY'] ?>
								</th>
							<? } ?>
						</thead>
						<tbody>
							<tr>
								<td><?= Loc::getMessage('TOTAL_SALE_SUM') ?></td>
								<? foreach ($currencies as $currency) { ?>
									<? $code = $currency['CURRENCY'] ?>
									<td>
										<? if (!empty($stat['PRICES']['PRICES'][$code])) { ?>
											<?= CurrencyFormat($stat['PRICES']['PRICES'][$code], $code) ?>
										<? } else { ?>
											<span class="none">&mdash;</span>
										<? } ?>
									</td>
								<? } ?>
							</tr>
							<tr>
								<td width="30%"><?= Loc::getMessage('TOTAL_VAT_SUM') ?></td>
								<? foreach ($currencies as $currency) { ?>
									<? $code = $currency['CURRENCY'] ?>
									<td>
										<? if (!empty($stat['PRICES']['VAT'][$code])) { ?>
											<?= CurrencyFormat($stat['PRICES']['VAT'][$code], $code) ?>
										<? } else { ?>
											<span class="none">&mdash;</span>
										<? } ?>
									</td>
								<? } ?>
							</tr>
							<tr>
								<td width="30%"><?= Loc::getMessage('TOTAL_SURCHARGE_SUM') ?></td>
								<? foreach ($currencies as $currency) { ?>
									<? $code = $currency['CURRENCY'] ?>
									<td>
										<? if (!empty($stat['PRICES']['SURCHARGE'][$code])) { ?>
											<?= CurrencyFormat($stat['PRICES']['SURCHARGE'][$code], $code) ?>
										<? } else { ?>
											<span class="none">&mdash;</span>
										<? } ?>
									</td>
								<? } ?>
							</tr>
						</tbody>
					</table>
					
					<hr/>
					
					<? // Статистика по стендам. // ?>
					<h4><?= Loc::getMessage('STAT_STANDS') ?></h4>
					<nav class="js-wrapper-stats navbar navbar-default" role="navigation">
						<div class="container-fluid no-padding">
							<ul class="nav navbar-nav">
								<? $first = true; ?>
								<? foreach ($currencies as $currency) { ?>
									<? $disabled = (empty($stat['STANDS']['CURRENCIES'][$currency['CURRENCY']])) ?>
									<li class="<?= ($first) ? ('active') : ('') ?> <?= ($disabled) ? ('disabled') : ('') ?>">
										<a href="javascript:void(0)" class="js-currency-switch" data-currency="<?= $currency['CODE'] ?>">
											<?= $currency['CURRENCY'] ?>
										</a>
									</li>
									<? $first = false; ?>
								<? } ?>
							</ul>
							<table class="table table-bordered table-condensed stat-table">
								<thead>
									<tr>
										<th>
											<?= Loc::getMessage('STAT_HEADER_TITLE') ?>
										</th>
										<th>
											<?= Loc::getMessage('STAT_HEADER_SURCHARGE') ?>
										</th>
										<th class="currency-column">
											<?= Loc::getMessage('STAT_HEADER_QUANTITY') ?>
										</th>
										<th class="currency-column">
											<?= Loc::getMessage('STAT_HEADER_PRICE') ?>
										</th>
										<th class="currency-column">
											<?= Loc::getMessage('STAT_HEADER_COST') ?>
										</th>
										<th>
											<?= Loc::getMessage('STAT_HEADER_COUNT_ORDERS_STANDS') ?>
										</th>
									</tr>
								</thead>
								<? $first = true; ?>
								<? foreach ($stat['STANDS']['CURRENCIES'] as $code => $curdata) { ?>
									<tbody class="js-currency-tab js-currency-tab-<?= strtolower($code) ?>" <?= (!$first) ? ('style="display: none;"') : ('') ?>>
										<? foreach ($curdata['ITEMS'] as $pid => $item) { ?>
											<?	// Продукт не из доступных в выставке. 
												if (!is_object($stands[$pid])) {
													// continue;
												}
											?>
											<? foreach ($item as $surcharge => $subitem) { ?>
												<tr>
													<td>
														<? if (is_object($stands[$pid])) { ?>
															<?= $stands[$pid]->getTitle() ?>
														<? } else { ?>
															<?= $pid ?>
														<? } ?>
													</td>
													<td>
														<?= floatval($surcharge) ?>%
													</td>
													<td>
														<?= $subitem['QUANTITY'] ?>
													</td>
													<td>
														<?= CurrencyFormat($subitem['PRICE_SURCHARGE'], $code) ?>
													</td>
													<td>
														<?= CurrencyFormat($subitem['TOTAL'], $code) ?>
													</td>
													<td>
														<? if (!empty($subitem['ORDERS'])) { ?>
															<?	// Список заказов. 
																$content = [];
																foreach ($subitem['ORDERS'] as $oid) {
																	$content []= "<a href='/bitrix/admin/wolk_oem_order_edit.php?ID=" . $oid . "' target='_blank'>" . $oid . "</a>";
																}
																$content = implode(', ', $content);
															?>									
															<a href="javascript:void(0)" class="js-popover" data-html="true" data-container="body" data-toggle="popover" data-placement="right" data-title="Номера заказов" data-content="<?= $content ?>">
																<?= count(array_unique($subitem['ORDERS'])) ?>
															</a>
														<? } else { ?>
															<span class="none">&mdash;</span>
														<? } ?>
													</td>
												</tr>
											<? } ?>
										<? } ?>
										<tr>
											<td colspan="4" align="right">
												<b>Сумма</b>
											</td>
											<td colspan="2" align="left">
												<b><?= CurrencyFormat($curdata['TOTAL'], $code) ?></b>
											</td>
										</tr>
									</tbody>
									<? $first = false; ?>
								<? } ?>
							</table>
						</div>
					</nav>
					
					<hr/>
					
					<? // Статистика по позициям. // ?>
					<h4><?= Loc::getMessage('STAT_PRODUCTS') ?></h4>
					<nav class="js-wrapper-stats navbar navbar-default" role="navigation">
						<div class="container-fluid no-padding">
							<ul class="nav navbar-nav">
								<? $first = true; ?>
								<? foreach ($currencies as $currency) { ?>
									<? $disabled = (empty($stat['PRODUCTS']['CURRENCIES'][$currency['CURRENCY']])) ?>
									<li class="<?= ($first) ? ('active') : ('') ?> <?= ($disabled) ? ('disabled') : ('') ?>">
										<a href="javascript:void(0)" class="js-currency-switch" data-currency="<?= $currency['CODE'] ?>">
											<?= $currency['CURRENCY'] ?>
										</a>
									</li>
									<? $first = false; ?>
								<? } ?>
							</ul>
							<table class="table table-bordered table-condensed stat-table">
								<thead>
									<tr>
										<th>
											<?= Loc::getMessage('STAT_HEADER_TITLE') ?>
										</th>
										<th>
											<?= Loc::getMessage('STAT_HEADER_SURCHARGE') ?>
										</th>
										<th class="currency-column">
											<?= Loc::getMessage('STAT_HEADER_QUANTITY') ?>
										</th>
										<th class="currency-column">
											<?= Loc::getMessage('STAT_HEADER_PRICE') ?>
										</th>
										<th class="currency-column">
											<?= Loc::getMessage('STAT_HEADER_COST') ?>
										</th>
										<th>
											<?= Loc::getMessage('STAT_HEADER_COUNT_ORDERS_PRODUCTS') ?>
										</th>
									</tr>
								</thead>
								<? $first = true; ?>
								<? foreach ($stat['PRODUCTS']['CURRENCIES'] as $code => $curdata) { ?>
									<tbody class="js-currency-tab js-currency-tab-<?= strtolower($code) ?>" <?= (!$first) ? ('style="display: none;"') : ('') ?>>
										<? foreach ($curdata['ITEMS'] as $pid => $item) { ?>
											<?	// Продукт не из доступных в выставке. 
												if (!is_object($products[$pid])) {
													// continue;
												}
											?>
											<? foreach ($item as $surcharge => $subitem) { ?>
												<tr>
													<td>
														<? if (is_object($products[$pid])) { ?>
															<?= $products[$pid]->getTitle() ?>
														<? } else { ?>
															<?= $pid ?>
														<? } ?>
													</td>
													<td>
														<?= floatval($surcharge) ?>%
													</td>
													<td>
														<?= $subitem['QUANTITY'] ?>
													</td>
													<td>
														<?= CurrencyFormat($subitem['PRICE_SURCHARGE'], $code) ?>
													</td>
													<td>
														<?= CurrencyFormat($subitem['TOTAL'], $code) ?>
													</td>
													<td>
														<? if (!empty($subitem['ORDERS'])) { ?>
															<?	// Список заказов. 
																$content = [];
																foreach ($subitem['ORDERS'] as $oid) {
																	$content []= "<a href='/bitrix/admin/wolk_oem_order_edit.php?ID=" . $oid . "' target='_blank'>" . $oid . "</a>";
																}
																$content = implode(', ', $content);
															?>									
															<a href="javascript:void(0)" class="js-popover" data-html="true" data-container="body" data-toggle="popover" data-placement="right" data-title="Номера заказов" data-content="<?= $content ?>">
																<?= count(array_unique($subitem['ORDERS'])) ?>
															</a>
														<? } else { ?>
															<span class="none">&mdash;</span>
														<? } ?>
													</td>
												</tr>
											<? } ?>
										<? } ?>
										<tr>
											<td colspan="4" align="right">
												<b>Сумма</b>
											</td>
											<td colspan="2" align="left">
												<b><?= CurrencyFormat($curdata['TOTAL'], $code) ?></b>
											</td>
										</tr>
									</tbody>
									<? $first = false; ?>
								<? } ?>
							</table>
						</div>
					</nav>
					
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
		
		
		// Переключение валюты.
		$('.js-wrapper-stats .js-currency-switch').on('click', function() {
			var $that = $(this);
			var $wrap = $that.closest('.js-wrapper-stats');
			var $tabs = $wrap.find('.js-currency-tab-' + $that.data('currency'));
			
			$wrap.find('.js-currency-switch').closest('li').removeClass('active');
			$that.closest('li').addClass('active');
			
			$wrap.find('.js-currency-tab').hide();
			$tabs.show();
		});
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
		background: #fcfcfc;
		border-top: 2px #dddddd solid;
		margin-bottom: 0px;
	}
	
	.stat-table tbody tr td {
		padding-left: 10px;
		vertical-align: middle;
	}
	
	.stat-table tbody tr td:first-child {
		width: 35%;
	}
	
	.currency-column {
		width: 100px;
	}
	
	.none {
		color: #cccccc;
	}
	
	.common-stat {
		margin: 0 0 20px 0;
	}
	
	.no-padding {
		padding: 0px;
	}
</style>

<? require ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>
