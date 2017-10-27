<?php

//////////////////////////
//START of the custom form
//////////////////////////

use Bitrix\Main\Localization\Loc;

use Wolk\OEM\Basket;
use Wolk\OEM\Stand;
use Wolk\OEM\Products\Base as Product;
use Wolk\OEM\Prices\Stand as StandPrices;
use Wolk\OEM\Prices\Product as ProductPrices;

IncludeModuleLangFile(__FILE__);

\Bitrix\Main\Loader::includeModule('wolk.core');
\Bitrix\Main\Loader::includeModule('wolk.oem');

// Свойства инфоблока мероприятий.
$props = Wolk\Core\Helpers\IBlock::getProps(IBLOCK_EVENTS_ID);


// Объект мероприятия.
$event = new Wolk\OEM\Event($ID);


// Список стендов.
$result = Stand::getList(
    array(
        'filter' => array('ACTIVE' => 'Y'),
        'select' => array('ID', 'NAME'),
        'order'  => array('NAME' => 'ASC')
    ),
    false
);
$stands = array();
while ($item = $result->fetch()) {
    $stands[$item['ID']] = $item;
}
unset($result, $item);


// Список продукции.
$products = Product::getList([
	'filter' => array('ACTIVE' => 'Y'),
	'order'  => array('NAME' => 'ASC')
]);


// Выбранные стенды.
$result = CIBlockElement::GetPropertyValues(
    IBLOCK_EVENTS_ID,
    ['ID' => $ID], 
    false, 
    ['CODE' => IBLOCK_PROPERTY_SELECTED_STANDS_ID] //  ['CODE' => 'STANDS']
)->fetch();

$selected_stands = $result[IBLOCK_PROPERTY_SELECTED_STANDS_ID];



// Выбранные элементы продукции.
$result = CIBlockElement::GetPropertyValues(
    IBLOCK_EVENTS_ID,
    ['ID' => $ID], 
    false, 
    ['CODE' => IBLOCK_PROPERTY_SELECTED_PRODUCTS_ID] //  ['CODE' => 'PRODUCTS']
)->fetch();

$selected_products_standard   = $result[IBLOCK_PROPERTY_SELECTED_PRODUCTS_STANDARD_ID];
$selected_products_individual = $result[IBLOCK_PROPERTY_SELECTED_PRODUCTS_INDIVIDUAL_ID];

$selected_products = array_unique(array_merge($selected_products_standard, $selected_products_individual));

// /local/modules/wolk.oem/admin/events/before_save.php
// /local/modules/wolk.oem/admin/events/iblock_element_edit.php


// Валюты.
$currencies = \Bitrix\Currency\CurrencyManager::getCurrencyList();


// Цены на стенды.
$result = StandPrices::getList(
    array(
        'filter' => [StandPrices::FIELD_EVENT => $event->getID(), StandPrices::FIELD_STAND => $selected_stands]
    ),
    false
);
$prices_stands = array();
while ($item = $result->fetch()) {
    $prices_stands
        [$item[StandPrices::FIELD_TYPE]]
        [$item[StandPrices::FIELD_LANG]]
        [$item[StandPrices::FIELD_STAND]] 
    = $item;
}


// Цены на продукцию (стандартная застройка).
$result = ProductPrices::getList(
    array(
        'filter' => [
			ProductPrices::FIELD_EVENT   => $event->getID(), 
			ProductPrices::FIELD_PRODUCT => $selected_products
		]
    ),
    false
);
$prices_products = array();
while ($item = $result->fetch()) {
    $prices_products
        [$item[ProductPrices::FIELD_TYPE]]
        [$item[ProductPrices::FIELD_LANG]]
        [$item[ProductPrices::FIELD_PRODUCT]] 
    = $item;
}



// Подключение библиотеки jQuery.
CJSCore::Init('jquery');


// Кнопка "Настроить"
$aMenu = [];
if (false == ((true == defined('BT_UT_AUTOCOMPLETE')) && (1 == BT_UT_AUTOCOMPLETE))) {
    $link = DeleteParam(["mode"]);
    $link = $GLOBALS["APPLICATION"]->GetCurPage() . "?mode=settings" . ($link <> "" ? "&" . $link : "");
    $aMenu[] = [
        "TEXT"  => GetMessage("IBEL_E_SETTINGS"),
        "TITLE" => GetMessage("IBEL_E_SETTINGS_TITLE"),
        "LINK"  => "javascript:" . $tabControl->GetName() . ".ShowSettings('" . urlencode($link) . "')",
        "ICON"  => "btn_settings",
    ];

    $context = new CAdminContextMenu($aMenu);
    $context->Show();
}


// стандартный файл-обработчик Битрикс.
include (dirname(__FILE__) . '/iblock_element_edit_base.before.php');


////////////////////
// Кастомные поля //
////////////////////
?>

