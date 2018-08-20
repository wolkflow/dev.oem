<?php

use Bitrix\Sale\Helpers\Admin\OrderEdit;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Helpers\Admin\Blocks;
use Bitrix\Highloadblock\HighloadBlockTable;

use Wolk\OEM\Basket;
use Wolk\OEM\Products\Base as Product;
use Wolk\OEM\OrderSketch as OrderSketch;


// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

$permission = $APPLICATION->GetGroupRight('wolk.oem');

if ($permission == 'D') {
	$APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));
}

$groups = $USER->GetUserGroupArray();

$ismanager = false;
if (in_array(GROUP_MANAGERS_ID, $groups) || in_array(GROUP_PARTNERS_ID, $groups)) {
	// $ismanager = true;
}



// подключим языковой файл
IncludeModuleLangFile(__FILE__);

$ID = (int) $_REQUEST['ID'];

if ($ID <= 0) {
    LocalRedirect('/bitrix/admin/wolk_oem_order_list.php?lang=' . LANG);
}

if (!\Bitrix\Main\Loader::includeModule('wolk.core')) {
    ShowError('Module wolk.core not installed.');
    return;
}

if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    ShowError('Module iblock not installed.');
    return;
}

if (!\Bitrix\Main\Loader::includeModule('sale')) {
    ShowError('Module sale not installed');
    return;
}

if (!\Bitrix\Main\Loader::includeModule('currency')) {
    ShowError('Module currency not installed.');
    return;
}


if ($ismanager) {
	$eids = array();
	$result = CIBlockElement::GetList([], ['IBLOCK_ID' => EVENTS_IBLOCK_ID, 'ACTIVE' => 'Y', 'PROPERTY_MANAGER' => $USER->getID()], false, false, ['ID']);
	while ($item = $result->Fetch()) {
		$eids []= (int) $item['ID'];
	}
	unset($result, $item);
	
	$result = CSaleOrderPropsValue::getList([], ['CODE' => 'EVENT_ID', '@VALUE' => $eids, 'ORDER_ID' => $ID], false, false, ['ORDER_ID']);
	if ($result->SelectedRowsCount() <= 0) {
		ShowError(Loc::getMessage('ERROR_MANAGER_ACCESS'));
		return;
	}
}


$colors = [];
if (\Bitrix\Main\Loader::includeModule('highloadblock')) {
	$hlblock = HighloadBlockTable::getById(COLORS_ENTITY_ID)->fetch();
	$entity  = HighloadBlockTable::compileEntity($hlblock);
	$class   = $entity->getDataClass();
	$result  = $class::getList(['order' => ['UF_NUM' => 'ASC']]);
	
	while ($color = $result->fetch()) {
		$colors[$color['ID']] = $color;
	}
}


$message = null;


/*
 * Заказ.
 */ 
$oemorder = new Wolk\OEM\Order($ID);
$bxorder  = Bitrix\Sale\Order::load($ID);



// Список валют.
$result = CCurrencyRates::GetList(($b = 'ID'), ($o = 'ASC'), []);
$rates  = array();
while ($irate = $result->fetch()) {
	$rates[$rate['CURRENCY']] = $irate['RATE'];
}
unset($result, $irate);

$result = CCurrency::GetList(($b = 'ID'), ($o = 'ASC'), []);
$currencies = array();
while ($currency = $result->fetch()) {
	if ($oemorder->getCurrency() == $currency['CURRENCY']) {
		$currency['ORDER_RATE'] = 1;
	} else {
		$currency['ORDER_RATE'] = round(CCurrencyRates::ConvertCurrency(1, $oemorder->getCurrency(), $currency['CURRENCY']), 6);
	}
	$currencies[$currency['CURRENCY']] = $currency;
}
unset($result, $currency);


// Список языков.
$result = CLanguage::GetList(($b = 'ID'), ($o = 'ASC'), []);
$languages = array();
while ($language = $result->fetch()) {
	$languages[$language['LID']] = $language;
}
unset($result, $language);


// Список статусов.
$statuses = Wolk\Core\Helpers\SaleOrder::getStatuses();



/*
 * Сохранение данных заказа.
 */
