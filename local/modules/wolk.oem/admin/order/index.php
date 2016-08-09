<?php

use Bitrix\Sale\Helpers\Admin\OrderEdit;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Helpers\Admin\Blocks;
use Bitrix\Highloadblock\HighloadBlockTable;

// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

$permission = $APPLICATION->GetGroupRight('wolk.oem');

if ($permission == 'D') {
	$APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));
}

$groups = $USER->GetUserGroupArray();

$ismanager = false;
if (in_array(GROUP_MANAGERS_ID, $groups)) {
	$ismanager = true;
}



// подключим языковой файл
IncludeModuleLangFile(__FILE__);

$ID = (int) $_REQUEST['ID'];

if ($ID <= 0) {
    LocalRedirect('/bitrix/admin/wolk_oem_order_list.php?lang=' . LANG);
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

if (!\Bitrix\Main\Loader::includeModule('currency')) {
    ShowError('Модуль currency не устанволен.');
    return;
}


if ($ismanager) {
	$eids = array();
	$result = CIBlockElement::GetList([], ['IBLOCK_ID' => EVENTS_IBLOCK_ID, 'ACTIVE' => 'Y', 'PROPERTY_MANAGER' => $USER->getID()], false, false, ['ID']);
	while ($item = $result->Fetch()) {
		$eids []= (int) $item['ID'];
	}
	unset($result, $item);
	
	$result = CSaleOrderPropsValue::GetList([], ['CODE' => 'eventId', '@VALUE' => $eids, 'ORDER_ID' => $ID], false, false, ['ORDER_ID']);
	if ($result->SelectedRowsCount() <= 0) {
		ShowError('Вы не являетесь менеджером данного заказа.');
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
		$colors[$color['UF_XML_ID']] = $color;
	}
}


$message = null;


/*
 * Заказ.
 */ 
$oemorder = new Wolk\OEM\Order($ID);
$bxorder  = Bitrix\Sale\Order::load($ID);


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
			}
			break;
		

        // Сохранение данных заказа.
        case 'data':
			$status = (string) $_POST['STATUS'];
            $bill   = (string) $_POST['BILL'];
			
			$result = true;
			
			if ($status != $oemorder->getStatus()) {
				$result = $bxorder->setField('STATUS_ID', $status)->isSuccess();
				
				if ($result) {
					$result = $bxorder->save()->isSuccess();
				}
				$bxorder = Bitrix\Sale\Order::load($ID);
				
				// $result = CSaleOrder::StatusOrder($oemorder->getID(), $status);
			}
			
            if (!$result) {
				$message = new CAdminMessage([
                    'MESSAGE' => Loc::getMessage('ERROR_CHANGE_DATA'),
                    'TYPE'    => 'ERROR'
                ]);
			} else {
				
				// Заказчик.
				$customer = $oemorder->getUser();
				
				// Шаблон письма.
				$html = $APPLICATION->IncludeComponent('wolk:mail.order', 'status', ['ID' => $oemorder->getID(), 'LANG' => $oemorder->getLanguage()]);
				
				// Отправка письма.
				$event = new \CEvent();
				$event->Send('SALE_NEW_ORDER_STATUS', SITE_DEFAULT, [
					'EMAIL' => $customer['EMAIL'], 
					'HTML' => $html, 
					'THEME' => Loc::getMessage('MESSAGE_THEME_ORDER_STATUS_CHANGE', Loc::loadLanguageFile(__FILE__, $oemorder->getLanguage()), $oemorder->getLanguage())
				]);
            }
			
            if (!empty($bill)) {
                if (!\Wolk\Core\Helpers\SaleOrder::saveProperty($ID, 'BILL', $bill)) {
                    $message = new CAdminMessage([
                        'MESSAGE' => Loc::getMessage('ERROR_CHANGE_DATA'),
                        'TYPE'    => 'ERROR'
                    ]);
                }
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
					$html = $APPLICATION->IncludeComponent('wolk:mail.order', 'invoice', array('ID' => $ID, 'LANG' => $oemorder->getLanguage()));
					
					// Отправка письма.
					$event = new \CEvent();
					$event->Send('SEND_INVOICE', SITE_DEFAULT, [
						'EMAIL' => $email,
						'HTML'  => $html,
						'THEME' => Loc::getMessage('MESSAGE_THEME_INVOICE', Loc::loadLanguageFile(__FILE__, $oemorder->getLanguage()), $oemorder->getLanguage())
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
				$result = \Wolk\Core\Helpers\SaleOrder::saveProperty($ID, 'sketch', $objects);
				if ($result) {
					$result = \Wolk\Core\Helpers\SaleOrder::saveProperty($ID, 'SKETCH_IMAGE', $image);
				}
				if ($result) {
					$order['PROPS'] = Wolk\Core\Helpers\SaleOrder::getProperties($ID);
					
					$file = array(
						'name'    	  => 'sketch-'.$ID.'.jpg',
						'description' => 'Изображение скетча для заказа №'.$ID,
						'content'     => base64_decode($image),
						'old_file'	  => $order['PROPS']['SKETCH_FILE']['VALUE_ORIG']
					);
					$result = \Wolk\Core\Helpers\SaleOrder::saveProperty($ID, 'SKETCH_FILE', CFile::SaveFile($file, 'sketchs'));
				}
				
                if (!$result) {
                    $message = new CAdminMessage([
                        'MESSAGE' => 'При изменнии данных скетча возникла ошибка',
                        'TYPE'    => 'ERROR'
                    ]);
				}
            }
            break;
    }
}




