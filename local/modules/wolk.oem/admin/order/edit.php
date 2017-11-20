<?php

use Bitrix\Sale\Helpers\Admin\OrderEdit;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Helpers\Admin\Blocks;
use Bitrix\Highloadblock\HighloadBlockTable;

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

// Не пересчитывать заказ при создании из формы.
define('NO_ORDER_RECALC', 'Y');


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


$oid = (int) $_REQUEST['ID'];

$oemorder = null;
if (!empty($oid)) {
    $oemorder = new Wolk\OEM\Order($oid);
}

// Подключение модуля.
Bitrix\Main\Loader::includeModule('wolk.oem');


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
$result = CCurrency::GetList(($b = 'ID'), ($o = 'ASC'));
$currencies = array();
while ($currency = $result->fetch()) {
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


// Данные существующего заказа.
$odata = array();

if (!empty($oemorder)) {
    $odata = $oemorder->getFullData();
    
    // В заказе есть стенд.
    foreach ($odata['BASKETS'] as $bitem) {
        if ($bitem['PROPS']['STAND']['VALUE'] == 'Y') {
            $odata['STAND'] = $bitem;
            break;
        }
    }
}


$errors = [];


// Создание или изменение заказа.
if (!empty($_POST) && $_POST['action'] == 'order-make') {
    
    $fields = array(
        'OID'        => (int) $oid,
        'UID'        => (int) $_POST['USER'],
        'EID'        => (int) $_POST['EVENT'],
        'SID'        => (int) $_POST['STAND'],
        'STANDPRICE' => $_POST['STANDPRICE'],
        'STANDWIDTH' => $_POST['STANDWIDTH'],
        'STANDDEPTH' => $_POST['STANDDEPTH'],
        'PRODUCTS'   => $_POST['PRODUCTS'],
        'TYPESTAND'  => (string) $_POST['TYPESTAND'],
        'CURRENCY'   => (string) $_POST['CURRENCY'],
        'LANGUAGE'   => (string) $_POST['LANGUAGE'],
        'TYPE'       => (string) $_POST['TYPE'],
        'STANDNUM'   => (string) $_POST['STANDNUM'],
        'PAVILION'   => (string) $_POST['PAVILION'],
        'SURCHARGE'  => $_POST['SURCHARGE'],
        'COMMENTS'   => $_POST['COMMENTS'],
    );
	
	if (empty($fields['EID'])) {
		$errors['EVENT'] = Loc::getMessage('ERROR_NOT_SPECIFIED_EVENT');// 'Не укаазна выставка';
	}
	if (empty($fields['UID'])) {
		$errors['USER'] = Loc::getMessage('ERROR_NOT_SPECIFIED_USER');//'Не укаазн участник';
	}
	if (empty($fields['CURRENCY'])) {
		$errors['CURRENCY'] = Loc::getMessage('ERROR_NOT_SPECIFIED_CURRENCY');//'Не укаазна валюта';
	}
	if (empty($fields['LANGUAGE'])) {
		$errors['LANGUAGE'] = Loc::getMessage('ERROR_NOT_SPECIFIED_LANGUAGE');//'Не укаазн язык';
	}
	if ($fields['TYPE'] != 'QUICK' && $fields['TYPESTAND'] != 'INDIVIDUAL') {
		if (empty($fields['SID'])) {
			$errors['STAND'] = Loc::getMessage('ERROR_NOT_SPECIFIED_STAND');//'Не выбран стенд';
		}
	}
	
	if (empty($errors)) {
		
		// Загруженные файлы.
		$files = Wolk\Core\Utils\File::getReStructUploadFiles();
		
		foreach ($fields['PRODUCTS'] as $i => &$product) {
			$file = $files['PRODUCTS'][$i]['PROPS']['FILE'];
			if (!empty($file['tmp_name'])) {
				$product['PROPS']['FILE'] = CFile::SaveFile($file, 'temp');
			}
		}
		
		$order = Wolk\OEM\Order::make($fields);
		
		LocalRedirect('/bitrix/admin/wolk_oem_order_edit.php?ID=' . $order->getID());
	}
}



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
                <div class="panel-heading">
                    <? if (!empty($oid)) { ?>
                        <h3>
							<?= Loc::getMessage('HEADER_ORDER_CHANGING') ?>
							<b><a href="/bitrix/admin/wolk_oem_order_index.php?ID=<?= $oid ?>"><?= $oid ?></a></b>
						</h3>
                    <? } else { ?>
                        <h3><?= Loc::getMessage('HEADER_ORDER_CREATING') ?></h3>
                    <? } ?>
                </div>
                <div class="panel-body">
                    <form method="POST" id="js-order-make-form-id" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="order-make" />
                        <input type="hidden" name="ID" value="<?= $oid ?>" />
                        <div class="form-group">
                            <label class="control-label" for="form-event-id">
								<?= Loc::getMessage('EVENT') ?>:
							</label>
                            <select class="form-control" id="form-event-id" name="EVENT">
                                <option class="option-no-select"> - выберите - </option>
                                <? foreach ($events as $event) { ?>
                                    <option value="<?= $event->getID() ?>" <?= ($odata['EVENT']['ID'] == $event->getID()) ? ('selected') : ('') ?>>
                                        <?= $event->get('NAME') ?>
                                    </option>
                                <? } ?>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="js-form-user-id">
										<?= Loc::getMessage('FIELD_USER') ?>:
									</label>
                                    <input type="hidden" name="USER" id="furm-user-value-id" value="<?= $odata['USER']['ID'] ?>" />
                                    
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="js-form-user-id" placeholder="<?= Loc::getMessage('FIELD_COMPANY_HOLDER') ?>" value="<?= $odata['USER']['WORK_COMPANY'] ?>" />
                                        <div class="input-group-btn">
                                            <a class="btn btn-primary" href="/bitrix/admin/user_edit.php" target="_blank"><?= Loc::getMessage('FIELD_USER_ADD_LINK') ?></a>
                                        </div>
                                    </div>
                                    <select multiple class="form-control hidden" id="js-form-user-select-id" style="min-height: 200px; margin-top: 2px; z-index: 1000; position: absolute;"></select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label" for="form-standnum-id"><?= Loc::getMessage('FIELD_STANDNUM') ?>:</label>
                                    <input type="text" class="form-control" id="form-standnum-id" name="STANDNUM" value="<?= $odata['ORDER']['PROPS']['STANDNUM']['VALUE'] ?>" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label" for="form-pavillion-id"><?= Loc::getMessage('FIELD_PAVILION') ?>:</label>
                                    <input type="text" class="form-control" id="form-pavillion-id" name="PAVILION" value="<?= $odata['ORDER']['PROPS']['PAVILION']['VALUE'] ?>" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <input class="js-form-type-order" type="radio" name="TYPE" id="form-type-common-id" value="COMMON" <?= ($odata['ORDER']['PROPS']['TYPE']['VALUE'] == 'COMMON' || empty($odata)) ? ('checked="checked"') : ('') ?> />
                                    <?= Loc::getMessage('FIELD_ORDER_COMMON') ?>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input class="js-form-type-order" type="radio" name="TYPE" id="form-type-quick-id" value="QUICK" <?= ($odata['ORDER']['PROPS']['TYPE']['VALUE'] == 'QUICK' || empty($odata)) ? ('checked="checked"') : ('') ?> />
                                    <?= Loc::getMessage('FIELD_ORDER_QUICK') ?>
                                </label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="form-currency-id">
										<?= Loc::getMessage('FIELD_CURRENCY') ?>:
									</label>
                                    <select class="form-control" id="form-currency-id" name="CURRENCY">
                                        <? foreach ($currencies as $currency) { ?>
                                            <option value="<?= $currency['CURRENCY'] ?>" <?= ($odata['ORDER']['CURRENCY'] == $currency['CURRENCY']) ? ('selected') : ('') ?>>
                                                <?= $currency['FULL_NAME'] ?>
                                            </option>
                                        <? } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="form-language-id">
										<?= Loc::getMessage('FIELD_LANGUAGE') ?>:
									</label>
                                    <select class="form-control" id="form-language-id" name="LANGUAGE">
                                        <? foreach ($languages as $language) { ?>
                                            <option value="<?= $language['LID'] ?>" <?= ($odata['ORDER']['PROPS']['LANGUAGE']['VALUE'] == strtoupper($language['LID'])) ? ('selected') : ('') ?>>
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
                                <h3><?= Loc::getMessage('FIELD_TABLE_HEADER_STAND') ?></h3>
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                            <input class="js-form-type-stand" type="radio" name="TYPESTAND" <?= ($odata['ORDER']['PROPS']['TYPESTAND']['VALUE'] == Wolk\OEM\Context::TYPE_STANDARD || empty($odata)) ? ('checked="checked"') : ('') ?> id="form-type-stand-standard-id" value="<?= Wolk\OEM\Context::TYPE_STANDARD ?>" />
                                            <?= Loc::getMessage('FIELD_TYPESTAND_STANDARD') ?>
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input class="js-form-type-stand" type="radio" name="TYPESTAND" <?= ($odata['ORDER']['PROPS']['TYPESTAND']['VALUE'] == Wolk\OEM\Context::TYPE_INDIVIDUAL) ? ('checked="checked"') : ('') ?> id="form-type-stand-individual-id" value="<?= Wolk\OEM\Context::TYPE_INDIVIDUAL ?>" />
                                            <?= Loc::getMessage('FIELD_TYPESTAND_INDIVIDUAL') ?>:
                                        </label>
                                    </div>
                                </div>
                                
                                <table class="table table-bordered" id="js-stand-id">
                                    <thead>
                                        <tr>
                                            <th><?= Loc::getMessage('FIELD_TABLE_TITLE') ?></th>
                                            <th><?= Loc::getMessage('FIELD_TABLE_PRICE') ?></th>
                                            <th><?= Loc::getMessage('FIELD_TABLE_WIDTH') ?></th>
                                            <th><?= Loc::getMessage('FIELD_TABLE_DEPTH') ?></th>
                                            <th><?= Loc::getMessage('FIELD_TABLE_COST') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <? if (!empty($odata['STAND'])) { ?>
                                            <tr>
                                                <td id="js-stand-title-id">
                                                    <input type="hidden" name="STAND" value="<?= $odata['STAND']['PRODUCT_ID'] ?>" />
                                                    <?= $odata['STAND']['NAME'] ?>
                                                </td>
                                                <td>
                                                    <input id="js-stand-price-id" type="text" class="form-control" name="STANDPRICE" value="<?= $odata['STAND']['PRICE'] ?>" />
                                                </td>
                                                <td>
                                                    <input id="js-stand-width-id" type="text" class="form-control" name="STANDWIDTH" value="<?= $odata['ORDER']['PROPS']['WIDTH']['VALUE'] ?>" />
                                                </td>
                                                <td>
                                                    <input id="js-stand-depth-id" type="text" class="form-control" name="STANDDEPTH" value="<?= $odata['ORDER']['PROPS']['DEPTH']['VALUE'] ?>" />
                                                </td>
                                                <td id="js-stand-cost-id"><?= ($odata['STAND']['PRICE'] * $odata['STAND']['QUANTITY']) ?></td>
                                            </tr>
                                        <? } else { ?>
                                            <tr>
                                                <td id="js-stand-title-id">&mdash;</td>
                                                <td>
                                                    <input id="js-stand-price-id" type="text" class="form-control" name="STANDPRICE" value="0.00" disabled="disabled" />
                                                </td>
                                                <td>
                                                    <input id="js-stand-width-id" type="text" class="form-control" name="STANDWIDTH" value="1" disabled="disabled" />
                                                </td>
                                                <td>
                                                    <input id="js-stand-depth-id" type="text" class="form-control" name="STANDDEPTH" value="1" disabled="disabled" />
                                                </td>
                                                <td id="js-stand-cost-id">0.00</td>
                                            </tr>
                                        <? } ?>
                                    </tbody>
                                </table>
                                <? if (empty($odata)) { ?>
                                    <button type="button" class="btn btn-primary" id="js-form-stand-select-button-id" disabled="disabled">
                                        <?= Loc::getMessage('CHOOSE_STAND') ?>
                                    </button>
                                <? } else { ?>
                                    <button type="button" class="btn btn-primary" id="js-form-stand-select-button-id">
                                        <?= Loc::getMessage('CHOOSE_STAND') ?>
                                    </button>
                                <? } ?>
                            </div>
                        </div>
                        
                        <hr/>
                        
                        <div class="row">
                            <div class="col-md-11">
                                <h3><?= Loc::getMessage('FIELD_TABLE_HEADER_PRODUCTS') ?></h3>
                                <table class="table table-bordered table-condensed" id="js-positions-id">
                                    <thead>
                                        <tr>
                                            <th colspan="2"><?= Loc::getMessage('FIELD_TABLE_TITLE') ?></th>
											<th><?= Loc::getMessage('FIELD_TABLE_QUANTITY') ?></th>
                                            <th><?= Loc::getMessage('FIELD_TABLE_PRICE') ?></th>
                                            <th><?= Loc::getMessage('FIELD_TABLE_COST') ?></th>
                                            <th colspan="2"><?= Loc::getMessage('FIELD_TABLE_NOTE') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <? foreach ($odata['BASKETS'] as $bitem) { ?>
                                            <? if ($bitem['PROPS']['STAND']['VALUE'] == 'Y' || $bitem['PROPS']['INCLUDING']['VALUE'] == 'Y') { continue; } ?>
                                            
                                            <? $product = new Wolk\OEM\Products\Base($bitem['PRODUCT_ID']) ?>
                                            
                                            <tr id="position-<?= $product->getID() ?>" class="js-position row-position">
                                                <td class="td-image">
                                                    <? $isrc = $product->getImageSrc() ?>
                                                    <? if (!empty($isrc)) { ?>
                                                        <img src="<?= $isrc ?>" class="img-thumbnail position-image-preview" />
                                                    <? } else { ?>
                                                        <div class="no_foto"><?= Loc::getMessage('NO_IMAGE') ?></div>
                                                    <? } ?>
                                                </td>
                                                <td class="td-name">
                                                    <?= $product->getTitle() ?> 
                                                    <input type="hidden" name="PRODUCTS[<?= $bitem['ID'] ?>][ID]" value="<?= $product->getID() ?>" />
                                                </td>
                                                <td class="td-quantity">
                                                    <div class="col-xs-2">
                                                        <input type="text" class="js-quantity form-control input-text-small" name="PRODUCTS[<?= $bitem['ID'] ?>][QUANTITY]" value="<?= $bitem['QUANTITY'] ?>" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="js-price form-control input-text-small" name="PRODUCTS[<?= $bitem['ID'] ?>][PRICE]" value="<?= number_format($bitem['PRICE'], 2, '.', '') ?>" />
                                                </td>
                                                <td>
                                                    <span class="js-cost"><?= number_format(($bitem['PRICE'] * $bitem['QUANTITY']), 2, '.', '') ?></span>
                                                </td>
                                                <td>
                                                    <input type="text" class="js-comment form-control" name="PRODUCTS[<?= $bitem['ID'] ?>][NOTES]" value="<?= $bitem['NOTES'] ?>" />
                                                </td>
                                                <td class="td-props">
                                                    <div class="modal fade" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">×</span></button>
                                                                    <h4 class="modal-title"><?= Loc::getMessage('FIELD_PRODUCT_PROPERTIES') ?></h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="PRODUCTS[<?= $bitem['ID'] ?>][PROPS][INCLUDED]" value="<?= $bitem['PROPS']['INCLUDING'] ?>" />
                                                                    
                                                                    <? // Свойства продукции.
                                                                        $pvals = json_decode($bitem['PROPS']['PARAMS']['VALUE'], true);
                                                                        $props = $product->getSection()->getProperties();
                                                                        foreach ($props as $prop) {
                                                                            $propfile = $_SERVER['DOCUMENT_ROOT'].'/local/modules/wolk.oem/admin/order/props/' . strtolower($prop) . '.php';
                                                                            
                                                                            $pid  = $bitem['PRODUCT_ID'];
                                                                            $pbid = $bitem['ID'];
                                                                            $pval = $pvals[$prop];
                                                                            if (is_readable($propfile)) {
                                                                                include ($propfile);
                                                                            }
                                                                        }
                                                                    ?>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-primary <?= (!empty($props)) ? ('js-props') : ('disabled') ?>" title="<?= Loc::getMessage('LINK_POSITION_PROPERTIES') ?>"><span class="glyphicon glyphicon-tasks"></span></button>
                                                        <button type="button" class="btn btn-danger js-remove" title="<?= Loc::getMessage('LINK_POSITION_DELETE') ?>"><span class="glyphicon glyphicon-remove"></span></button>
                                                    </div>
                                                </td>
                                           </tr>
                                        <? } ?>
                                    </tbody>
                                </table>
                                <? if (empty($odata)) { ?>
                                    <button type="button" class="btn btn-primary" id="js-form-insert-position-id" disabled="disabled">
                                        <?= Loc::getMessage('LINK_POSITION_INSERT') ?>
                                    </button>
                                <? } else { ?>
                                    <button type="button" class="btn btn-primary" id="js-form-insert-position-id">
                                        <?= Loc::getMessage('LINK_POSITION_INSERT') ?>
                                    </button>
                                <? } ?>
                            </div>
                        </div>
                        
                        <hr/>
                        
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label class="control-label" for="form-comments-id"><?= Loc::getMessage('FIELD_ORDER_COMMENT') ?>:</label>
                                    <textarea class="form-control" id="form-comments-id" name="COMMENTS" rows="8"><?= $odata['ORDER']['USER_DESCRIPTION'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <hr/>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="form-surcharge-id"><?= Loc::getMessage('FIELD_SURCHARGE') ?>:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="form-surcharge-id" name="SURCHARGE" value="<?= $odata['ORDER']['PROPS']['SURCHARGE']['VALUE'] ?>" />
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="form-vat-id" name="VAT" value="1" <?= ($odata['EVENT']['PROPS']['INCLUDE_VAT']['VALUE'] == 'Y') ? ('checked="checked"') : ('') ?> />
                                            <?= Loc::getMessage('FIELD_INCLUDE_VAT') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label"><?= Loc::getMessage('ORDER_PRICE') ?>:</label>
                                <table class="table table-bordered table-condensed table-prices">
                                    <tr class="bold">
                                        <td class="bold"><?= Loc::getMessage('ORDER_SUMMARY') ?>:</td>
                                        <td id="js-order-price-baskets-id"><?= number_format($odata['PRICES']['BASKET'], 2, '.', '') ?></td>
                                    </tr>
									<tr>
                                        <td class="bold"><?= Loc::getMessage('ORDER_SURCHARGE') ?>:</td>
                                        <td id="js-order-price-surcharge-id"><?= number_format($odata['PRICES']['SURCHARGE'], 2, '.', '') ?></td>
                                    </tr>
									<tr>
                                        <td class="bold"><?= Loc::getMessage('ORDER_TOTAL') ?>:</td>
                                        <td id="js-order-price-summ-id"><?= number_format($odata['PRICES']['TOTAL_WITH_SUR'], 2, '.', '') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="bold"><?= Loc::getMessage('ORDER_VAT') ?>:</td>
                                        <td id="js-order-price-vat-id"><?= number_format($odata['PRICES']['TAX'], 2, '.', '') ?></td>
                                    </tr>
                                    <tr class="info bold">
                                        <td class="bold"><?= Loc::getMessage('ORDER_TOTAL_VAT') ?>:</td>
                                        <td id="js-order-price-total-id"><?= number_format($odata['PRICES']['TOTAL'], 2, '.', '') ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <hr/>
						
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a id="js-submit-id" class="btn btn-success" href="javascript:void(0)"><?= Loc::getMessage('SAVE') ?></a>
                                </div>
                            </div>
                        </div>
                    </form>
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
        
        
        // Выбор выставки.
        $('#form-event-id').on('change', function() {
            var $forminvoice  = $('#form-invoice-id');
            var eventinvoices = invoices[$(this).val()];
            
            $forminvoice.html('');
            for (var code in eventinvoices) {
                $forminvoice.append('<option value="' + code + '">' + eventinvoices[code] + '</option>');
            }
			
			if ($(this).val() > 0) {
				$('#js-form-insert-position-id').prop('disabled', false);
                if ($('.js-form-type-order:checked').val() != 'QUICK') {
                    $('#js-form-stand-select-button-id').prop('disabled', false);
                }
			} else {
				$('#js-form-insert-position-id').prop('disabled', 'disabled');
                $('#js-form-stand-select-button-id').prop('disabled', 'disabled');
			}
            
            $(this).find('option:first-child').remove();
        });
        
        
        // Поиск участника выставки.
        $('#js-form-user-id').on('keyup', function() {  
            var query   = $(this).val();
            var $select = $('#js-form-user-select-id');
            
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
        $('#js-form-user-select-id').on('click', 'option', function() {
            var $that = $(this);
            
            $('#js-form-user-id').val($that.text());
            $('#furm-user-value-id').val($that.val());
            
            $('#js-form-user-select-id').addClass('hidden').html('').append($that);
        });
        
        
        // Выбор типа заказа.
        $('.js-form-type-order').on('change', function() {
            if ($('.js-form-type-order:checked').val() == 'QUICK') {
                $('#js-form-stand-select-button-id').prop('disabled', 'disabled');
            } else {
                $('#js-form-stand-select-button-id').prop('disabled', false);
            }
        });
        
        
        // Добавление стенда.
        $('#js-form-stand-select-button-id').on('click', function() {
            var event     = $('#form-event-id').val();
            var language  = $('#form-language-id').val();
            var currency  = $('#form-currency-id').val();
            var typestand = $('.js-form-type-stand:checked').val();
            
            jsUtils.OpenWindow('/bitrix/admin/wolk_oem_order_element_search.php?lang=ru&IBLOCK_ID=<?= STANDS_IBLOCK_ID ?>&func=SetStandElement&event=' + event + '&language=' + language + '&currency=' + currency + '&typestand=' + typestand, 900, 600);
        });
        
        
        // Изменение цены стенда.
        $('#js-stand-id').on('keyup', '#js-stand-price-id', function() {
            $('#js-stand-cost-id').text(($(this).val() * $('#js-stand-width-id').val() * $('#js-stand-depth-id').val()).toFixed(2));
            
            // Пересчет цены.
            CalcPrices();
        });
        
        
        // Изменение ширины стенда.
        $('#js-stand-id').on('keyup', '#js-stand-width-id', function() {
            $('#js-stand-cost-id').text(($('#js-stand-price-id').val() * $(this).val() * $('#js-stand-depth-id').val()).toFixed(2));
            
            // Пересчет цены.
            CalcPrices();
        });
        
        
         // Изменение шлубины стенда.
        $('#js-stand-id').on('keyup', '#js-stand-depth-id', function() {
            $('#js-stand-cost-id').text(($('#js-stand-price-id').val() * $('#js-stand-width-id').val() * $(this).val()).toFixed(2));
            
            // Пересчет цены.
            CalcPrices();
        });
        
        
        // Добавление товарной позиции.
        $('#js-form-insert-position-id').on('click', function() {
            var event     = $('#form-event-id').val();
            var language  = $('#form-language-id').val();
            var currency  = $('#form-currency-id').val();
            var typestand = $('.js-form-type-stand:checked').val();
            var basketid  = 'n' + (new Date()).getTime();
            
            jsUtils.OpenWindow('/bitrix/admin/wolk_oem_order_product_search.php?lang=ru&IBLOCK_ID=<?= IBLOCK_PRODUCTS_ID ?>&func=SetElement&event=' + event + '&language=' + language + '&currency=' + currency + '&typestand=' + typestand + '&bid=' + basketid, 900, 600);
        });
        
        
        // Изменене количества позиций.
        $('#js-positions-id').on('keyup', '.js-quantity', function() {
            var $that = $(this);
            var position = $that.closest('.js-position');
            var quantity = parseFloat($that.val());
            var price    = parseFloat(position.find('.js-price').val());
            
            if (quantity <= 0) {
                quantity = 1;
            }
            position.find('.js-cost').text((price * quantity).toFixed(2));
            
            // Пересчет цены.
            CalcPrices();
        });
        
        
        // Изменение цены товара.
        $('#js-positions-id').on('keyup', '.js-price', function() {
            var $that = $(this);
            var position = $that.closest('.js-position');
            var quantity = parseFloat(position.find('.js-quantity').val());
            
            if (quantity <= 0) {
                quantity = 1;
            }
            position.find('.js-cost').text(($that.val() * quantity).toFixed(2));
        
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
		
		
        // Отправка формы заказа.
		$('#js-submit-id').on('click', function() {
			$(this).closest('form').submit();
		});
        
        
        // Всплывающее окно для своства.
        $(document).on('click', '.js-props', function() {
            var $wrap = $(this).closest('td');
            
            $wrap.find('.modal').modal();
        });
        
        
        // Модальные окна.
        $(document).on('click', '[data-modal]', function(e) {
            e.preventDefault();
            $($(this).data('modal')).modal();
            return false;
        });
        
        
        // СВОЙСТВО: Выбор цвета.
        $(document).on('click', '.js-colors-palette .js-color-item', function(e) {
            var $that    = $(this);
            var $parent  = $that.parent('li');
            var $wrapper = $that.closest('.js-param-block');

            // Данные свойства для корзины.
            var $input_value = $wrapper.find('.js-param-x-value');
            var $input_color = $wrapper.find('.js-param-x-color');
            
            if ($parent.hasClass('active')) {
                $parent.removeClass('active');
                $input_value.val('');
                $input_color.val('');
            } else {
                $parent.closest('.js-colors-palette').find('li').removeClass('active');
                $parent.addClass('active');
                $input_value.val($that.data('id'));
                $input_color.val($that.css('background'));
            }
        });
        
    });
    
    
    function CalcPrices()
    {
		var price = 0;
		var sur   = 0;
        var vat   = 0;
        var summ  = 0;
        var total = 0;
        var surcharge  = parseFloat($('#form-surcharge-id').val());
        var includevat = $('#form-vat-id').is(':checked');
        
        // Стоимость стенда.
        price += parseFloat($('#js-stand-cost-id').text());
        
        $('#js-positions-id tbody .js-cost').each(function() {
            price += parseFloat($(this).text());
        });
		
		// Стоимость без наценок.
		summ = price;
        
        // Наценка.
        if (surcharge > 0) {
			sur  = summ * surcharge / 100;
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
        
		$('#js-order-price-baskets-id').html(price.toFixed(2));
		$('#js-order-price-surcharge-id').html(sur.toFixed(2));
        $('#js-order-price-summ-id').html(summ.toFixed(2));
        $('#js-order-price-vat-id').html(vat.toFixed(2));
        $('#js-order-price-total-id').html(total.toFixed(2));
    }
    
    
    // Сохранение товарной позиции.
    function SetElement(element)
    {
        element = JSON.parse(element);
        
        var html = '<tr id="position-' + element.ID + '" class="js-position row-position">';
        var code = element.BASKET_ID;
        var name = '[' + code + ']';
        
        if (element.PICTURE) {
            html += '<td class="td-image"><img src="' + element.PICTURE + '" class="img-thumbnail position-image-preview" /></td>';
        } else {
            html += '<td class="td-image"><div class="no_foto">Нет картинки</div></td>';
        }
        html += '<td class="td-name">' + element.NAME + ' <input type="hidden" name="PRODUCTS' + name + '[ID]" value="' + element.ID + '" /></td>';
        html += '<td class="td-quantity"><div class="col-xs-2"><input type="text" class="js-quantity form-control input-text-small" name="PRODUCTS' + name + '[QUANTITY]" value="1" /></div></td>';
        html += '<td><input type="text" class="js-price form-control input-text-small" name="PRODUCTS' + name + '[PRICE]" value="' + (element.PRICE).toFixed(2) + '" /></td>';
        html += '<td><span class="js-cost">' + (element.PRICE).toFixed(2) + '</span></td>';
        html += '<td><input type="text" class="js-comment form-control" name="PRODUCTS' + name + '[NOTES]" value="" /></td>';
        html += '<td class="td-props">';
        html += element.PRODUCT.HTML;
        html += '<div class="btn-group" role="group">';
        if (element.PRODUCT.HTML.length) {
            html += '<button type="button" class="btn btn-primary js-props" title="<?= Loc::getMessage('LINK_POSITION_PROPERTIES') ?>"><span class="glyphicon glyphicon-tasks"></span></button>';
        } else {
            html += '<button type="button" class="btn btn-primary disabled" title="<?= Loc::getMessage('LINK_POSITION_PROPERTIES') ?>"><span class="glyphicon glyphicon-tasks"></span></button>';
        }
        html += '<button type="button" class="btn btn-danger js-remove" title="<?= Loc::getMessage('LINK_POSITION_DELETE') ?>"><span class="glyphicon glyphicon-remove"></span></button>';
        html += '</div>';
        html += '</td>';
        html += '</tr>';
        
        $('#js-positions-id tbody').append(html);
        
        // Пересчет цены.
        CalcPrices();
    }
    
    
    // Сохранение стенда.
    function SetStandElement(element)
    {
        element = JSON.parse(element);
        
        $('#js-stand-title-id').html('<input type="hidden" name="STAND" value="' + element.ID + '" />' + element.NAME);
        $('#js-stand-price-id').prop('disabled', false).val(element.PRICE.toFixed(2));
        $('#js-stand-width-id').prop('disabled', false).val(1);
        $('#js-stand-depth-id').prop('disabled', false).val(1);
        $('#js-stand-cost-id').text(element.PRICE.toFixed(2));
        
        // Пересчет цены.
        CalcPrices();
    }
    
</script>

<style>
	#js-form-user-select-id {
        cursor: pointer;
    }

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
    
    .td-image {
        width: 50px;
    }
    
    .td-image .position-image-preview {
        height: 70px;
        max-width: 70px;
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
    
    .js-cost {
        font-weight: bold;
    }
    
	.table-prices td {
		font-size: 16px;
	}
    .table-prices tr.bold td, .table-prices td.bold {
		
        font-weight: 600;
    }
    .table-prices td:nth-child(1) {
        width: 30%;
    }
    
    .modal-header, .modal-footer {
        background: #f0f0f0;
    }
    
    .td-name {
        max-width: 120px;
    }
    
    .td-quantity input {
        max-width: 80px;
    }
    
    .td-props {
        min-width: 82px;
    }
</style>


<? require ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>