if (!empty($_POST)) {
    $action = (string)$_POST['action'];

	
    switch ($action) {
		
		// Пересчет заказа по курсу.
		case 'convert':
			$rate     = (float) str_replace(',', '.', (string) $_POST['RATE']);
			$currency = (string) $_POST['RATE_CURRENCY'];
			
			$result = \Wolk\Core\Helpers\SaleOrder::saveProperty($ID, 'RATE', $rate);
			$result = \Wolk\Core\Helpers\SaleOrder::saveProperty($ID, 'RATE_CURRENCY', $currency) && $result;
			
			if (!$result) {
				$message = new CAdminMessage([
					'MESSAGE' => Loc::getMessage('ERROR_CHANGE_RATE'),
					'TYPE'    => 'ERROR'
				]);
			} else {
                LocalRedirect($APPLICATION->GetCurUri());
            }
			break;
		
        
        // Пересчет наценки заказа.
		case 'surcharge':
			$surcharge = (float) $_REQUEST['SURCHARGE'];
			
            // Пересчет цен заказа.
            if (!$oemorder->recalc($surcharge)) {
                $message = new CAdminMessage([
                    'MESSAGE' => Loc::getMessage('ERROR_CHANGE_SURCHARGE'),
                    'TYPE'    => 'ERROR'
                ]);
            } else {
                LocalRedirect('/bitrix/admin/wolk_oem_order_index.php?ID='.$ID.'lang=' . LANG);
            }
			break;
        

        // Сохранение данных заказа.
        case 'data':
			$status  = (string) $_POST['STATUS'];
			$comment = (String) $_POST['COMMENTS'];
            $bill    = (string) $_POST['BILL'];
			
			$result = null;
			
			if ($status != $oemorder->getStatus() || $comment != $oemorder->getAdminComments()) {
				$bxorder->setField('COMMENTS', $comment);
				$bxorder->setField('STATUS_ID', $status);
				
				$result = $bxorder->save()->isSuccess();
				
				// $bxorder = Bitrix\Sale\Order::load($ID);
				// $result = CSaleOrder::StatusOrder($oemorder->getID(), $status);
                
                if (!$result) {
                    $message = new CAdminMessage([
                        'MESSAGE' => Loc::getMessage('ERROR_CHANGE_DATA'),
                        'TYPE'    => 'ERROR'
                    ]);
                } else {
                    // Заказчик.
                    $customer = $oemorder->getUser();
                    
                    // Шаблон письма.
                    $html = $APPLICATION->IncludeComponent('wolk:mail.order', 'status', ['ID' => $oemorder->getID(), 'STATUS' => $status, 'LANG' => $oemorder->getLanguage()]);
                    
                    // Отправка письма.
                    $event = new \CEvent();
                    $event->Send('SALE_NEW_ORDER_STATUS', SITE_DEFAULT, [
                        'EMAIL' => $customer['EMAIL'], 
                        'HTML' => $html, 
                        'THEME' => Loc::getMessage('MESSAGE_THEME_ORDER_STATUS_CHANGE', Loc::loadLanguageFile(__FILE__, $oemorder->getLanguage()), $oemorder->getLanguage())
                    ]);
                }
			}
            
            if (!empty($bill)) {
                if (!\Wolk\Core\Helpers\SaleOrder::saveProperty($ID, 'BILL', $bill)) {
                    $message = new CAdminMessage([
                        'MESSAGE' => Loc::getMessage('ERROR_CHANGE_DATA'),
                        'TYPE'    => 'ERROR'
                    ]);
                }
            }
            
            if (is_null($message)) {
                LocalRedirect($APPLICATION->GetCurUri());
            }
            break;

		
		// Отправка письма.
		case 'mail':
			$email = (string) $_POST['EMAIL'];

			if (!empty($email)) {
				// Данные.
				$order    = CSaleOrder::getByID($ID);
				$customer = CUser::getByID($order['USER_ID'])->Fetch();

				// Файл для отправки.
				$fid = $oemorder->getInvoice();


				if (\Wolk\Core\Helpers\SaleOrder::saveProperty($ID, 'SENDTIME', date('d.m.Y H:i:s'))) {
					$html = $APPLICATION->IncludeComponent(
                        'wolk:mail.order',
                        'invoice',
                        array(
                            'ID'   => $ID,
                            'LANG' => $oemorder->getLanguage()
                        )
                    );

                    
                    // Заказ.
                    $order = CSaleOrder::getByID($ID);
                    $order['PROPS'] = Wolk\Core\Helpers\SaleOrder::getProperties($ID);
                    
                    // Мероприятие.
                    $event = new Wolk\OEM\Event($order['PROPS']['EVENT_ID']['VALUE']);
                    
                    // E-mail'ы для отправки.
                    $emails = array_filter(array_unique(array_merge([$email], (array) $event->getEmails())));
                    
					// Отправка письма.
					$event = new \CEvent();
					$event->Send('SEND_INVOICE', SITE_DEFAULT, [
						'EMAIL' => implode(',', $emails),
						'HTML'  => $html,
						'THEME' => Loc::getMessage('MESSAGE_THEME_INVOICE', Loc::loadLanguageFile(__FILE__, strtolower($oemorder->getLanguage())), strtolower($oemorder->getLanguage()))
					], 'N', '', [$fid]);
				}
			}
			break;

			
		// Сохранение данных покупателя.
		case 'user':
			$number     = (string) $_POST['CLIENT_NUMBER'];
			$requisites = (string) $_POST['REQUISITES'];

			$order = CSaleOrder::getByID($ID);

			$user = new CUser();
			$user->Update($order['USER_ID'], array('UF_CLIENT_NUMBER' => $number, 'UF_REQUISITES' => $requisites));
			break;

			
        // Сохранение скетча заказа.
        case 'sketch':
			$objects = (string) $_POST['OBJECTS'];
			$image   = (string) $_POST['IMAGE'];
			
			if (!empty($objects)) {
				
				// Удаление данных о скетче.
				OrderSketch::clear($ID);
				
				// Сохранение скетча.
				$sketch = new OrderSketch();
				$result = $sketch->add([
					OrderSketch::FIELD_ORDER_ID => $ID,
					OrderSketch::FIELD_SCENE    => $objects,
					OrderSketch::FIELD_IMAGE    => $image
				]);
				$sketch->saveFile();
				
				
                if (!$result) {
                    $message = new CAdminMessage([
                        'MESSAGE' => Loc::getMessage('ERROR_CHANGE_DATA'),
                        'TYPE'    => 'ERROR'
                    ]);
				} else {
					//LocalRedirect($APPLICATION->GetCurUri());
					header("Location: " . $APPLICATION->GetCurUri());
					
					session_write_close();
					fastcgi_finish_request();
					
					// Создание рендеров.
					$oemorder = new Wolk\OEM\Order($ID);
					$oemorder->makeRenders(true);
					$oemorder->makeFilePDF(true);
					
					exit();
				}
            }
            break;
    }
}



// Данные существующего заказа.
$bundle = array();

if (empty($oemorder)) {
	die('No order');
	
}
$bundle = $oemorder->getFullData();

// Мероприятие.
$event = $oemorder->getEvent(true);


// В заказе есть стенд.
foreach ($bundle['BASKETS'] as $basket) {
	if ($basket['PROPS']['STAND']['VALUE'] == 'Y') {
		$bundle['STAND'] = $basket;
		break;
	}
}

// Заказчик.
$customer = $bundle['USER'];


// Курс пересчета.
$rate = $oemorder->getRate();
$rate_currency = $oemorder->getRateCurrency();

$result = CCurrencyRates::GetList(($b = 'ID'), ($o = 'ASC'), []);
$rates  = array();
while ($irate = $result->fetch()) {
	$rates[$rate['CURRENCY']] = $irate['RATE'];
}
unset($result, $irate);



// Данные для скетча.
$sketch = $oemorder->getSketch();
if (is_object($sketch)) {
	$sketch = json_decode($sketch->getScene(), true);
} else {
	$sketch = ['objects' => []];
}
$sketch['items'] = [];