// Заказ.
$order = CSaleOrder::getByID($ID);
$order['PROPS'] = Wolk\Core\Helpers\SaleOrder::getProperties($ID);

// Заказчик.
$customer = CUser::GetByID($order['USER_ID'])->Fetch();

// Мероприятие.
$element = CIBlockElement::getByID($order['PROPS']['eventId']['VALUE'])->GetNextElement();

if ($element) {
	$event = $element->GetFields();
	$event['PROPS'] = $element->GetProperties();
} else {
	ShowError('Мероприятие не найдено.');
    return;
}



$rate = (!empty($order['PROPS']['RATE']['VALUE'])) 
		? ((float) $order['PROPS']['RATE']['VALUE']) 
		: (1);
$rate_currency = (!empty($order['PROPS']['RATE_CURRENCY']['VALUE'])) 
				? ((string) $order['PROPS']['RATE_CURRENCY']['VALUE']) 
				: ($order['CURRENCY']);



$result = CCurrencyRates::GetList();
$rates  = array();
while ($irate = $result->fetch()) {
	$rates[$rate['CURRENCY']] = $irate['RATE'];
}
unset($result, $irate);

$result = CCurrency::GetList();
$currencies = array();
while ($currency = $result->fetch()) {
	if ($order['CURRENCY'] == $currency['CURRENCY']) {
		$currency['ORDER_RATE'] = 1;
	} else {
		$currency['ORDER_RATE'] = round(CCurrencyRates::ConvertCurrency(1, $order['CURRENCY'], $currency['CURRENCY']), 6);
	}
	$currencies[$currency['CURRENCY']] = $currency;
}
unset($result, $currency);



$stand = array();

unset($element);

// Состав заказа.
$baskets = Wolk\Core\Helpers\SaleOrder::getBaskets($ID);


foreach ($baskets as $i => $basket) {
	if ($basket['PRODUCT_ID'] > 0) {
		
		$element = CIBlockElement::getByID($basket['PRODUCT_ID'])->GetNextElement();

		// Элемент удален.
		if (!$element) {
			continue;
		}
		
		$item = $element->getFields();
		$item['PROPS'] = $element->getProperties();
		$item['IMAGE'] = CFile::getPath($item['PREVIEW_PICTURE']);

		$baskets[$i]['ITEM'] = $item;
	
		if ($basket['SET_PARENT_ID'] == 0 && $basket['ITEM']['IBLOCK_ID'] == STANDS_IBLOCK_ID) {
			$stand['BASKET'] = $basket;
			$stand['ITEM']   = $item;
		}
        $baskets[$i]['SURCHARGE_PRICE'] = $basket['PRICE'];
	}
}
unset($item);


