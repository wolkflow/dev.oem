﻿<?php

use Bitrix\Sale\Helpers\Admin\OrderEdit;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Helpers\Admin\Blocks;
use Bitrix\Highloadblock\HighloadBlockTable;

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

// подключим языковой файл
IncludeModuleLangFile(__FILE__);


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


// Подключение модуля.
Bitrix\Main\Loader::includeModule('wolk.oem');


// Сообщение.
$message = null;


// Сохранение заказа.
if (!empty($_POST)) {
    
}


// Список выставок.
$events = Wolk\OEM\Event::getList(['filter' => ['ACTIVE' => 'Y'], 'order' => ['NAME' => 'ASC']]);

// Счета.
$invoices = [];
foreach ($events as $event) {
    $event_invoices = $event->getInvoices('VALUE');
    $event_invoices_titles = $event->getInvoices('VALUE_XML_ID');
    foreach ($event_invoices as $index => $invoice) {
        $invoices[$event->getID()][$event_invoices_titles[$index]] = $invoice;
    }
}


// Список валют.
$result = CCurrency::GetList();
$currencies = array();
while ($currency = $result->fetch()) {
	$currencies[$currency['CURRENCY']] = $currency;
}
unset($result, $currency);


// Список языков.
$result = CLanguage::GetList();
$languages = array();
while ($language = $result->fetch()) {
	$languages[$language['LID']] = $language;
}
unset($result, $language);



//Bitrix\Main\Page\Asset::getInstance()->addJs('/local/templates/.default/javascripts/designer.js');
//Bitrix\Main\Page\Asset::getInstance()->addJs('/local/templates/.default/build/js/vendor.js');
//Bitrix\Main\Page\Asset::getInstance()->addCss('/assets/css/sketch.css');

//Bitrix\Main\Page\Asset::getInstance()->addCss('/assets/bootstrap/css/bootstrap.min.css');
$APPLICATION->SetAdditionalCSS('/assets/bootstrap/css/bootstrap.min.css');
// Bitrix\Main\Page\Asset::getInstance()->addCss('/assets/bootstrap/css/bootstrap.min.css');
Bitrix\Main\Page\Asset::getInstance()->addJs('https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js');
Bitrix\Main\Page\Asset::getInstance()->addJs('/assets/bootstrap/js/bootstrap.min.js');

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

?>


<? if (!empty($message)) { ?>
    <?= $message->show() ?>
<? } ?>

<style type="text/css">
    #form-user-select-id {
        cursor: pointer;
    }
</style>