foreach ($bundle['BASKETS'] as &$basket) {
	
	// Продкуция.
	$basket['PRODUCT'] = $element = new Wolk\OEM\Products\Base($basket['PRODUCT_ID']);
	
	// Добавление наценки на товары.
    $basket['SURCHARGE_PRICE'] = $basket['PRICE'] * (1 + $oemorder->getSurchargePercent() / 100);
 
	
	if (empty($element)) { 
		continue;
	}
	
	if (!$element->isSketchShow()) {
		continue;
	}
	
	if (!is_file($_SERVER['DOCUMENT_ROOT'] . $element->getSketchImagePrepared())) {
		continue;
	}
	
	$object = [
		'id'        => $basket['PROPS']['BID']['VALUE'],
		'quantity'  => $basket['QUANTITY'],
		'pid'       => $element->getID(),
		'title'     => $element->getTitle(),
		'type'      => $element->getSketchType(),
		'w'         => $element->getSketchWidth() / 1000,
		'h'         => $element->getSketchHeight() / 1000,
		'imagePath' => $element->getSketchImagePrepared(),
	];
	$sketch['items'][$basket['PROPS']['BID']['VALUE']] = $object;
}
unset($basket);


// echo '<pre>'; print_r($sketch); echo '</pre>';


// Печать заказа.
$orderprint = new \Wolk\OEM\OrderPrint($ID);

// Описываем табы административной панели битрикса.
$aTabs = [
    [
        'DIV'   => 'data',
        'TAB'   => Loc::getMessage('ORDER_DATA'),
        'ICON'  => 'data',
        'TITLE' => Loc::getMessage('ORDER_NO') . $ID.' | '.$oemorder->getLanguage()
    ]
];


/*
 * Инициализируем табы
 */
$oTabControl = new CAdmintabControl('tabControl', $aTabs);


$APPLICATION->SetAdditionalCSS('/assets/bootstrap/css/bootstrap.min.css');

Bitrix\Main\Page\Asset::getInstance()->addJs('https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js');
Bitrix\Main\Page\Asset::getInstance()->addJs('/assets/bootstrap/js/bootstrap.min.js');

Bitrix\Main\Page\Asset::getInstance()->addJs('/local/templates/.default/javascripts/designer.js');
Bitrix\Main\Page\Asset::getInstance()->addJs('/local/templates/.default/build/js/vendor.js');

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

?>

<? // Скетч // ?>
<script type="text/javascript">
	window.addEventListener('touchmove', function(event) {
		event.preventDefault();
	}, false);

	if (typeof window.devicePixelRatio != 'undefined' && window.devicePixelRatio > 2) {
		var meta = document.getElementById('viewport');
		meta.setAttribute('content', 'width=device-width, initial-scale=' + (2 / window.devicePixelRatio) + ', user-scalable=no');
	}
	
    $(document).ready(function() {
		
		// Генерация счета.
        $('#js-invoice-button-id').on('click', function(e) {
            $('#js-invoice-response-id').html('');
            $.ajax({
                url: '/bitrix/admin/wolk_oem_remote.php',
                data: {'action': 'invoice-print', 'oid': <?= $ID ?>, 'tpl': $('#js-invoice-select-id').val()},
                dataType: 'json',
				beforeSend: function() {
					BX.closeWait('.js-invoices-wrapper');
					BX.showWait('.js-invoices-wrapper');
				},
                success: function (response) {
					BX.closeWait('.js-invoices-wrapper');
                    if (response.status) {
                        $('#js-invoice-response-id').html('<button type="button" class="btn btn-info" onclick="javascript: window.open(\'' + response.data['link'] + '?' + (new Date()).getTime() + '\');" target="_blank"><?= Loc::getMessage('DOWNLOAD_INVOICE') ?></button>');

						var exist = false;
						$('#js-order-invoice-select-id option').each(function() {
							if ($(this).val() == response.data['name']) {
								exist = true;
							}
						});
						if (!exist) {
							$('#js-order-invoice-select-id').append('<option value="' + response.data['name'] + '">' + response.data['name'] + '</option>');
						}
                    } else {
                        alert(response.message);
                    }
                }
            });
        });


		// Генерация заказа.
		$('#js-print-order-button-id').on('click', function (event) {
			$.ajax({
                url: '/bitrix/admin/wolk_oem_remote.php',
                data: {'action': 'order-print', 'oid': <?= $ID ?>},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
						window.open(response.data['link'], '_blank');
                    } else {
                        alert(response.message);
                    }
                }
            });
		});
		
		
		// Генерация формы подвесной конструкции.
		$('.js-order-form-print').on('click', function (event) {
			var $that = $(this);
			var $wrap = $that.closest('.js-basket-form');
			
			$.ajax({
                url: '/bitrix/admin/wolk_oem_remote.php',
                data: {'action': 'order-form-print', 'oid': <?= $ID ?>, 'bid': $that.data('bid')},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
						$wrap.find('.js-order-form-link').attr('href', response.data['link']).show();
                    }
                }
            });
		});
		

		// Сохранение скетча.
		$('#js-sketch-button-id').on('click', function (event) {
			var image;
			var savesketch = function() {
				$('#js-sketch-scene-input-id').val(JSON.stringify(ru.octasoft.oem.designer.Main.getScene()));
				$('#js-sketch-image-input-id').val(ru.octasoft.oem.designer.Main.saveJPG());
			}
			var $that = $(this);
			
			$.when(savesketch()).done(function() {
                $that.closest('form').trigger('submit');
			});
		});

		
		// Смена валюты пересчета.
		$('#js-convert-select-id').on('change', function() {
			$('#js-convert-rate-id').val($(this).find('option:selected').data('rate'));
		});
		

        /*
         * Обработчики скетча.
         */
		var sketchitems = <?= json_encode(array_values($sketch['items'])) ?>;
        
		var loadsketch = function() {
			
			var gridX = parseFloat(<?= (float) ($oemorder->getSketchWidth()) ?: 5 ?>);
			var gridY = parseFloat(<?= (float) ($oemorder->getSketchDepth()) ?: 5 ?>);
			
			(window.resizeEditor = function(items) {
				var height =  Math.max(120 + (items.length * 135), $(window).height());
				$('#designer').height(height);
				
				window.editorScrollTop = $('#designer').offset().top - 30;
				window.editorScrollBottom = window.editorScrollTop - 30 + height - $(window).height();
				if (window.editorScrollBottom < window.editorScrollTop) {
					window.editorScrollTop = window.editorScrollBottom;
				}
			})(sketchitems);
			
			window.onEditorReady = function() {
                $(window).on("scroll", function(e) {
                    ru.octasoft.oem.designer.Main.scroll(window.editorScrollTop, window.editorScrollBottom, $(this).scrollTop());
                });
				
				ru.octasoft.oem.designer.Main.init({
					w: gridX,
					h: gridY,
					type: '<?= (!empty($bundle['ORDER']['PROPS']['SFORM']['VALUE'])) ? ($bundle['ORDER']['PROPS']['SFORM']['VALUE']) : ('row') ?>',
					items: sketchitems,
					placedItems: <?= (!empty($sketch['objects'])) ? (json_encode($sketch['objects'])) : ('{}') ?>
				});
			};
			lime.embed('designer', 0, 0, '', '/');
			
			setTimeout(function() { window.resizeEditor(sketchitems); }, 300);
		}
		
		if ($('#sketch-order').length) {
			loadsketch();
		}
    });