// if ($USER->getID() == 1) { echo '<hr/><pre>'; print_r($baskets); echo '</pre>'; }

$surcharge       = (float) $order['PROPS']['SURCHARGE']['VALUE_ORIG'];
$surcharge_price = (float) $order['PROPS']['SURCHARGE_PRICE']['VALUE_ORIG'];

// Добавление наценки на товары.
if ($surcharge > 0) {
    foreach ($baskets as $i => $basket) {
        $baskets[$i]['SURCHARGE_PRICE'] = $basket['SURCHARGE_PRICE'] * (1 + $surcharge / 100);
    }
}

// Статусы заказа.
$statuses = Wolk\Core\Helpers\SaleOrder::getStatuses();

$goodprice = 0;
foreach ($baskets as $basket) {
    $goodprice += $basket['PRICE'] * $basket['QUANTITY'];
}


// Данные для скетча.
$sketch = json_decode($order['PROPS']['sketch']['VALUE_ORIG'], true);

$sketch['items'] = [];
foreach ($baskets as $basket) {
    if ($basket['ITEM']['PROPS']['WIDTH']['VALUE'] && $basket['ITEM']['PROPS']['HEIGHT']['VALUE'] && $basket['ITEM']['PROPS']['SKETCH_IMAGE']['VALUE']) {
        if (array_key_exists($basket['ITEM']['ID'], $sketch['items'])) {
            $sketch['items'][$basket['ITEM']['ID']]['quantity'] += $basket['QUANTITY'];
        } else {
			
			if ($basket['ITEM']['PROPS']['SKETCH_TYPE']['VALUE'] == 'nouse') {
				continue;
			}
			
            $sketch['items'][$basket['ITEM']['ID']] = [
                'id'        => $basket['ITEM']['ID'],
                'imagePath' => CFile::ResizeImageGet($basket['ITEM']['PROPS']['SKETCH_IMAGE']['VALUE'], [
                    'width' => ($basket['ITEM']['PROPS']['WIDTH']['VALUE'] / 10 < 5) ? 5 : $basket['ITEM']['PROPS']['WIDTH']['VALUE'] / 10,
                    'height' => ($basket['ITEM']['PROPS']['HEIGHT']['VALUE'] / 10 < 5) ? 5 : $basket['ITEM']['PROPS']['HEIGHT']['VALUE'] / 10,
                ])['src'],
                'quantity'  => $basket['QUANTITY'],
                'title'     => $basket['ITEM']['NAME'],
                'type'      => $basket['ITEM']['PROPS']['SKETCH_TYPE']['VALUE'] ?: 'droppable',
                'w'         => (float) $basket['ITEM']['PROPS']['WIDTH']['VALUE'] / 1000,
                'h'         => (float) $basket['ITEM']['PROPS']['HEIGHT']['VALUE'] / 1000
            ];
        }
    }
}
unset($basket);



// Печать заказа.
$orderprint = new \Wolk\OEM\OrderPrint($ID);

/*
 * Описываем табы административной панели битрикса.
 */
$aTabs = [
    [
        'DIV'   => 'data',
        'TAB'   => 'Данные заказа',
        'ICON'  => 'data',
        'TITLE' => 'Заказ №'.$ID.' | '.$oemorder->getLanguage()
    ]
];


/*
 * Инициализируем табы
 */
$oTabControl = new CAdmintabControl('tabControl', $aTabs);

CJSCore::Init(['jquery']);