<? $tabControl->BeginCustomField('PRODUCTS_PROPERTIES', Loc::getMessage('TAB_PRODUCTS_PROPERTIES')); ?>
	<tr>
        <td colspan="2">
			<table class="js-props-wrapper" width="100%" border="1" cellpadding="10">
                <thead>
					<tr>
						<th>Название</th>
						<th>Обязательность свойств</th>
						<th>Подпись</th>
						<th>Комментарий</th>
					</tr>
                </thead>
                <tbody>
					<? $sections = [] ?>
                    <? foreach ($products as $product) { ?>
						<?	// Раздел продукции.
							if (!array_key_exists($product->getSectionID(), $sections)) {
								$sections[$product->getSectionID()] = $product->getSection();
							}
							$section = $sections[$product->getSectionID()];
						?>
						<tr>
							<td>
								<b><?= $product->getTitle() ?></b>
							</td>
							<td width="35%">
								<? $props = $section->getProperties() ?>
								<? foreach ($props as $prop) { ?>
									<div style="border: 1px dotted #777777; border-radius: 5px; margin-bottom: 2px; padding: 5px; overflow: hidden;">
										<input type="checkbox" name="PROPREQS[<?= $product->getID() ?>][<?= $prop ?>]" value="Y" />
										<span><?= Loc::getMessage('PROP_' . $prop) ?></span>
										
										<? // Если у продукции есть оплата за символы на фризовой панели. // ?>
										<? if ($product->isSpecialType(Product::SPECIAL_TYPE_FASCIA) && $prop == Basket::PARAM_TEXT) { ?>
											<input 
												type="number" 
												step="1"
												title="Количество бесплатных символов"
												name="PROPFASCIA[<?= $product->getID() ?>][<?= $prop ?>]" 
												value=""
												style="float: right;"
											/>
										<? } ?>
									</div>
								<? } ?>
							</td>
							<td>
								<input type="text" name="PROPNAMES[<?= $product->getID() ?>][<?= $prop ?>]" value="" />
							</td>
							<td>
								<textarea cols="30" rows="4" style="resize: none;"></textarea>
							</td>
						</tr>
					<? } ?>
				</tbody>
			</table>
		</td>
	</tr>
<? $tabControl->EndCustomField('PRODUCTS_PROPERTIES', ''); ?>