</script>

<? if (!empty($message)) { ?>
    <?= $message->show() ?>
<? } ?>

<div class="adm-detail-toolbar"><span style="position:absolute;"></span>
    <a href="/bitrix/admin/wolk_oem_order_list.php" class="adm-detail-toolbar-btn" title="<?= Loc::getMessage('GOTO_ORDERS_LIST') ?>" id="btn_list">
        <span class="adm-detail-toolbar-btn-l"></span><span class="adm-detail-toolbar-btn-text"><?= Loc::getMessage('ORDERS_LIST') ?></span><span class="adm-detail-toolbar-btn-r"></span>
    </a>
    <div class="adm-detail-toolbar-right" style="top: 0px;">
        <a href="/bitrix/admin/wolk_oem_order_edit.php?ID=<?= $ID ?>" class="adm-btn adm-btn-edit"><?= Loc::getMessage('ORDER_EDIT') ?></a>
		<a href="<?= $orderprint->getURL() ?>" target="_blank" class="adm-btn adm-btn-edit"><?= Loc::getMessage('ORDER_PRINT') ?></a>
		<a href="<?= $oemorder->getFilePDF() ?>" target="_blank" id="js-sketch-image-download-id" class="adm-btn adm-btn-edit"><?= Loc::getMessage('SKETCH_PRINT') ?></a>
    </div>
</div>

<div class="adm-bus-orderinfoblock adm-detail-tabs-block-pin" id="sale-order-edit-block-order-info">
    <div class="adm-bus-orderinfoblock-container">
        <div class="adm-bus-orderinfoblock-title">
            <?= Loc::getMessage('ORDER_NO') ?> <?= $oemorder->getID() ?>
        </div>
        <div class="adm-bus-orderinfoblock-content">
            <div class="adm-bus-orderinfoblock-content-block-customer" style="width: 50%">
                <ul class="adm-bus-orderinfoblock-content-customer-info">
                    <li>
                        <span class="adm-bus-orderinfoblock-content-customer-info-param"><?= Loc::getMessage('EVENT') ?>:</span>
						<span class="adm-bus-orderinfoblock-content-customer-info-value">
							<?= $event->getName() ?>
						</span>
                    </li>
                    <li>
                        <span class="adm-bus-orderinfoblock-content-customer-info-param"><?= Loc::getMessage('PAVILION') ?>:</span>
						<span class="adm-bus-orderinfoblock-content-customer-info-value">
							<?= $oemorder->getPavilion() ?>
						</span>
                    </li>
                    <li>
                        <span class="adm-bus-orderinfoblock-content-customer-info-param"><?= Loc::getMessage('STANDNUM') ?>:</span>
						<span class="adm-bus-orderinfoblock-content-customer-info-value">
							<?= $oemorder->getStandNumber() ?>
						</span>
                    </li>
                    <li>
                        <span class="adm-bus-orderinfoblock-content-customer-info-param"><?= Loc::getMessage('DATE_CREATED') ?>:</span>
						<span class="adm-bus-orderinfoblock-content-customer-info-value">
							<?= date('d.m.Y H:i', strtotime($oemorder->getDateCreated())) ?>
						</span>
                    </li>
                    <li>
                        <span class="adm-bus-orderinfoblock-content-customer-info-param"><?= Loc::getMessage('ORDER_STATUS') ?>:</span>
						<span class="adm-bus-orderinfoblock-content-customer-info-value">
							<?= $statuses[$oemorder->getStatus()]['NAME'] ?>
						</span>
                    </li>
                </ul>
            </div>
            <div class="adm-bus-orderinfoblock-content-block-order" style="width: 50%">
                <ul class="adm-bus-orderinfoblock-content-order-info">
                    <li>
                        <span class="adm-bus-orderinfoblock-content-order-info-param"><?= Loc::getMessage('COST_STAND') ?></span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($bundle['STAND']['PRICE'] * $bundle['STAND']['QUANTITY'] * $rate, $rate_currency) ?>
						</span>
                    </li>
                    <li>
                        <span class="adm-bus-orderinfoblock-content-order-info-param"><?= Loc::getMessage('TOTAL_COST_PRODUCTS') ?></span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($bundle['PRICES']['BASKET'] * $rate, $rate_currency) ?>
							<? if (!empty($bundle['STAND']['PRICE'])) { ?>
								/ <?= CurrencyFormat(($bundle['PRICES']['BASKET'] - $bundle['STAND']['PRICE'] * $bundle['STAND']['QUANTITY']) * $rate, $rate_currency) ?>
							<? } ?>
						</span>
                    </li>
					<li class="adm-bus-orderinfoblock-content-redtext">
                        <span class="adm-bus-orderinfoblock-content-order-info-param"><?= Loc::getMessage('SURCHARGES') ?></span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							(<?= $oemorder->getSurchargePercent() ?>%)
                            <?= CurrencyFormat($oemorder->getSurchargePrice() * $rate, $rate_currency) ?>
						</span>
                    </li>
					<li>
                        <span class="adm-bus-orderinfoblock-content-order-info-param"><?= Loc::getMessage('VAT') ?></span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($oemorder->getTAX() * $rate, $rate_currency) ?>
						</span>
                    </li>
                </ul>
                <ul class="adm-bus-orderinfoblock-content-order-info-result">
                    <li>
                        <span class="adm-bus-orderinfoblock-content-order-info-param"><?= Loc::getMessage('TOTAL') ?></span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($oemorder->getPrice() * $rate, $rate_currency) ?>
							<? if ($rate != 1) { ?>
								/ <?= CurrencyFormat($oemorder->getPrice(), $oemorder->getCurrency()) ?>
							<? } ?>
						</span>
                    </li>
                </ul>				
            </div>
        </div>
    </div>