Bitrix\Main\Page\Asset::getInstance()->addJs('/local/templates/.default/javascripts/designer.js');
Bitrix\Main\Page\Asset::getInstance()->addJs('/local/templates/.default/build/js/vendor.js');
Bitrix\Main\Page\Asset::getInstance()->addCss('/assets/css/sketch.css');

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
                        $('#js-invoice-response-id').html('<input type="button" class="amd-btn-save" onclick="javascript: window.open(\'' + response.data['link'] + '?' + (new Date()).getTime() + '\');" target="_blank" value="Скачать счет" />');

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
			
			var gridX = parseInt(<?= (int) ($order['PROPS']['width']['VALUE']) ?: 5 ?>);
			var gridY = parseInt(<?= (int) ($order['PROPS']['depth']['VALUE']) ?: 5 ?>);
			
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
					type: '<?= (!empty($order['PROPS']['standType']['VALUE'])) ? ($order['PROPS']['standType']['VALUE']) : ('row') ?>',
					items: sketchitems,
					placedItems: <?= (!empty($sketch['objects'])) ? (json_encode($sketch['objects'])) : ('{}') ?>
				});
			};
			lime.embed('designer', 0, 0, '', '/');
			
			setTimeout(function() { window.resizeEditor(sketchitems); }, 300);
		}

		loadsketch();
    });

</script>

<style>
	.note {
		color: #909090;
		margin-top: 5px;
	}
	.fascia-text {
		color: #606060;
		font-size: 18px;
		font-weight: 600;
	}
</style>

<? if (!empty($message)) { ?>
    <?= $message->show() ?>
<? } ?>

<div class="adm-detail-toolbar"><span style="position:absolute;"></span>
    <a href="/bitrix/admin/wolk_oem_order_list.php" class="adm-detail-toolbar-btn" title="Перейти к списку заказов" id="btn_list">
        <span class="adm-detail-toolbar-btn-l"></span><span class="adm-detail-toolbar-btn-text">Список заказов</span><span class="adm-detail-toolbar-btn-r"></span>
    </a>
    <div class="adm-detail-toolbar-right" style="top: 0px;">
        <a href="/bitrix/admin/sale_order_edit.php?ID=<?= $ID ?>" class="adm-btn adm-btn-edit" title="Редактировать заказа в Битркисе">Редактировать состав</a>

		<a href="<?= $orderprint->getURL() ?>" target="_blank" class="adm-btn adm-btn-edit">Распечатать заказ</a>
		<a href="/bitrix/admin/wolk_oem_image.php?action=sketch-download&ID=<?= $ID ?>" target="_blank" id="js-sketch-image-download-id" class="adm-btn adm-btn-edit">Распечатать скетч</a>
    </div>
</div>