<? $tabControl->BeginCustomField('STANDS_PRICES_STANDARD', 'Цены на стенды (стандартные)'); ?>
    <tr>
        <td>
            <script>
                $(document).ready(function() {
                    $('.js-prices-stands-use-standard').on('change', function() {
                        var $that = $(this);
                        var $wrap = $that.closest('.js-prices-wrapper');
                        
                        var site = $that.data('site');
                        var from = $that.find('option:selected').val();
                        
                        if (from == undefined) {
                            return;
                        }
                        //var currency = $wrap.find('.js-prices-stands-currency-standard-' + from).val();
                        
                        $wrap.find('.js-prices-stands-standard-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-stands-standard-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                        //$wrap.find('.js-prices-stands-currency-standard-' + site + ' option[value="' + currency + '"]').prop('selected', 'selected');
                    });
                });
            </script>
			
            <table class="js-prices-wrapper">
                <thead>
					<tr>
						<th>Название</th>
						<th>Цены RU</th>
						<th>Цены EN</th>
					</tr>
                </thead>
                <tbody>
                    <? foreach ($selected_stands as $selected_stand) { ?>
                        <tr class="js-prices-values">
                            <td>
                                <input type="hidden" name="STANDS[<?= $selected_stand ?>]" value="<?= $selected_stand ?>" />
                                <?= $stands[$selected_stand]['NAME'] ?>
                            </td>
                            <td>
                                <input 
                                    class="js-prices-stands-standard-<?= LANG_RU ?>"
                                    name="PRICES_STANDS[<?= StandPrices::TYPE_STANDARD ?>][<?= LANG_RU_UP ?>][<?= $selected_stand ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_stands[StandPrices::TYPE_STANDARD][LANG_RU_UP][$selected_stand][StandPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_stand ?>"
                                />
                            </td>
                            <td>
                                <input
                                    class="js-prices-stands-standard-<?= LANG_EN ?>"
                                    name="PRICES_STANDS[<?= StandPrices::TYPE_STANDARD ?>][<?= LANG_EN_UP ?>][<?= $selected_stand ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_stands[StandPrices::TYPE_STANDARD][LANG_EN_UP][$selected_stand][StandPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_stand ?>"
                                />
                            </td>
                        </tr>
                    <? } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <hr/>
                        </td>
                    <tr>
					<tr>
						<td>
                            <b>Использовать цены другого языка</b>
                        </td>
						<td>
							<select class="js-prices-stands-use-standard" data-site="<?= LANG_RU ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_EN ?>">Цены EN</option>
							</select>
						</td>
						<td>
							<select class="js-prices-stands-use-standard" data-site="<?= LANG_EN ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_RU ?>">Цены RU</option>
							</select>
						</td>
					</tr>
                </tfoot>
            </table>
        </td>
    </tr>
<? $tabControl->EndCustomField('STANDS_PRICES_STANDARD', ''); ?>



<? $tabControl->BeginCustomField('STANDS_PRICES_INDIVIDUAL', 'Цены на стенды (индивидуальные)'); ?>
    <tr>
        <td>
            <script>
                $(document).ready(function() {
                    $('.js-prices-stands-use-individual').on('change', function() {
                        var $that = $(this);
                        var $wrap = $that.closest('.js-prices-wrapper');
                        
                        var site = $that.data('site');
                        var from = $that.find('option:selected').val();
                        
                        if (from == undefined) {
                            return;
                        }
                        //var currency = $wrap.find('.js-prices-stands-currency-individual-' + from).val();
                        
                        $wrap.find('.js-prices-stands-individual-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-stands-individual-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                        //$wrap.find('.js-prices-stands-currency-individual-' + site + ' option[value="' + currency + '"]').prop('selected', 'selected');
                    });
                });
            </script>
        
            <table class="js-prices-wrapper">
                <thead>
					<tr>
						<th>Название</th>
						<th>Цены RU</th>
						<th>Цены EN</th>
					</tr>
                </thead>
                <tbody>
                    <? foreach ($selected_stands as $selected_stand) { ?>
                        <tr class="js-prices-values">
                            <td>
                                <input type="hidden" name="STANDS[<?= $selected_stand ?>]" value="<?= $selected_stand ?>" />
                                <?= $stands[$selected_stand]['NAME'] ?>
                            </td>
                            <td>
                                <input 
                                    class="js-prices-stands-individual-<?= LANG_RU ?>"
                                    name="PRICES_STANDS[<?= StandPrices::TYPE_INDIVIDUAL ?>][<?= LANG_RU_UP ?>][<?= $selected_stand ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_stands[StandPrices::TYPE_INDIVIDUAL][LANG_RU_UP][$selected_stand][StandPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_stand ?>"
                                />
                            </td>
                            <td>
                                <input
                                    class="js-prices-stands-individual-<?= LANG_EN ?>"
                                    name="PRICES_STANDS[<?= StandPrices::TYPE_INDIVIDUAL ?>][<?= LANG_EN_UP ?>][<?= $selected_stand ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_stands[StandPrices::TYPE_INDIVIDUAL][LANG_EN_UP][$selected_stand][StandPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_stand ?>"
                                />
                            </td>
                        </tr>
                    <? } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <hr/>
                        </td>
                    <tr>
					<tr>
						<td>
                            <b>Использовать цены другого языка</b>
                        </td>
						<td>
							<select class="js-prices-stands-use-individual" data-site="<?= LANG_RU ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_EN ?>">Цены EN</option>
							</select>
						</td>
						<td>
							<select class="js-prices-stands-use-individual" data-site="<?= LANG_EN ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_RU ?>">Цены RU</option>
							</select>
						</td>
					</tr>
                </tfoot>
            </table>
        </td>
    </tr>
<? $tabControl->EndCustomField('STANDS_PRICES_INDIVIDUAL', ''); ?>



<? $tabControl->BeginCustomField('PRODUCTS_PRICES_STANDARD', 'Цены на продукцию (стандартные)'); ?>
    <tr>
        <td>
            <script>
                $(document).ready(function() {
                    $('.js-prices-products-use-standard').on('change', function() {
                        var $that = $(this);
                        var $wrap = $that.closest('.js-prices-wrapper');
                        
                        var site = $that.data('site');
                        var from = $that.find('option:selected').val();
                        
                        if (from == undefined) {
                            return;
                        }
                        //var currency = $wrap.find('.js-prices-products-currency-standard-' + from).val();
                        
                        $wrap.find('.js-prices-products-standard-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-products-standard-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                        //$wrap.find('.js-prices-products-currency-standard-' + site + ' option[value="' + currency + '"]').prop('selected', 'selected');
                    });
                });
            </script>
        
            <table class="js-prices-wrapper">
                <thead>
					<tr>
						<th>Название</th>
						<th>Цены RU</th>
						<th>Цены EN</th>
					</tr>
                </thead>
                <tbody>
                    <? foreach ($selected_products_standard as $selected_product) { ?>
                        <tr class="js-prices-values">
                            <td>
                                <input type="hidden" name="PRODUCTS[<?= $selected_product ?>]" value="<?= $selected_product ?>" />
                                <?= $products[$selected_product]->getTitle() ?>
                            </td>
                            <td>
                                <input 
                                    class="js-prices-products-standard-<?= LANG_RU ?>"
                                    name="PRICES_PRODUCTS[<?= ProductPrices::TYPE_STANDARD ?>][<?= LANG_RU_UP ?>][<?= $selected_product ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_products[ProductPrices::TYPE_STANDARD][LANG_RU_UP][$selected_product][ProductPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_product ?>"
                                />
                            </td>
                            <td>
                                <input
                                    class="js-prices-products-standard-<?= LANG_EN ?>"
                                    name="PRICES_PRODUCTS[<?= ProductPrices::TYPE_STANDARD ?>][<?= LANG_EN_UP ?>][<?= $selected_product ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_products[ProductPrices::TYPE_STANDARD][LANG_EN_UP][$selected_product][ProductPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_product ?>"
                                />
                            </td>
                        </tr>
                    <? } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <hr/>
                        </td>
                    <tr>
					<tr>
						<td>
                            <b>Использовать цены другого языка</b>
                        </td>
						<td>
							<select class="js-prices-products-use-standard" data-site="<?= LANG_RU ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_EN ?>">Цены EN</option>
							</select>
						</td>
						<td>
							<select class="js-prices-products-use-standard" data-site="<?= LANG_EN ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_RU ?>">Цены RU</option>
							</select>
						</td>
					</tr>
                </tfoot>
            </table>
        </td>
    </tr>
<? $tabControl->EndCustomField('PRODUCTS_PRICES_STANDARD', ''); ?>



<? $tabControl->BeginCustomField('PRODUCTS_PRICES_INDIVIDUAL', 'Цены на продукцию (индивидуальные)'); ?>
    <tr>
        <td>
            <script>
                $(document).ready(function() {
                    $('.js-prices-products-use-individual').on('change', function() {
                        var $that = $(this);
                        var $wrap = $that.closest('.js-prices-wrapper');
                        
                        var site = $that.data('site');
                        var from = $that.find('option:selected').val();
                        
                        if (from == undefined) {
                            return;
                        }
                        //var currency = $wrap.find('.js-prices-products-currency-individual-' + from).val();
                        
                        $wrap.find('.js-prices-products-individual-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-products-individual-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                        //$wrap.find('.js-prices-products-currency-individual-' + site + ' option[value="' + currency + '"]').prop('selected', 'selected');
                    });
                });
            </script>
        
            <table class="js-prices-wrapper">
                <thead>
					<tr>
						<th>Название</th>
						<th>Цены RU</th>
						<th>Цены EN</th>
					</tr>
                </thead>
                <tbody>
                    <? foreach ($selected_products_individual as $selected_product) { ?>
                        <tr class="js-prices-values">
                            <td>
                                <input type="hidden" name="PRODUCTS[<?= $selected_product ?>]" value="<?= $selected_product ?>" />
                                <?= $products[$selected_product]->getTitle() ?>
                            </td>
                            <td>
                                <input 
                                    class="js-prices-products-individual-<?= LANG_RU ?>"
                                    name="PRICES_PRODUCTS[<?= ProductPrices::TYPE_INDIVIDUAL ?>][<?= LANG_RU_UP ?>][<?= $selected_product ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_products[ProductPrices::TYPE_INDIVIDUAL][LANG_RU_UP][$selected_product][ProductPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_product ?>"
                                />
                            </td>
                            <td>
                                <input
                                    class="js-prices-products-individual-<?= LANG_EN ?>"
                                    name="PRICES_PRODUCTS[<?= ProductPrices::TYPE_INDIVIDUAL ?>][<?= LANG_EN_UP ?>][<?= $selected_product ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_products[ProductPrices::TYPE_INDIVIDUAL][LANG_EN_UP][$selected_product][ProductPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_product ?>"
                                />
                            </td>
                        </tr>
                    <? } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <hr/>
                        </td>
                    <tr>
					<tr>
						<td>
                            <b>Использовать цены другого языка</b>
                        </td>
						<td>
							<select class="js-prices-products-use-individual" data-site="<?= LANG_RU ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_EN ?>">Цены EN</option>
							</select>
						</td>
						<td>
							<select class="js-prices-products-use-individual" data-site="<?= LANG_EN ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_RU ?>">Цены RU</option>
							</select>
						</td>
					</tr>
                </tfoot>
            </table>
        </td>
    </tr>
<? $tabControl->EndCustomField('PRODUCTS_PRICES_INDIVIDUAL', ''); ?>

<script>
	function CheckLangFields(element) {
		if (element.checked) {
			$(element).closest('.edit-table').find("input, textarea, select").not(element).attr('disabled', false);
		} else {
			$(element).closest('.edit-table').find("input, textarea, select").not(element).attr('disabled', true);
		}
	}
</script>
    
<? 
// стандартный файл-обработчик Битрикс.
include (dirname(__FILE__) . '/iblock_element_edit_base.after.php');
