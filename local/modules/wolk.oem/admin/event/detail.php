<?php

use Bitrix\Sale\Helpers\Admin\OrderEdit;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Helpers\Admin\Blocks;
use Bitrix\Highloadblock\HighloadBlockTable;


function jsonresponse($status, $message = '', $data = null, $console = '', $type = 'json')
{
	$result = array(
		'status'  => (bool)   $status,
		'message' => (string) $message,
		'data' 	  => (array)  $data,
		'console' => (string) $console,
	);
	
	header('Content-Type: application/json');
	echo json_encode($result);
	exit();
}


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


$cache = new CPHPCache();
if ($cache->initCache(864000, 'event-detail-stats-entities', '/events/')) {
	$vars = $cache->getVars();
	
	$stands   = $vars['stands'];
	$products = $vars['products'];
	$sections = $vars['sections'];
} else {
	// Стенды.
	$stands = Wolk\OEM\Stand::getList([]); //$event->getStands();

	// Продукция.
	$products = Wolk\OEM\Products\Base::getList([]); //$event->getProducts();

	// Разделы.
	$sections = Wolk\OEM\Products\Section::getList(['filter' => ['DEPTH_LEVEL' => 1]]);
	
	
	$cache->EndDataCache([
		'stands'   => $stands,
		'products' => $products,
		'sections' => $sections,
	]);
}





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
$stat['PRICES']   = array('PRICE' => array(), 'VAT' => array(), 'SURCHARGE' => array());
$stat['STANDS']   = array();
$stat['PRODUCTS'] = array();



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
		
		// Курс и валютя для статистики.
		$convrate = $order->getRate();
		$currency = $order->getRateCurrency();
		
		// Процент наценки.
		$surcharge = (float) $order->getSurchargePercent();
		$surfactor = (1 + $surcharge / 100);
		
		// Стоимость.
		$cost = $basket['PRICE'] * $basket['QUANTITY'] * $convrate;
		
		// Элемент.
		$bitem = &$stat[$key]['CURRENCIES'][$currency]['ITEMS'][$basket['PRODUCT_ID']][$surcharge];
		
		
		// Количество заказов.
		$bitem['ORDERS'][$order->getID()] = $order->getID();
		
		// Общее количество.
		$bitem['QUANTITY'] += $basket['QUANTITY'];
		
		// Цена товара.
		$bitem['PRICE_ORIGINAL'] = (float) $basket['PRICE'] * $convrate;
		
		// Цена товара с наценкой.
		$bitem['PRICE_SURCHARGE'] = (float) $basket['PRICE'] * $surfactor * $convrate;
		
		// Стоимость товара.
		$bitem['TOTAL'] += (float) $cost * $surfactor * $convrate;
		
		
		
		// Общая сумма.
		$stat[$key]['CURRENCIES'][$currency]['TOTAL'] += (float) $cost * $surfactor * $convrate;
		
		
		// Общее количество товаров.
		$stat[$key]['STATS'][$basket['PRODUCT_ID']]['QUANTITY'] += $basket['QUANTITY'];
		
		// Общее количество заказов.
		$stat[$key]['STATS'][$basket['PRODUCT_ID']]['ORDERS'][$order->getID()] = $order->getID();
	}
	
	// Цена.
	$stat['PRICES']['PRICE'][$currency] += (float) $order->getPrice() * $convrate;
	
	// НДС.
	$stat['PRICES']['VAT'][$currency] += (float) $order->getTAX() * $convrate;
	
	// Цена без НДС.
	$stat['PRICES']['NOVAT'][$currency] = $stat['PRICES']['PRICE'][$currency] - $stat['PRICES']['VAT'][$currency];
	
	// Наценки.
	$stat['PRICES']['SURCHARGE'][$currency] += (float) $order->getSurcharge() * $convrate;
}

$stat['USERS'] = array_unique($stat['USERS']);



// Запрос.
$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

/*
 * Экспортирование данных.
 */