<script>
    var invoices = <?= json_encode($invoices) ?>;
    
    $(document).ready(function() {
        // bootstrap
        $('html').addClass('wolk_admin_pages_no_conflict');
        
        // Выбор выставки.
        $('#form-event-id').on('change', function() {
            var $forminvoice  = $('#form-invoice-id');
            var eventinvoices = invoices[$(this).val()];
            
            $forminvoice.html('');
            for (var code in eventinvoices) {
                $forminvoice.append('<option value="' + code + '">' + eventinvoices[code] + '</option>');
            }
        });
        
        // Поиск участника выставки.
        $('#form-user-id').on('keyup', function() {  
            var query   = $(this).val();
            var $select = $('#form-user-select-id');
            
            $.ajax({
                url: '/bitrix/admin/wolk_oem_remote.php',
                type: 'post',
                data: {'action': 'select-user', 'query': query},
                dataType: 'json',
				beforeSend: function() {
                    $select.addClass('hidden');
                    
                    BX.closeWait('.js-invoices-wrapper');
					BX.showWait('.js-invoices-wrapper');
				},
                success: function (response) {
                    if (response.status) {
                        $select.find('option').remove();
                        
                        for (var i in response.data['users']) {
                            var user = response.data['users'][i];
                            $select.append('<option value="' + user.ID + '">' + user.FULLNAME + '</option>');
                        }
                        $select.removeClass('hidden');
                    }
                    BX.closeWait('.js-invoices-wrapper');
                }
            });
        });
        
        
        // Выбор участника выставки.
        $('#form-user-select-id').on('click', 'option', function() {
            var $that = $(this);
            
            $('#form-user-id').val($that.text());
            $('#furm-user-value-id').val($that.val());
            
            $('#form-user-select-id').addClass('hidden').html('').append($that);
        });
        
        
        // Добавление товарной позиции.
        $('#js-form-insert-position-id').on('click', function() {
            var event    = $('#form-event-id').val();
            var language = $('#form-language-id').val();
            var currency = $('#form-currency-id').val();
            
            jsUtils.OpenWindow('/bitrix/admin/wolk_oem_order_element_search.php?lang=ru&IBLOCK_ID=<?= EQUIPMENT_IBLOCK_ID ?>&func=SetElement&event=' + event + '&language=' + language + '&currency=' + currency, 900, 700);
        });
        
        
        // Изменене количества позиций.
        $('#js-positions-id').on('keyup', '.js-quantity', function() {
            var $that = $(this);
            var position = $that.closest('.js-position');
            var quantity = parseFloat($that.val());
            var price    = parseFloat(position.find('.js-price').text());
            
            if (quantity <= 0) {
                quantity = 1;
            }
            position.find('.js-cost').val((price * quantity).toFixed(2));
            
            // Пересчет цены.
            CalcPrices();
        });
        
        // Удаление товарной позиции.
        $('#js-positions-id').on('click', '.js-remove', function() {
            $(this).closest('tr').remove();
            
            // Пересчет цены.
            CalcPrices();
        });
        
        // Пересчет цен: изменение наценка.
        $('#form-surcharge-id').on('keyup', function() {
            CalcPrices();
        });
        
        // Пересчет цен: изменение включения НДС.
        $('#form-vat-id').on('change', function() {
            CalcPrices();
        });
        
        // Пересчет цен: изменение стоимости товарной позиции.
        $('#js-positions-id').on('keyup', '.js-cost', function() {
            CalcPrices();
        });
    });
    
    
    function CalcPrices()
    {
        var vat   = 0;
        var summ  = 0;
        var total = 0;
        var surcharge  = parseFloat($('#form-surcharge-id').val());
        var includevat = $('#form-vat-id').is(':checked');
        
        $('#js-positions-id tbody .js-cost').each(function() {
            summ += parseFloat($(this).val());
        });
        
        // Наценка.
        if (surcharge > 0) {
            summ = summ * (1 + surcharge / 100);
        }
        
        // Включение НДС в стоимость.
        if (includevat) {
            vat   = summ * <?= UNVAT_DEFAULT ?>;
            total = summ;
        } else {
            vat   = summ * <?= VAT_DEFAULT ?> / 100;
            total = summ + vat;
        }
        
        $('#js-order-price-summ').html(summ.toFixed(2));
        $('#js-order-price-vat').html(vat.toFixed(2));
        $('#js-order-price-total').html(total.toFixed(2));
    }
    
    
    // Сохранение элемента.
    function SetElement(element)
    {
        var html;
        
        element = JSON.parse(element);
        
        html = '<tr id="position-' + element.ID + '" class="js-position row-position">';
        if (element.PICTURE) {
            html += '<td><img src="' + element.PICTURE + '" class="img-thumbnail position-image-preview" /></td>';
        } else {
            html += '<td><div class="no_foto">Нет картинки</div></td>';
        }
        html += '<td>' + element.NAME + '</td>';
        html += '<td><input type="text" class="js-quantity form-control input-text-small" value="1" /></td>';
        html += '<td><span class="js-price">' + (element.PRICE).toFixed(2) + '</span></td>';
        html += '<td><input type="text" class="js-cost form-control input-text-small" value="' + (element.PRICE).toFixed(2) + '" /></td>';
        html += '<td><input type="text" class="js-comment form-control" value="" /></td>';
        html += '<td><a class="btn btn-danger js-remove" title="Удалить позицию"><span class="glyphicon glyphicon-remove"></span></a></td>';
        html += '</tr>';
        
        $('#js-positions-id tbody').append(html);
        
        // Пересчет цены.
        CalcPrices();
    }
    
</script>