</div>

<? $oTabControl->Begin() ?>
<? $oTabControl->BeginNextTab() ?>
<tr class="lm_carsale_details lm_admin_table">
    <td width="50%" valign="top" class="lm_inspector_left_block">

        <div style="position: relative; vertical-align: top;">
            <div style="height:5px;width:100%"></div>
            <a id="edit-order"></a>
            <div class="adm-container-draggable">
                <div class="adm-bus-statusorder">
                    <div class="adm-bus-component-container">
                        <div class="adm-bus-component-title-container">
                            <div class="adm-bus-component-title"><?= Loc::getMessage('ORDER_DATA') ?></div>
                        </div>
                        <div class="adm-bus-component-content-container">
                            <div class="adm-bus-table-container">
                                <form method="post">
                                    <input type="hidden" name="action" value="data"/>
                                    <table class="adm-detail-content-table edit-table" style="width: 60%;">
                                        <tr>
                                            <td class="adm-detail-content-cell-l"><?= Loc::getMessage('ORDER_STATUS') ?>:</td>
                                            <td class="adm-detail-content-cell-r">
                                                <select name="STATUS" class="adm-bus-select">
                                                    <? foreach ($statuses as $status) { ?>
                                                        <option
                                                            value="<?= $status['ID'] ?>" <?= ($status['ID'] == $oemorder->getStatus()) ? ('selected') : ('') ?>>
                                                            <?= $status['NAME'] ?>
                                                        </option>
                                                    <? } ?>
                                                </select>
											</td>
										</tr>
										<tr>
                                            <td class="adm-detail-content-cell-l"><?= Loc::getMessage('ORDER_COMMENT') ?>:</td>
                                            <td class="adm-detail-content-cell-r">
												<textarea name="COMMENTS" cols="45" rows="5"><?= $oemorder->getAdminComments() ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="adm-detail-content-cell-l"><?= Loc::getMessage('ORDER_INVOICE') ?>:</td>
                                            <td class="adm-detail-content-cell-r">
                                                <input type="text" name="BILL" value="<?= $oemorder->getBillNumber() ?>" class="form-control" size="40" />
                                            </td>
                                        </tr>
										<tr>
                                            <td class="adm-detail-content-cell-l"><?= Loc::getMessage('ORDER_NOTE') ?>:</td>
                                            <td class="adm-detail-content-cell-r">
												<div>
													<?= ($oemorder->getComments()) ?: ('&mdash;') ?>
												</div>
                                            </td>
                                        </tr>
										<tr>
                                            <td></td>
											<td>
                                                <button type="submit" class="btn btn-info"><?= Loc::getMessage('SAVE') ?></button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <hr width="96%" color="e7f2f2"/>
                        <div class="adm-bus-component-content-container">
                            <div class="adm-bus-table-container js-invoices-wrapper">
								<form method="post">
									<input type="hidden" name="action" value="convert" />
									<table cellpadding="5">
										<tr>
											<td class="adm-detail-content-cell-l" width="235"><?= Loc::getMessage('RATE') ?>:</td>
											<td>
												<div class="input-group">
													<span class="input-group-addon">
														<?= $oemorder->getCurrency() ?> &rarr;
													</span>
													
													<? // Счета // ?>
													<select name="RATE_CURRENCY" id="js-convert-select-id" class="form-control" style="width: 85px;">
														<? foreach ($currencies as $code => $currency) { ?>
															<option value="<?= $code ?>" data-rate="<?= $currency['ORDER_RATE'] ?>" <?= ($code == $oemorder->getRateCurrency()) ? ('selected') : ('') ?>>
																<?= $code ?>
															</option>
														<? } ?>
													</select>
													<input type="text" name="RATE" id="js-convert-rate-id" value="<?= $oemorder->getRate() ?>" class="form-control" style="width: 100px;" />
													<span class="input-group-btn">
														<button type="submit" class="btn btn-info" /><?= Loc::getMessage('RECALC') ?></button>
													</span>
												</div>
											</td>
											<td>
												<? if (!empty($order['PROPS']['RATE']['VALUE'])) { ?>
													<?= Loc::getMessage('RATED') ?>
													<b><?= $order['PROPS']['RATE']['VALUE'] ?> <?= $order['PROPS']['RATE_CURRENCY']['VALUE'] ?></b>
												<? } ?>
											</td>
										</tr>
									</table>
								</form>
								
								<hr/>
                                
                                <form method="post">
									<input type="hidden" name="action" value="surcharge" />
									<table cellpadding="5">
										<tr>
											<td class="adm-detail-content-cell-l" width="235"><?= Loc::getMessage('SURCHARGE') ?>:</td>
											<td>
												<div class="input-group">
													<input type="text" name="SURCHARGE" value="<?= $order['PROPS']['SURCHARGE']['VALUE_ORIG'] ?>" class="form-control" size="30" />
													<span class="input-group-btn">
														<button type="submit" class="btn btn-info" /><?= Loc::getMessage('RECALC') ?></button>
													</span>
												</div>
											</td>
										</tr>
									</table>
								</form>
                                
                                <hr/>
							
                                <table cellpadding="5">
                                    <tr>
                                        <td class="adm-detail-content-cell-l" width="235"><?= Loc::getMessage('INVOICE_GENERATION') ?>:</td>
                                        <td width="200">
											<div class="input-group">
												<? // Счета // ?>
												<select name="invoice" id="js-invoice-select-id" class="form-control" style="width: 257px;">
													<? foreach ($event->getInvoices() as $index => $invoice) { ?>
														<option value="<?= $event->getInvoices('VALUE_XML_ID')[$index] ?>">
															<?= $invoice ?>
														</option>
													<? } ?>
												</select>
												<span class="input-group-btn">
													<button type="button" id="js-invoice-button-id" class="btn btn-info"><?= Loc::getMessage('GET_INCOVCE') ?></button>
												</span>
											</div>
										</td>
                                        <td>
                                            <div id="js-invoice-response-id">
												<? $invoice = $oemorder->getInvoice() ?>
												<? if (!empty($invoice)) { ?>
													<button type="button" class="btn btn-info" onclick="javascript: window.open('<?= $oemorder->getInvoiceLink() ?>?<?= time() ?>');" target="_blank"><?= Loc::getMessage('DOWNLOAD_INVOICE') ?></button>
												<? } ?>
											</div>
                                        </td>
                                    </tr>
								</table>
								<br/>
								
								<form method="post">
									<input type="hidden" name="action" value="mail" />
									
									<table cellpadding="5">
										<tr>
											<td class="adm-detail-content-cell-l" width="235"><?= Loc::getMessage('INVOCE_SENDING') ?>:</td>
											<td>
												<div class="input-group">
													<input type="text" name="EMAIL" value="<?= $customer['EMAIL'] ?>" class="form-control" size="30" />
													<span class="input-group-btn">
														<button type="submit" id="js-send-button-id" class="btn btn-info"><?= Loc::getMessage('SEND_INVOCE') ?></button>
													</span>
												</div>
											</td>
											<td style="padding: 0 0 0 10px;">
												<? if (!empty($bundle['ORDER']['PROPS']['SENDTIME']['VALUE'])) { ?>
													<?= Loc::getMessage('SENDED') ?> <?= date('H:i d.m.Y', strtotime($bundle['ORDER']['PROPS']['SENDTIME']['VALUE'])) ?>
												<? } else { ?>
													<?= Loc::getMessage('NOT_SENDED_YET') ?>
												<? } ?>
											</td>
										</tr>
									</table>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
		
		<? /*
		<? $baskets = $oemorder->getFormHandingBaskets() ?>
		<? if (!empty($baskets)) { ?>
			<? $print = new \Wolk\OEM\Prints\Form($oemorder->getID()); ?>
			<div style="position: relative; vertical-align: top;">
				<div style="height: 5px; width: 100%;"></div>
				<a id="company-order"></a>
				<div class="adm-container-draggable">
					<div class="adm-bus-statusorder">
						<div class="adm-bus-component-container">
							<div class="adm-bus-component-title-container">
								<div class="adm-bus-component-title">
									<?= Loc::getMessage('FORM_HANDING_STRUCTURE') ?>
								</div>
							</div>
							<div class="adm-bus-component-content-container">
								<div class="adm-bus-table-container">
									<button id="js-order-form-print-id" type="button" class="btn btn-info">
										<?= Loc::getMessage('PRINT_FORM') ?>
									</button>
									<? if ($print->isExists()) { ?>
										<a id="js-order-form-link-id" href="<?= $print->getPathPDF() ?>" class="btn btn-default" target="_blank">
											<?= Loc::getMessage('DOWNLOAD_PDF') ?>
										<a/>
									<? } else { ?>
										<a id="js-order-form-link-id" href="" style="display: none;" class="btn btn-default" target="_blank">
											<?= Loc::getMessage('DOWNLOAD_PDF') ?>
										<a/>
									<? } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<? } ?>
		*/ ?>


        <div style="position: relative; vertical-align: top;">
            <div style="height:5px;width:100%"></div>
            <a id="company-order"></a>
            <div class="adm-container-draggable">
                <div class="adm-bus-statusorder">
                    <div class="adm-bus-component-container">
                        <div class="adm-bus-component-title-container">
                            <div class="adm-bus-component-title">
								<?= Loc::getMessage('COMPANY_DATA') ?>
							</div>
                        </div>
                        <div class="adm-bus-component-content-container">
                            <div class="adm-bus-table-container">
                                <form method="post">
                                    <input type="hidden" name="action" value="user" />
                                    <table class="adm-detail-content-table edit-table">
                                        <tr>
                                            <td class="adm-detail-content-cell-l"><?= Loc::getMessage('COMPANY_NAME') ?>:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <?= $customer['WORK_COMPANY'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="adm-detail-content-cell-l"><?= Loc::getMessage('COMPANY_EMAIL') ?>:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <?= $customer['EMAIL'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="adm-detail-content-cell-l"><?= Loc::getMessage('COMPANY_PHONE') ?>:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <?= $customer['PERSONAL_PHONE'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="adm-detail-content-cell-l"><?= Loc::getMessage('COMPANY_VATID') ?>:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <?= $customer['UF_VAT'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="adm-detail-content-cell-l"><?= Loc::getMessage('COMPANY_CONTACT') ?>:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <?= trim($customer['NAME'] . ' ' . $customer['LAST_NAME']) ?>
                                            </td>
                                        </tr>
										<tr>
                                            <td class="adm-detail-content-cell-l"><?= Loc::getMessage('COMPANY_REQUISITES') ?>:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <textarea cols="10" rows="6" name="REQUISITES" class="form-control"><?= $customer['UF_REQUISITES'] ?></textarea>
                                            </td>
                                        </tr>
										<tr>
                                            <td class="adm-detail-content-cell-l"><?= Loc::getMessage('COMPANY_CLIENT_NUMBER') ?>:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <input type="text" name="CLIENT_NUMBER" value="<?= $customer['UF_CLIENT_NUMBER'] ?>" class="form-control" size="10" />
                                            </td>
                                        </tr>
										<tr>
                                            <td></td>
											<td>
                                                <button type="submit" class="btn btn-info"><?= Loc::getMessage('SAVE') ?></button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div style="position: relative; vertical-align: top;">
            <div style="height:5px;width:100%"></div>
            <a id="positions-order"></a>
            <div class="adm-container-draggable">
                <div class="adm-bus-statusorder">
                    <div class="adm-bus-component-container">
                        <div class="adm-bus-component-title-container">
                            <div class="adm-bus-component-title">
								<?= Loc::getMessage('PRODUCT_LIST') ?>
							</div>
                        </div>
                        <div class="adm-bus-component-content-container">
                            <div class="adm-bus-table-container">
                                <table class="adm-s-order-table-ddi-table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <td align="left"><?= Loc::getMessage('PRODUCT_IMAGE') ?></td>
                                            <td align="left"><?= Loc::getMessage('PRODUCT_NAME') ?></td>
                                            <td align="left"><?= Loc::getMessage('PRODUCT_QUANTITY') ?></td>
                                            <td align="left"><?= Loc::getMessage('PRODUCT_PRICE_CATALOG') ?></td>
                                            <td align="left"><?= Loc::getMessage('PRODUCT_PRICE') ?></td>
                                            <td align="left"><?= Loc::getMessage('PRODUCT_COST') ?></td>
											<? /* <td align="left"><?= Loc::getMessage('PRODUCT_INFO') ?></td> */ ?>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: left; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(221, 221, 221);">
                                        <? $cnt = 0; ?>
                                        <? foreach ($bundle['BASKETS'] as $basket) { ?>
                                            <?	// Входит в стандартную комплектацию.
												$quantity = intval($basket['QUANTITY']);
											
												if (!isset($basket['PROPS']['INCLUDED']['VALUE'])) {
													if ($basket['PROPS']['INCLUDING']['VALUE'] == 'Y') {
														continue;
													} 
												} else {
													if (intval($basket['QUANTITY']) <= intval($basket['PROPS']['INCLUDED']['VALUE'])) {
														continue;
													}
													$quantity = intval($basket['QUANTITY']) - intval($basket['PROPS']['INCLUDED']['VALUE']);
												}
											?>
                                            <tr>
                                                <td class="adm-s-order-table-ddi-table-img">
													<? $isrc = $basket['PRODUCT']->getImageSrc() ?>
                                                    <? if (!empty($isrc)) { ?>
                                                        <img src="<?= $isrc ?>" width="78" height="78" />
                                                    <? } else { ?>
                                                        <div class="no_foto"><?= Loc::getMessage('NO_IMAGE') ?></div>
                                                    <? } ?>
                                                </td>
                                                <td align="left">
													<? if ($basket['PROPS']['STAND']['VALUE'] == 'Y') { ?> 
														<?= Loc::getMessage('STAND') ?> &laquo;<?= $bundle['STAND']['NAME'] ?>&raquo;
													<? } else { ?>
														<?= $basket['PRODUCT']->getTitle() ?>
													<? } ?>
												</td>
                                                <td align="center">
													<?= $quantity ?>
												</td>
                                                <td align="left">
                                                    <?= CurrencyFormat($basket['PRICE'] * $rate, $rate_currency) ?>
                                                </td>
                                                <td align="left">
                                                    <?= CurrencyFormat($basket['SURCHARGE_PRICE'] * $rate, $rate_currency) ?>
                                                </td>
                                                <td align="left">
                                                    <?= CurrencyFormat($basket['SURCHARGE_PRICE'] * $basket['QUANTITY'] * $rate, $rate_currency) ?>
                                                </td>
												<? /*<td>
													<?= $basket['PROPS']['COMMENT']['VALUE'] ?>
												</td> */ ?>
                                            </tr>
                                            <? $cnt++ ?>
                                        <? } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" align="left" style="height: 50px; margin-left: 15px;">
                                                <b style="margin-left: 30px;"><?= Loc::getMessage('TOTAL_PRODUCTS') ?>: <?= $cnt ?></b>
                                            </td>
                                            <td colspan="5" align="right">
                                                <h2 style="padding: 3px 20px 10px 0;">
													<?= Loc::getMessage('TOTAL') ?>: 
													<?= CurrencyFormat(($oemorder->getPrice() - $oemorder->getTax()) * $rate, $rate_currency) ?>
												</h2>
                                            </td>
                                        <tr>
                                        </tr>
                                            <td colspan="2"></td>
                                            <td colspan="5" align="right">
                                                <h2 style="padding: 3px 20px 20px 0;">
													<?= Loc::getMessage('TOTAL_VAT') ?>: 
													<?= CurrencyFormat($oemorder->getPrice() * $rate, $rate_currency) ?>
												</h2>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
		<div style="position: relative; vertical-align: top;">
            <div style="height:5px;width:100%"></div>
            <a id="company-order"></a>
            <div class="adm-container-draggable">
                <div class="adm-bus-statusorder">
                    <div class="adm-bus-component-container">
                        <div class="adm-bus-component-title-container">
                            <div class="adm-bus-component-title">
								<?= Loc::getMessage('ATTACHMENTS') ?>
							</div>
                        </div>
                        <div class="adm-bus-component-content-container">
                            <div class="adm-bus-table-container">
								<ul>
									<? foreach ($bundle['BASKETS'] as $basket) { ?>
										<? if (empty($basket['PRODUCT'])) { continue; } ?>
										
										<? $params = json_decode($basket['PROPS']['PARAMS']['VALUE'], true) ?>
										
										<? if (array_key_exists(Basket::PARAM_FILE, $params) && !empty($params[Basket::PARAM_FILE])) { ?>
											<li>
												<a href="<?= CFile::getPath($params[Basket::PARAM_FILE]) ?>" target="_blank"><?= Loc::getMessage('DOWNLOAD') ?></a>
                                                | <i><?= $basket['PRODUCT']->getTitle()?></i>
											</li>
										<? } ?>
										
										<? if (array_key_exists(Basket::PARAM_LINK, $params) && !empty($params[Basket::PARAM_LINK])) { ?>
											<li>
												<a href="<?= $params[Basket::PARAM_LINK] ?>" target="_blank"><?= htmlspecialchars($params[Basket::PARAM_LINK]) ?></a>
                                                | <i><?= $basket['PRODUCT']->getTitle()?></i>
											</li>
										<? } ?>
										
										<? if ($basket['PRODUCT']->isSpecialType(Product::SPECIAL_TYPE_FASCIA)) { ?>
											<li>
												<?= Loc::getMessage('FASCIA') ?>:
												(<i><?= $colors[$params['COLOR']['ID']]['UF_XML_ID'] ?></i>):
												<b><?= $params['TEXT'] ?></b>
											</li>
										<? } ?>
									<? } ?>
								</ul>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
		
		<div style="position: relative; vertical-align: top;">
            <div style="height: 5px; width: 100%;"></div>
            <a id="company-order"></a>
            <div class="adm-container-draggable">
                <div class="adm-bus-statusorder">
                    <div class="adm-bus-component-container">
                        <div class="adm-bus-component-title-container">
                            <div class="adm-bus-component-title">
								<?= Loc::getMessage('FILLED_FORMS') ?>
							</div>
                        </div>
                        <div class="adm-bus-component-content-container">
                            <div class="adm-bus-table-container">
								
								<? foreach ($bundle['BASKETS'] as $basket) { ?>
									<? if (empty($basket['PRODUCT'])) { continue; } ?>
									
									<? $params = json_decode($basket['PROPS']['PARAMS']['VALUE'], true) ?>
									
									<? if (!empty($params[Basket::PARAM_FORM_HANGING_STRUCTURE])) { ?>
										<div class="filled-form js-basket-form">
											<table>
												<? foreach ($params[Basket::PARAM_FORM_HANGING_STRUCTURE] as $code => $prop) { ?>
													<tr>
														<td><?= Loc::getMessage('FIELD_FORM_' . $code) ?>:</td>
														<td>
															<b><?= $prop ?></b>
														</td>
													</tr>
												<? } ?>
											</table>
											
											<? $print = new \Wolk\OEM\Prints\Form($oemorder->getID(), $basket['ID']); ?>
											
											<div class="adm-bus-table-container">
												<button type="button" class="js-order-form-print btn btn-info" data-bid="<?= $basket['ID'] ?>">
													<?= Loc::getMessage('PRINT_FORM') ?>
												</button>
												<? if ($print->isExists()) { ?>
													<a href="<?= $print->getPathPDF() ?>" class="js-order-form-link btn btn-default" target="_blank">
														<?= Loc::getMessage('DOWNLOAD_PDF') ?>
													</a>
												<? } else { ?>
													<a href="#" style="display: none;" class="js-order-form-link btn btn-default" target="_blank">
														<?= Loc::getMessage('DOWNLOAD_PDF') ?>
													</a>
												<? } ?>
											</div>
										</div>
									<? } ?>
								<? } ?>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		

        <div style="position: relative; vertical-align: top;">
			
            <? if (!empty($sketch['objects'])) { ?>
				<div style="height: 5px; width: 100%"></div>
				<a id="sketch-order"></a>
				<div class="adm-container-draggable">
					<div class="adm-bus-statusorder">
						<div class="adm-bus-component-container">
							<div class="adm-bus-component-title-container">
								<div class="adm-bus-component-title"><?= Loc::getMessage('SKETCH') ?></div>
							</div>
							<div class="adm-bus-component-content-container">
								<div class="adm-bus-table-container">
								
									<? // Контейнер для скетча. // ?>
									<div id="designer" style="margin-top:40px; width: 940px; height:680px; margin-bottom: 50px;" onmouseout="ru.octasoft.oem.designer.Main.stopDragging()"></div>
									
									<form method="post">
										<input type="hidden" name="action" value="sketch" />
										<input type="hidden" name="OBJECTS" value="" id="js-sketch-scene-input-id" />
										<input type="hidden" name="IMAGE" value="" id="js-sketch-image-input-id" />
										
										<button type="button" id="js-sketch-button-id" class="btn btn-info"><?= Loc::getMessage('SAVE') ?></button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			<? } ?>
			
        </div>
    </td>
</tr>

<? $oTabControl->EndTab() ?>
<? $oTabControl->End() ?>

<script>
    $(document).ready(function() {
		
        $('html').addClass('wolk_admin_pages_no_conflict');
		
	});
</script>
<style>
	@font-face {
		font-family: 'Gotham Pro Bold';
		src: url('/assets/fonts/gothaprobol-webfont.eot');
		src: url('/assets/fonts/gothaprobol-webfont.eot?#iefix') format('embedded-opentype'),
		url('/assets/fonts/gothaprobol-webfont.svg#my-font-family') format('svg'),
		url('/assets/fonts/gothaprobol-webfont.woff') format('woff'),
		url('/assets/fonts/gothaprobol-webfont.ttf') format('truetype');
		font-weight: normal;
		font-style: normal;
	}
	@font-face {
		font-family: 'Gotham Pro Regular';
		src: url('/assets/fonts/gothaproreg-webfont.eot');
		src: url('/assets/fonts/gothaproreg-webfont.eot?#iefix') format('embedded-opentype'),
		url('/assets/fonts/gothaproreg-webfont.svg#my-font-family') format('svg'),
		url('/assets/fonts/gothaproreg-webfont.woff') format('woff'),
		url('/assets/fonts/gothaproreg-webfont.ttf') format('truetype');
		font-weight: normal;
		font-style: normal;
	}
	.filled-form {
		border: solid 1px #cccccc;
		border-right: none;
		border-top: none;
		border-bottom: none;
		border-radius: 30px;
		padding: 15px;
		max-width: 800px;
	}
	
    #js-form-user-select-id {
        cursor: pointer;
    }
	
    .wolk_admin_pages_no_conflict * {
        -webkit-box-sizing: initial !important;
        -moz-box-sizing: initial !important;
        box-sizing: initial !important;
    }
    .linemedia_carsale_dealer_list .adm-workarea .adm-filter-box-sizing .adm-select,
    .linemedia_carsale_auction_admin .adm-workarea .adm-filter-box-sizing .adm-select,
    .wolk_admin_pages_no_conflict .adm-workarea .adm-filter-box-sizing .adm-select,
    .linemedia_carsale_dealer_list .adm-workarea input[type="submit"], .linemedia_carsale_dealer_list .adm-workarea input[type="button"], .linemedia_carsale_dealer_list .adm-workarea input[type="reset"],
    .linemedia_carsale_auction_admin .adm-workarea input[type="submit"], .linemedia_carsale_auction_admin .adm-workarea input[type="button"], .linemedia_carsale_auction_admin .adm-workarea input[type="reset"],
    .wolk_admin_pages_no_conflict .adm-workarea input[type="submit"], .wolk_admin_pages_no_conflict .adm-workarea input[type="button"], .wolk_admin_pages_no_conflict .adm-workarea input[type="reset"],
    .wolk_admin_pages_no_conflict input, .wolk_admin_pages_no_conflict button, .wolk_admin_pages_no_conflict select, .wolk_admin_pages_no_conflict textarea {
        box-sizing: border-box!important;
    }


    .wolk_admin_pages_no_conflict .adm-workarea { background: #f2f5f7; padding-bottom: 30px}

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
	
	.note {
		color: #909090;
		margin-top: 5px;
	}
	.fascia-text {
		color: #606060;
		font-size: 18px;
		font-weight: 600;
	}
	
	.adm-s-order-table-ddi-table tbody tr:first-child td { 
		padding: 0;
	}
</style>

<? require ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>