<div class="adm-bus-orderinfoblock adm-detail-tabs-block-pin" id="sale-order-edit-block-order-info">
    <div class="adm-bus-orderinfoblock-container">
        <div class="adm-bus-orderinfoblock-title">
            Заказ №<?= $order['ID'] ?>
        </div>
        <div class="adm-bus-orderinfoblock-content">
            <div class="adm-bus-orderinfoblock-content-block-customer">
                <ul class="adm-bus-orderinfoblock-content-customer-info">
                    <li>
                        <span class="adm-bus-orderinfoblock-content-customer-info-param">Мероприятие:</span>
						<span class="adm-bus-orderinfoblock-content-customer-info-value">
							<?= $event['NAME'] ?>
						</span>
                    </li>
                    <li>
                        <span class="adm-bus-orderinfoblock-content-customer-info-param">Павильон:</span>
						<span class="adm-bus-orderinfoblock-content-customer-info-value">
							<?= $oemorder->getPavilion() ?>
						</span>
                    </li>
                    <li>
                        <span class="adm-bus-orderinfoblock-content-customer-info-param">Номер стенда:</span>
						<span class="adm-bus-orderinfoblock-content-customer-info-value">
							<?= $oemorder->getStandNumber() ?>
						</span>
                    </li>
                    <li>
                        <span class="adm-bus-orderinfoblock-content-customer-info-param">Дата создания:</span>
						<span class="adm-bus-orderinfoblock-content-customer-info-value">
							<?= date('d.m.Y H:i', strtotime($order['DATE_INSERT'])) ?>
						</span>
                    </li>
                    <li>
                        <span class="adm-bus-orderinfoblock-content-customer-info-param">Статус заказа:</span>
						<span class="adm-bus-orderinfoblock-content-customer-info-value">
							<?= $statuses[$order['STATUS_ID']]['NAME'] ?>
						</span>
                    </li>
                </ul>
            </div>
            <div class="adm-bus-orderinfoblock-content-block-order">
                <ul class="adm-bus-orderinfoblock-content-order-info">
                    <li>
                        <span class="adm-bus-orderinfoblock-content-order-info-param">Стоимость стенда</span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($stand['BASKET']['PRICE'] * $stand['BASKET']['QUANTITY'] * $rate, $rate_currency) ?>
						</span>
                    </li>
                    <li>
                        <span class="adm-bus-orderinfoblock-content-order-info-param">Общая стоимость товаров</span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($goodprice * $rate, $rate_currency) ?>
						</span>
                    </li>
					<li class="adm-bus-orderinfoblock-content-redtext">
                        <span class="adm-bus-orderinfoblock-content-order-info-param">Наценки</span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($order['PROPS']['SURCHARGE_PRICE']['VALUE_ORIG'] * $rate, $rate_currency) ?>
						</span>
                    </li>
					<li>
                        <span class="adm-bus-orderinfoblock-content-order-info-param">НДС</span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($order['TAX_VALUE'] * $rate, $rate_currency) ?>
						</span>
                    </li>
                </ul>
                <ul class="adm-bus-orderinfoblock-content-order-info-result">
                    <li>
                        <span class="adm-bus-orderinfoblock-content-order-info-param">Итого</span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($order['PRICE'] * $rate, $rate_currency) ?>
							<? if (!empty($order['PROPS']['RATE']['VALUE'])) { ?>
								/ <?= CurrencyFormat($order['PRICE'], $order['CURRENCY']) ?>
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
                            <div class="adm-bus-component-title">Данные заказа</div>
                        </div>
                        <div class="adm-bus-component-content-container">
                            <div class="adm-bus-table-container">
                                <form method="post">
                                    <input type="hidden" name="action" value="data"/>
                                    <table class="adm-detail-content-table edit-table" style="width: 60%;">
                                        <tr>
                                            <td class="adm-detail-content-cell-l">Статус заказа:</td>
                                            <td class="adm-detail-content-cell-r">
                                                <select name="STATUS" class="adm-bus-select">
                                                    <? foreach ($statuses as $status) { ?>
                                                        <option
                                                            value="<?= $status['ID'] ?>" <?= ($status['ID'] == $order['STATUS_ID']) ? ('selected') : ('') ?>>
                                                            <?= $status['NAME'] ?>
                                                        </option>
                                                    <? } ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="adm-detail-content-cell-l">Номер счета:</td>
                                            <td class="adm-detail-content-cell-r">
                                                <input type="text" name="BILL" value="<?= $order['PROPS']['BILL']['VALUE'] ?>" size="40" />
                                            </td>
                                        </tr>
										<tr>
                                            <td class="adm-detail-content-cell-l">Комментарий к заказу:</td>
                                            <td class="adm-detail-content-cell-r">
												<div>
													<?= ($order['USER_DESCRIPTION']) ?: ('&mdash;') ?>
												</div>
                                            </td>
                                        </tr>
										<tr>
                                            <td colspan="2" align="left">
                                                <input type="submit" class="amd-btn-save adm-btn-green" value="Сохранить" />
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
											<td class="adm-detail-content-cell-l" width="235">Курс пересчета:</td>
											<td width="200">
												<?= $order['CURRENCY'] ?>
												&rarr;
												<? // Счета // ?>
												<select name="RATE_CURRENCY" id="js-convert-select-id" style="width: 65px;">
													<? foreach ($currencies as $code => $currency) { ?>
														<option value="<?= $code ?>" data-rate="<?= $currency['ORDER_RATE'] ?>" <?= ($code == $order['CURRENCY']) ? ('selected') : ('') ?>>
															<?= $code ?>
														</option>
													<? } ?>
												</select>
												<input type="text" name="RATE" id="js-convert-rate-id" value="1" style="width: 75px;" />
											</td>
											<td>
												<input type="submit" class="amd-btn-save" value="Пересчитать" />
											</td>
											<td>
												<? if (!empty($order['PROPS']['RATE']['VALUE'])) { ?>
													Пересчитан по курсу 
													<b><?= $order['PROPS']['RATE']['VALUE'] ?> <?= $order['PROPS']['RATE_CURRENCY']['VALUE'] ?></b>
												<? } ?>
											</td>
										</tr>
									</table>
								</form>
								
								<hr/>
							
                                <table cellpadding="5">
                                    <tr>
                                        <td class="adm-detail-content-cell-l" width="235">Генерация счета:</td>
                                        <td width="200">
                                            <? // Счета // ?>
                                            <select name="invoice" id="js-invoice-select-id" style="width: 200px;">
                                                <? foreach ($event['PROPS']['INVOICES']['VALUE'] as $index => $invoice) { ?>
                                                    <option value="<?= $event['PROPS']['INVOICES']['VALUE_XML_ID'][$index] ?>">
                                                        <?= $invoice ?>
                                                    </option>
                                                <? } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="button" id="js-invoice-button-id" class="amd-btn-save" value="Получить счет"/>
                                        </td>
                                        <td>
                                            <div id="js-invoice-response-id">
												<? $invoice = $oemorder->getInvoice() ?>
												<? if (!empty($invoice)) { ?>
													<input type="button" class="amd-btn-save" onclick="javascript: window.open('<?= $oemorder->getInvoiceLink() ?>?<?= time() ?>');" target="_blank" value="Скачать счет" />
												<? } ?>
											</div>
                                        </td>
                                    </tr>
								</table>

								<form method="post">
									<input type="hidden" name="action" value="mail" />
									<table cellpadding="5">
										<tr>
											<td class="adm-detail-content-cell-l" width="235">Отправка счета:</td>
											<td>
												<input type="text" name="EMAIL" value="<?= $customer['EMAIL'] ?>" size="23" />
											</td>
											<td>
												<input type="submit" id="js-send-button-id" class="amd-btn-save" value="Отправить счет" />
											</td>
											<td>
												<? if (!empty($order['PROPS']['SENDTIME']['VALUE'])) { ?>
													Отправлено <?= date('H:i d.m.Y', strtotime($order['PROPS']['SENDTIME']['VALUE'])) ?>
												<? } else { ?>
													Счет еще не был отправлен.
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


        <div style="position: relative; vertical-align: top;">
            <div style="height:5px;width:100%"></div>
            <a id="company-order"></a>
            <div class="adm-container-draggable">
                <div class="adm-bus-statusorder">
                    <div class="adm-bus-component-container">
                        <div class="adm-bus-component-title-container">
                            <div class="adm-bus-component-title">Данные компании</div>
                        </div>
                        <div class="adm-bus-component-content-container">
                            <div class="adm-bus-table-container">
                                <form method="post">
                                    <input type="hidden" name="action" value="user" />
                                    <table class="adm-detail-content-table edit-table">
                                        <tr>
                                            <td class="adm-detail-content-cell-l">Компания:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <?= $customer['WORK_COMPANY'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="adm-detail-content-cell-l">E-mail:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <?= $customer['EMAIL'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="adm-detail-content-cell-l">Телефон:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <?= $customer['PERSONAL_PHONE'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="adm-detail-content-cell-l">VAT ID</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <?= $customer['UF_VAT'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="adm-detail-content-cell-l">Контактное лицо:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <?= trim($customer['NAME'] . ' ' . $customer['LAST_NAME']) ?>
                                            </td>
                                        </tr>
										<tr>
                                            <td class="adm-detail-content-cell-l">Рекивизиты:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <textarea cols="30" rows="6" name="REQUISITES"><?= $customer['UF_REQUISITES'] ?></textarea>
                                            </td>
                                        </tr>
										<tr>
                                            <td class="adm-detail-content-cell-l">Номер клиента:</td>
                                            <td class="adm-detail-content-cell-r" style="font-weight: bold;">
                                                <input type="text" name="CLIENT_NUMBER" value="<?= $customer['UF_CLIENT_NUMBER'] ?>" size="28" />
                                            </td>
                                        </tr>
										<tr>
                                            <td colspan="2" align="left">
                                                <input type="submit" class="amd-btn-save adm-btn-green" value="Сохранить" />
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
                            <div class="adm-bus-component-title">Список позиций заказа</div>
                        </div>
                        <div class="adm-bus-component-content-container">
                            <div class="adm-bus-table-container">
                                <table class="adm-s-order-table-ddi-table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <td align="left">Изображение</td>
                                            <td align="left">Название</td>
                                            <td align="left">Количество</td>
                                            <td align="left">Цена в каталоге</td>
                                            <td align="left">Цена</td>
                                            <td align="left">Стоимость</td>
											<td align="left">Дополнительные данные</td>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: left; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(221, 221, 221);">
                                        <? $cnt = 0; ?>
                                        <? foreach ($baskets as $basket) { ?>
                                            <? if ($basket['PRICE'] <= 0) { continue; } ?>
                                            <tr>
                                                <td class="adm-s-order-table-ddi-table-img">
                                                    <? if (!empty($basket['ITEM']['PREVIEW_PICTURE'])) { ?>
                                                        <img src="<?= $basket['ITEM']['IMAGE'] ?>" width="78" height="78" />
                                                    <? } else { ?>
                                                        <div class="no_foto">Нет картинки</div>
                                                    <? } ?>
                                                </td>
                                                <td align="left">
													<? if ($basket['ITEM']['IBLOCK_ID'] == STANDS_IBLOCK_ID) { ?> 
														Стенд &laquo;<?= $basket['NAME'] ?>&raquo;
													<? } else { ?>
														<?= $basket['NAME'] ?>
													<? } ?>
												</td>
                                                <td align="center"><?= $basket['QUANTITY'] ?></td>
                                                <td align="left">
                                                    <?= CurrencyFormat($basket['PRICE'] * $rate, $rate_currency) ?>
                                                </td>
                                                <td align="left">
                                                    <?= CurrencyFormat($basket['SURCHARGE_PRICE'] * $rate, $rate_currency) ?>
                                                </td>
                                                <td align="left">
                                                    <?= CurrencyFormat($basket['SURCHARGE_PRICE'] * $basket['QUANTITY'] * $rate, $rate_currency) ?>
                                                </td>
												<td>
													&nbsp;
												</td>
                                            </tr>
                                            <? $cnt++ ?>
                                        <? } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" align="left" style="height: 50px; margin-left: 15px;">
                                                <b style="margin-left: 30px;">Всего товаров <?= $cnt ?></b>.
                                            </td>
                                            <td colspan="3" align="right">
                                                <h2 style="3px 20px 0 0">Итого: <?= CurrencyFormat(($order['PRICE'] - $order['TAX_VALUE']) * $rate, $rate_currency) ?></h2>
                                            </td>
                                        <tr>
                                        </tr>
                                            <td colspan="4"></td>
                                            <td colspan="3" align="right">
                                                <h2 style="3px 20px 0 0">Итого с НДС: <?= CurrencyFormat($order['PRICE'] * $rate, $rate_currency) ?></h2>
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
                            <div class="adm-bus-component-title">Прикрепленые данные</div>
                        </div>
                        <div class="adm-bus-component-content-container">
                            <div class="adm-bus-table-container">
								<ul>
									
									<? foreach ($baskets as $basket) { ?>
										<? if ($basket['ITEM']['IBLOCK_SECTION_ID'] == SECTION_LOGOTYPES_ID) { ?>
											<li>
												<a href="<?= CFile::getPath($basket['PROPS']['LOGO_FILE']['VALUE']) ?>" target="_blank">Логотип</a>
												<? if (!empty($basket['PROPS']['LOGO_COMMENTS']['VALUE'])) { ?>
													<p class="note"><?= $basket['PROPS']['LOGO_COMMENTS']['VALUE'] ?></p>
												<? } ?>
											</li>
										<? } ?>
										
										<? if ($basket['ITEM']['CODE'] == 'FASCIA_NAME') { ?>
											<li>
												Надпись на фриз (<i><?= $basket['PROPS']['FASCIA_COLOR']['VALUE'] ?> - <?= $colors[$basket['PROPS']['FASCIA_COLOR']['VALUE']]['UF_NUM'] ?></i>):
												<div class="fascia-text"><?= $basket['PROPS']['FASCIA_TEXT']['VALUE'] ?></div>
											</li>
										<? } ?>
										
										<? if ($basket['ITEM']['CODE'] == 'file_upload') { ?>
											<li>
												<a href="<?= CFile::getPath($basket['PROPS']['FILE_ID']['VALUE']) ?>" target="_blank">Логотип</a>
												<? if (!empty($basket['PROPS']['LOGO_COMMENTS']['VALUE'])) { ?>
													<p class="note"><?= $basket['PROPS']['LOGO_COMMENTS']['VALUE'] ?></p>
												<? } ?>
											</li>
										<? } ?>
										
										<? if ($basket['ITEM']['CODE'] == 'FULL_COLOR_PRINTING') { ?>
											<li>
												<a href="<?= $basket['PROPS']['LINK']['VALUE'] ?>" target="_blank">Ссылка на изображение</a>
												<? if (!empty($basket['PROPS']['COMMENTS']['VALUE'])) { ?>
													<p class="note"><?= $basket['PROPS']['COMMENTS']['VALUE'] ?></p>
												<? } ?>
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
            <div style="height:5px;width:100%"></div>
            <a id="company-order"></a>
            <div class="adm-container-draggable">
                <div class="adm-bus-statusorder">
                    <div class="adm-bus-component-container">
                        <div class="adm-bus-component-title-container">
                            <div class="adm-bus-component-title">Заполненные формы</div>
                        </div>
                        <div class="adm-bus-component-content-container">
                            <div class="adm-bus-table-container">
								<? foreach ($baskets as $basket) { ?>
									<? if ($basket['ITEM']['CODE'] == 'form') { ?>
										<div class="filled-form">
											<table>
												<? foreach ($basket['PROPS'] as $prop) { ?>
													<tr>
														<td><?= $prop['NAME'] ?>:</td>
														<td>
															<b><?= $prop['VALUE'] ?></b>
														</td>
													</tr>
												<? } ?>
											</table>
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
            <style scoped>
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
            </style>
			
			<? if (!$oemorder->isIndividual()) { ?>
				<div style="height: 5px; width: 100%"></div>
				<a id="sketch-order"></a>
				<div class="adm-container-draggable">
					<div class="adm-bus-statusorder">
						<div class="adm-bus-component-container">
							<div class="adm-bus-component-title-container">
								<div class="adm-bus-component-title">Скетч</div>
							</div>
							<div class="adm-bus-component-content-container">
								<div class="adm-bus-table-container">
								
									<? // Контейнер для скетча. // ?>
									<div id="designer" style="margin-top:40px; width: 940px; height:680px" onmouseout="ru.octasoft.oem.designer.Main.stopDragging()"></div>
									
									<form method="post">
										<input type="hidden" name="action" value="sketch" />
										<input type="hidden" name="OBJECTS" value="" id="js-sketch-scene-input-id" />
										<input type="hidden" name="IMAGE" value="" id="js-sketch-image-input-id" />
										<input type="button" id="js-sketch-button-id" class="amd-btn-save adm-btn-green" value="Сохранить" />
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