if ($request->isAjaxRequest() && !empty($request->get('export'))) {
	
	while (ob_get_level()) {
		ob_end_clean();
	}
	
	$target   = strtolower((string) $request->get('target'));
	$filters  = array_filter((array) $request->get('filters'));
	$currency = strtoupper((string) $request->get('currency'));
	
	switch ($target) {
		
		case ('stands'):
			$objects = $stands;
			$items   = $stat['STANDS']['CURRENCIES'][$currency]['ITEMS'];
			break;
			
		case ('products'):
			$objects = $products;
			$items   = $stat['PRODUCTS']['CURRENCIES'][$currency]['ITEMS'];
			if (!empty($filters)) { 
				foreach ($items as $id => $subitems) {
					foreach ($subitems as $item) {
						if (is_object($objects[$id])) {
							$object = $objects[$id];
							if (!in_array($object->getSection()->getMainSection()->getID(), $filters)) {
								unset($items[$id]);
							}
						}
					}
				}
			}
			break;
	}
	
	// Путь к файлу выгрузки.
	$link = $request->get('link');
	if (empty($link)) {
		$link = '/upload/stats/' . $target . '-' . date('Y-m-d-H-i-s') . '.csv';
	}
	$path = $_SERVER['DOCUMENT_ROOT'] . $link;
	
	$fp = fopen($path, 'a');
	
	$data = [
		iconv('UTF-8', 'cp1251', Loc::getMessage('STAT_HEADER_TITLE')),
		iconv('UTF-8', 'cp1251', Loc::getMessage('STAT_HEADER_SURCHARGE')),
		iconv('UTF-8', 'cp1251', Loc::getMessage('STAT_HEADER_QUANTITY')),
		iconv('UTF-8', 'cp1251', Loc::getMessage('STAT_HEADER_PRICE')),
		iconv('UTF-8', 'cp1251', Loc::getMessage('STAT_HEADER_COST')),
	];
	fputcsv($fp, $data, ';');
	
	foreach ($items as $id => $subitems) {
		foreach ($subitems as $surcharge => $item) {
			$object = $objects[$id];
			$title  = '—';
			if (!empty($object)) {
				$title = $object->getTitle();
			}
			$data = [
				iconv('UTF-8', 'cp1251', $title),
				floatval($surcharge) . '%',
				$item['QUANTITY'],
				number_format($item['PRICE_SURCHARGE'], 2),
				number_format($item['TOTAL'], 2),
			];
			fputcsv($fp, $data, ';');
		}
	}
	
	fclose($fp);
	
	// Ответ.
	jsonresponse(true, '', array('status' => true, 'link' => $link));
}




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
								<?= Loc::getMessage('PLACE') ?>:
								<b><?= $event->getLocation()->getName() ?></b>
							</div>
						<? } ?>
					</div>
					
					<table class="table table-bordered table-condensed  stat-table">
						<thead>
							<th><?= Loc::getMessage('STAT_HEADER_TITLE') ?></th>
							<? foreach ($currencies as $currency) { ?>
								<th class="currency-column">
									<?= Loc::getMessage('STAT_HEADER_SUMMBY') ?> <?= $currency['CURRENCY'] ?>
								</th>
							<? } ?>
						</thead>
						<tbody>
							<tr>
								<td><?= Loc::getMessage('TOTAL_SALE_SUM_NOVAT') ?></td>
								<? foreach ($currencies as $currency) { ?>
									<? $code = $currency['CURRENCY'] ?>
									<td>
										<? if (!empty($stat['PRICES']['NOVAT'][$code])) { ?>
											<?= CurrencyFormat($stat['PRICES']['NOVAT'][$code], $code) ?>
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
								<td><?= Loc::getMessage('TOTAL_SALE_SUM') ?></td>
								<? foreach ($currencies as $currency) { ?>
									<? $code = $currency['CURRENCY'] ?>
									<td>
										<? if (!empty($stat['PRICES']['PRICE'][$code])) { ?>
											<?= CurrencyFormat($stat['PRICES']['PRICE'][$code], $code) ?>
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
							
							<div class="collapse navbar-collapse pull-right">
								<ul class="nav navbar-nav">
									<li>
										<a href="javascript:void(0);" class="js-export" data-target="stands">
											<?= Loc::getMessage('STAT_EXPORT_CSV') ?>
											<span class="glyphicon glyphicon-import"></span>
										</a>
									</li>
								</ul>
							</div>
							
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
								<? foreach ($currencies as $currency) { ?>
									<? $code = $currency['CURRENCY'] ?>
									<? $curdata = $stat['STANDS']['CURRENCIES'][$code] ?>
									<tbody class="js-currency-tab js-currency-tab-<?= strtolower($code) ?>" <?= (!$first) ? ('style="display: none;"') : ('') ?>>
										<? foreach ($curdata['ITEMS'] as $pid => $item) { ?>
											<? foreach ($item as $surcharge => $subitem) { ?>
												<tr>
													<td>
														<? if (is_object($stands[$pid])) { ?>
															<?= $stands[$pid]->getTitle() ?>
														<? } else { ?>
															&mdadsh;
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
															<a href="javascript:void(0)" class="js-popover" data-html="true" data-container="body" data-toggle="popover" data-placement="right" data-title="<?= Loc::getMessage('ORDER_NUMBERS') ?>" data-content="<?= $content ?>">
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
												<b><?= Loc::getMessage('STAT_SUMM') ?></b>
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
							
							<div class="collapse navbar-collapse pull-right">
								<div id="js-filter-id" class="navbar-form navbar-left" role="search">
									<? foreach ($sections as $section) { ?>
										<button type="button" class="btn btn-sm btn-default js-filter" name="SECTIONS[]" value="<?= $section->getID() ?>">
											<?= $section->getTitle() ?>
										</button>
									<? } ?>
								</div>
								
								<ul class="nav navbar-nav">
									<li>
										<a href="javascript:void(0);" class="js-export" data-target="products">
											<?= Loc::getMessage('STAT_EXPORT_CSV') ?>
											<span class="glyphicon glyphicon-import"></span>
										</a>
									</li>
								</ul>
							</div>
							
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
								<? foreach ($currencies as $currency) { ?>
									<? $code = $currency['CURRENCY'] ?>
									<? $curdata = $stat['PRODUCTS']['CURRENCIES'][$code] ?>
									<tbody class="js-currency-tab js-currency-tab-<?= strtolower($code) ?>" <?= (!$first) ? ('style="display: none;"') : ('') ?>>
										<? foreach ($curdata['ITEMS'] as $pid => $item) { ?>
											<? foreach ($item as $surcharge => $subitem) { ?>
												<? if (is_object($products[$pid])) { ?>
													<? $product = $products[$pid] ?>
													<? $section = $product->getSection()->getMainSection() ?>
												<? } ?>
												<tr class="js-filtered-row js-filtered-row-<?= (is_object($section)) ? ($section->getID()) : ('0') ?>">
													<td>
														<? if (is_object($products[$pid])) { ?>
															<?= $products[$pid]->getTitle() ?>
														<? } else { ?>
															&mdash;
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
															<a href="javascript:void(0)" class="js-popover" data-html="true" data-container="body" data-toggle="popover" data-placement="right" data-title="<?= Loc::getMessage('ORDER_NUMBERS') ?>" data-content="<?= $content ?>">
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
												<b><?= Loc::getMessage('STAT_SUMM') ?></b>
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
		$('.js-wrapper-stats .js-currency-switch').on('click', function(e) {
			var $that = $(this);
			var $wrap = $that.closest('.js-wrapper-stats');
			var $link = $wrap.find('.js-export');
			var $tabs = $wrap.find('.js-currency-tab-' + $that.data('currency'));
			
			$wrap.find('.js-currency-switch').closest('li').removeClass('active');
			$that.closest('li').addClass('active');
			
			$wrap.find('.js-currency-tab').hide();
			$tabs.show();
			
			if ($link.data('update')) {
				$link.prop({'href': 'javascript:void(0);', 'target': '_blank'});
				$link.data('update', false);
				$link.html('<?= Loc::getMessage('STAT_UPDATE_CSV') ?> <span class="glyphicon glyphicon-import"></span>');
				$link.css('color', '#000000');
			}
		});
		
		
		// Переключение фильтров.
		$('.js-wrapper-stats .js-filter').on('click', function(e) {
			var $that = $(this);
			var $wrap = $that.closest('.js-wrapper-stats');
			var $link = $wrap.find('.js-export');
			var sects = [];
			
			$that.toggleClass('active');
			
			$wrap.find('.js-filter.active').each(function() {
				sects.push($(this).val());
			});
			
			if (sects.length == 0) {
				$wrap.find('.js-filtered-row').show();
			} else {
				$wrap.find('.js-filtered-row').hide();
				for (let s in sects) {
					$wrap.find('.js-filtered-row-' + sects[s]).show();
				}
			}
			
			if ($link.data('update')) {
				$link.prop({'href': 'javascript:void(0);', 'target': '_blank'});
				$link.data('update', false);
				$link.html('<?= Loc::getMessage('STAT_UPDATE_CSV') ?> <span class="glyphicon glyphicon-import"></span>');
				$link.css('color', '#000000');
			}
		});
		
		
		// Экспорт таблицы в CSV.
		$('.js-export').on('click', function(e) {
			var $that = $(this);
			var $wrap = $that.closest('.js-wrapper-stats');
			
			if ($that.data('update')) {
				return;
			}
			
			var filters = [];
			$('#js-filter-id button.active').each(function() {
				filters.push($(this).val());
			});
			var currency = $wrap.find('.active .js-currency-switch').data('currency');
			
			BX.showWait();
			
			$.ajax({
				url: '',
				type: 'post',
				data: {'export': true, 'target': $that.data('target'), 'currency': currency, 'filters': filters},
				dataType: 'json',
				success: function(response) {
					if (response.status) {
						$that.prop({'href': response.data['link'], 'target': '_blank'});
						$that.data('update', true);
						$that.html('<?= Loc::getMessage('STAT_DOWNLOAD_CSV') ?> <span class="glyphicon glyphicon-import"></span>');
						$that.css('color', '#2222cc');
					} else {
						alert('<?= Loc::getMessage('ERROR_UNKNOWN') ?>');
					}
					BX.closeWait();
				},
				error: function() {
					BX.closeWait();
				}
			});
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