<div class="row" id="js-order-form-od">
    <div class="col-md-10">
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Создание заказа</h3>
            </div>
            <div class="panel-body">
                <form method="POST">
                    <div class="form-group">
                        <label class="control-label" for="form-event-id">Выставка:</label>
                        <select class="form-control" id="form-event-id" name="EVENT">
                            <option class="option-no-select"> - выберите - </option>
                            <? foreach ($events as $event) { ?>
                                <option value="<?= $event->getID() ?>">
                                    <?= $event->get('NAME') ?>
                                </option>
                            <? } ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label" for="form-user-id">Участник:</label>
                                <input type="hidden" name="USER" id="furm-user-value-id" />
                                
                                <div class="input-group">
                                    <input type="text" class="form-control" id="form-user-id" placeholder="Введите и выберите название компании или ФИО" />
                                    <div class="input-group-btn">
                                        <a class="btn btn-primary" href="/bitrix/admin/user_edit.php" target="_blank">Добавить участника</a>
                                    </div>
                                </div>
                                <select multiple class="form-control hidden" id="form-user-select-id" style="min-height: 200px; margin-top: 2px; z-index: 1000;"></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="form-standnum-id">Номер стенда:</label>
                                <input type="text" class="form-control" id="form-standnum-id" name="STANDNUM" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="form-pavillion-id">Номер павильона:</label>
                                <input type="text" class="form-control" id="form-pavillion-id" name="PAVILLION" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="radio">
                            <label>
                                <input type="radio" name="TYPE" id="form-type-common-id" value="COMMON" checked />
                                Обычный заказ
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="TYPE" id="form-type-quick-id" value="QUICK" />
                                Быстрый заказ
                            </label>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="form-currency-id">Валюта заказа:</label>
                                <select class="form-control" id="form-currency-id" name="CURRENCY">
                                    <? foreach ($currencies as $currency) { ?>
                                        <option value="<?= $currency['CURRENCY'] ?>">
                                            <?= $currency['FULL_NAME'] ?>
                                        </option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="form-language-id">Язык заказа:</label>
                                <select class="form-control" id="form-language-id" name="LANGUAGE">
                                    <? foreach ($languages as $language) { ?>
                                        <option value="<?= $language['LID'] ?>">
                                            <?= $language['NAME'] ?>
                                        </option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr/>
                    
                    <div class="row">
                        <div class="col-md-11">
                            <h3>Товарные позиции</h3>
                            <table class="table table-bordered" id="js-positions-id">
                                <thead>
                                    <tr>
                                        <th>Изображение</th>
                                        <th>Название</th>
                                        <th>Количество</th>
                                        <th>Цена</th>
                                        <th>Сумма</th>
                                        <th>Комментарий</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <button type="button" class="btn btn-primary" id="js-form-insert-position-id">
                                Добавить позицию
                            </button>
                        </div>
                    </div>
                    
                    <br/><hr/>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="form-surcharge-id">Наценка:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="form-surcharge-id" name="SURCHARGE" />
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="form-vat-id" name="VAT" value="1" />
                                        Включен НДС
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Сумма заказа:</label>
                            <table class="table table-bordered table-condensed table-prices">
                                <tr>
                                    <td>Итого:</td>
                                    <td id="js-order-price-summ"></td>
                                </tr>
                                <tr>
                                    <td>НДС:</td>
                                    <td id="js-order-price-vat"></td>
                                </tr>
                                <tr class="info">
                                    <td>Итого с НДС:</td>
                                    <td id="js-order-price-total"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr/>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="form-invoice-id">Счет:</label>
                            <div class="input-group">
                                <select class="form-control" id="form-invoice-id" name="INVOICE"></select>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default">
                                        <span class="glyphicon glyphicon-list-alt"></span>
                                        Сгенерировать счет
                                    </button>
                                    <button type="button" class="btn btn-default" disabled>
                                        <span class="glyphicon glyphicon-download-alt"></span>
                                        Скачать счет
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group">
                            <input class="adm-btn-save" type="submit" value="Создать" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .linemedia_carsale_dealer_list *,
    .linemedia_carsale_auction_admin *,
    .wolk_admin_pages_no_conflict *{
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


    .linemedia_carsale_auction_admin .adm-workarea { background: #f2f5f7; padding-bottom: 30px}

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
    
    .position-image-preview {
        max-height: 50px;
    }
    
    .table tr.row-position td, .table tr.row-position th {
        min-height: 120px;
        text-align: center;
        vertical-align: middle;
    }
    
    .input-text-small {
        width: 120px !important;
    }
    
    .option-no-select {
        color: #777777;
    }
    
    .table-prices td {
        font-weight: 600;
    }
    .table-prices td:nth-child(1) {
        width: 30%;
    }
</style>


<? require ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>












