<?php

use Bitrix\Sale\Helpers\Admin\OrderEdit;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Helpers\Admin\Blocks;

// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

// подключим языковой файл
IncludeModuleLangFile(__FILE__);

$ID = (int)$_REQUEST['ID'];

if ($ID <= 0) {
    LocalRedirect('/bitrix/admin/linemedia.carsale_operator.php?lang=' . LANG);
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


$message = null;


$oemorder = new Wolk\OEM\Order($ID);


/*
 * Сохранение данных заказа.
 */
if (!empty($_POST)) {
    $action = (string)$_POST['action'];

    switch ($action) {
        // Сохранение данных заказа.
        case 'data':
            $status = (string)$_POST['STATUS'];
            $bill = (string)$_POST['BILL'];


            if (!\CSaleOrder::StatusOrder($ID, $status)) {
                $message = new CAdminMessage([
                    'MESSAGE' => 'При изменнии данных заказа возникла ошибка',
                    'TYPE'    => 'ERROR'
                ]);
            }

            if (!empty($bill)) {
                if (!\Wolk\Core\Helpers\SaleOrder::saveProperty($ID, 'BILL', $bill)) {
                    $message = new CAdminMessage([
                        'MESSAGE' => 'При изменнии данных заказа возникла ошибка',
                        'TYPE'    => 'ERROR'
                    ]);
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
			
			if (!empty($objects)) {
                if (!\Wolk\Core\Helpers\SaleOrder::saveProperty($ID, 'sketch', $objects)) {
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

$bxorder = Bitrix\Sale\Order::load($ID);

// Заказчик.
$customer = CUser::GetByID($order['USER_ID'])->Fetch();

// Мероприятие.
$event = CIBlockElement::getByID($order['PROPS']['eventId']['VALUE'])->Fetch();

// Состав заказа.
$baskets = Wolk\Core\Helpers\SaleOrder::getBaskets($ID);

foreach ($baskets as &$basket) {
    $element = CIBlockElement::getByID($basket['PRODUCT_ID'])->GetNextElement();

    $item = $element->getFields();
    $item['PROPS'] = $element->getProperties();
    $item['IMAGE'] = CFile::getPath($item['PREVIEW_PICTURE']);

    $basket['ITEM'] = $item;
}
// break reference to the last element
unset($basket);

// Статусы заказа.
$statuses = Wolk\Core\Helpers\SaleOrder::getStatuses();

/*  
echo '<pre>';
print_r($oemorder->getData());
echo '</pre>';
*/

$goodprice = 0;
foreach ($baskets as $basket) {
    $goodprice += $basket['PRICE'] * $basket['QUANTITY'];
}

// Данные для скетча.
$sketch = json_decode($order['PROPS']['sketch']['VALUE'], true);

$sketch['items'] = [];
foreach ($baskets as $basket) {
    if ($basket['ITEM']['PROPS']['WIDTH']['VALUE'] && $basket['ITEM']['PROPS']['HEIGHT']['VALUE']) {
        if(array_key_exists($basket['ITEM']['ID'], $sketch['items'])) {
            $sketch['items'] [$basket['ITEM']['ID']]['quantity'] += $basket['QUANTITY'];
        } else {
            $sketch['items'] [$basket['ITEM']['ID']] = [
                'id'        => $basket['ITEM']['ID'],
                'imagePath' => CFile::ResizeImageGet($basket['ITEM']['PROPS']['SKETCH_IMAGE']['VALUE'], [
                    'width' => ($basket['ITEM']['PROPS']['WIDTH']['VALUE'] / 10 < 30) ? 30 : $basket['ITEM']['PROPS']['WIDTH']['VALUE'] / 10,
                    'height' => ($basket['ITEM']['PROPS']['HEIGHT']['VALUE'] / 10 < 30) ? 30 : $basket['ITEM']['PROPS']['HEIGHT']['VALUE'] / 10,
                ])['src'],
                'quantity'  => $basket['QUANTITY'],
                'title'     => $basket['ITEM']['NAME'],
                'type'      => $basket['ITEM']['PROPS']['SKETCH_TYPE']['VALUE'] ?: 'droppable',
                'w'         => (float)$basket['ITEM']['PROPS']['WIDTH']['VALUE'] / 1000,
                'h'         => (float)$basket['ITEM']['PROPS']['HEIGHT']['VALUE'] / 1000
            ];
        }

    }
}

/*
 * Описываем табы административной панели битрикса.
 */
$aTabs = [
    [
        'DIV'   => 'data',
        'TAB'   => 'Данные заказа',
        'ICON'  => 'data',
        'TITLE' => 'Заказ №' . $ID
    ]
];

/*
 * Инициализируем табы
 */
$oTabControl = new CAdmintabControl('tabControl', $aTabs);

CJSCore::Init(['jquery']);

Bitrix\Main\Page\Asset::getInstance()->addJs('/local/templates/.default/javascripts/designer.js');

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
/*
h: 0.5
id: "93"
imagePath: "/upload/resize_cache/iblock/ad3/90_75_1/_i_110.jpg"
quantity: "1"
title: "Шкаф архивный Н=110"
type: "droppable"
w: 1
*/
?>

<? // Скетч // ?>
<script type="text/javascript">

    $(document).ready(function () {

        var itemsForSketch = <?= json_encode(array_values($sketch['items'])) ?>;
		
		// Генерация счета.
        $('#js-invoice-button-id').on('click', function () {
            $('#js-invoice-response-id').html('');
            $.ajax({
                url: '/bitrix/admin/wolk_oem_remote.php',
                data: {'action': 'invoice-print', 'oid': <?= $ID ?>, 'tpl': $('#js-invoice-select-id').val()},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        $('#js-invoice-response-id').html('<a href="' + response.data['link'] + '?' + (new Date()).getTime() + '" target="_blank">Скачать счет</a>');
                    } else {
                        alert(response.message);
                    }
                }
            });
        });
		
		// Сохранение скетча.
		$('#js-sketch-button-id').on('click', function() {
			$('#js-sketch-input-id').val(JSON.stringify(ru.octasoft.oem.designer.Main.getScene()));
			$(this).closest('form').trigger('submit');
		});


        /*
         * Обработчики скетча.
         */
        $('head').append('<script src="/local/templates/.default/javascripts/designer.js"><\/script>');

        window.addEventListener("touchmove", function (event) {
            event.preventDefault();
        }, false);

        if (typeof window.devicePixelRatio != 'undefined' && window.devicePixelRatio > 2) {
            var meta = document.getElementById("viewport");
            meta.setAttribute('content', 'width=device-width, initial-scale=' + (2 / window.devicePixelRatio) + ', user-scalable=no');
        }

        (window.resizeEditor = function (items) {
            var editorH = 400 + (items.length * 135);
            $("#designer").height(editorH);

            var firstRun = !window.editorScrollTop;
            window.editorScrollTop = $("#designer").offset().top - 30;
            window.editorScrollBottom = window.editorScrollTop - 30 + editorH - $(window).height();

            if (window.editorScrollBottom < window.editorScrollTop) window.editorScrollTop = window.editorScrollBottom;
            if (!firstRun) {
                ru.octasoft.oem.designer.Main.scroll(window.editorScrollTop, window.editorScrollBottom, $(this).scrollTop());
                // trigger resize event to update layout with new height
                if (Event.prototype.initEvent) {
                    // for IE
                    var evt = window.document.createEvent('UIEvents');
                    evt.initUIEvent('resize', true, false, window, 0);
                    window.dispatchEvent(evt);
                } else {
                    window.dispatchEvent(new Event('resize'));
                }
            }
        })(itemsForSketch);

        window.onEditorReady = function () {
            $(window).bind("scroll", function (e) {
                ru.octasoft.oem.designer.Main.scroll(window.editorScrollTop, window.editorScrollBottom, $(this).scrollTop());
            });
            ru.octasoft.oem.designer.Main.init({
                w: <?= ($order['PROPS']['width']['VALUE']) ?: 5 ?>,
                h: <?= ($order['PROPS']['depth']['VALUE']) ?: 5 ?>,
                // row corner head island
                type: '<?= $order['PROPS']['standType']['VALUE'] ?>',
                items: itemsForSketch,
                placedItems: <?= (!empty($sketch['objects'])) ? (json_encode($sketch['objects'])) : ('null') ?>
            });
        };
        lime.embed("designer", 0, 0);
    });

</script>


<? if (!empty($message)) { ?>
    <?= $message->show() ?>
<? } ?>

<div class="adm-detail-toolbar"><span style="position:absolute;"></span>
    <a href="/bitrix/admin/sale_order.php?lang=ru&amp;filter=Y&amp;set_filter=Y" class="adm-detail-toolbar-btn"
       title="Перейти к списку заказов" id="btn_list">
        <span class="adm-detail-toolbar-btn-l"></span><span
            class="adm-detail-toolbar-btn-text">Список заказов</span><span class="adm-detail-toolbar-btn-r"></span>
    </a>
    <div class="adm-detail-toolbar-right" style="top: 0px;">
        <a href="/bitrix/admin/sale_order_edit.php?ID=<?= $ID ?>" class="adm-btn adm-btn-edit"
           title="Редактировать заказа в Битркисе">Редактировать состав</a>
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
                        <span class="adm-bus-orderinfoblock-content-order-info-param">Общая стоимость товаров</span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($goodprice, $order['CURRENCY']) ?>
						</span>
                    </li>
                    <li class="adm-bus-orderinfoblock-content-redtext">
                        <span class="adm-bus-orderinfoblock-content-order-info-param">Стоимость с учётом скидок и наценок</span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($order['PRICE'], $order['CURRENCY']) ?>
						</span>
                    </li>
                </ul>
                <ul class="adm-bus-orderinfoblock-content-order-info-result">
                    <li>
                        <span class="adm-bus-orderinfoblock-content-order-info-param">Итого</span>
						<span class="adm-bus-orderinfoblock-content-order-info-value">
							<?= CurrencyFormat($order['PRICE'], $order['CURRENCY']) ?>
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
                            <div class="adm-bus-table-container">
                                <table cellpadding="10">
                                    <tr>
                                        <td class="adm-detail-content-cell-l">Шаблон счета:</td>
                                        <td>
                                            <? // Счета.
                                            $invoices = [
                                                'uaz'      => 'Uaz',
                                                'malcorp'  => 'MALCORP',
                                                'distance' => 'ДИСТАНЦИЯ',
                                            ];
                                            ?>
                                            <select name="invoice" id="js-invoice-select-id" style="width: 200px;">
                                                <? foreach ($invoices as $tpl => $invoice) { ?>
                                                    <option value="<?= $tpl ?>">
                                                        <?= $invoice ?>
                                                    </option>
                                                <? } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="button" id="js-invoice-button-id" class="amd-btn-save" value="Получить счет"/>
                                        </td>
                                        <td>
                                            <div id="js-invoice-response-id"></div>
                                        </td>
                                    </tr>
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
                                <p>
                                    В списке состава заказанаходится стенд, а также входящее в него оборудование с
                                    нулевой стоимостью.
                                </p>
                                <table class="adm-s-order-table-ddi-table" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <td align="left">Изображение</td>
                                        <td align="left">Название</td>
                                        <td align="left">Количество</td>
										<td align="left">Цена</td>
                                        <td align="left">Стоимость</td>
                                    </tr>
                                    </thead>
                                    <tbody
                                        style="text-align: left; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(221, 221, 221);">
                                    <? foreach ($baskets as $basket) { ?>
                                        <tr>
                                            <td class="adm-s-order-table-ddi-table-img">
                                                <? if (!empty($basket['ITEM']['PREVIEW_PICTURE'])) { ?>
                                                    <img src="<?= $basket['ITEM']['IMAGE'] ?>" width="78" height="78"/>
                                                <? } else { ?>
                                                    <div class="no_foto">Нет картинки</div>
                                                <? } ?>
                                            </td>
                                            <td align="left"><?= $basket['NAME'] ?></td>
                                            <td align="center"><?= $basket['QUANTITY'] ?></td>
                                            <td align="left">
												<?= CurrencyFormat($basket['PRICE'], $basket['CURRENCY']) ?>
											</td>
											<td align="left">
												<?= CurrencyFormat($basket['SUMMARY_PRICE'], $basket['CURRENCY']) ?>
											</td>
                                        </tr>
                                    <? } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="4" align="left"
                                            style="height: 50px; border-top: 2px solid #; margin-left: 15px;">
                                            <b>Всего товаров <?= count($baskets) ?></b>.
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
                                <div id="designer" style="margin-top:40px; width: 940px; height:680px" onmouseout="ru.octasoft.oem.designer.Main.stopDragging()" ></div>
                                <form method="post">
                                    <input type="hidden" name="action" value="sketch" />
									<input type="hidden" name="OBJECTS" value="" id="js-sketch-input-id" />
                                    <input type="button" id="js-sketch-button-id" class="amd-btn-save adm-btn-green" value="Сохранить" />
									<? // ru.octasoft.oem.designer.Main.getScene() // ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>

<? $oTabControl->EndTab() ?>
<? $oTabControl->End() ?>
