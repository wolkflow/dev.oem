<?php
//////////////////////////
//START of the custom form
//////////////////////////

use Wolk\OEM\Stand;
use Wolk\OEM\Products\Base as Product;
use Wolk\OEM\Prices\Stand as StandPrices;
use Wolk\OEM\Prices\Product as ProductPrices;


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
$result = Product::getList(
    array(
        'filter' => array('ACTIVE' => 'Y'),
        'select' => array('ID', 'NAME'),
        'order'  => array('NAME' => 'ASC')
    ),
    false
);
$products = array();
while ($item = $result->fetch()) {
    $products[$item['ID']] = $item;
}
unset($result, $item);


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

$selected_products = $result[IBLOCK_PROPERTY_SELECTED_PRODUCTS_ID];

// /local/modules/wolk.oem/admin/events/before_save.php
// /local/modules/wolk.oem/admin/events/iblock_element_edit.php

// print_r($selected_products);


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


// Цены на продукцию.
$result = ProductPrices::getList(
    array(
        'filter' => [ProductPrices::FIELD_EVENT => $event->getID(), ProductPrices::FIELD_PRODUCT => $selected_products]
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
                        var currency = $wrap.find('.js-prices-stands-currency-standard-' + from).val();
                        
                        $wrap.find('.js-prices-stands-standard-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-stands-standard-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                        $wrap.find('.js-prices-stands-currency-standard-' + site + ' option[value="' + currency + '"]').prop('selected', 'selected');
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
                            Используемая валюта
                        </td>
						<td>
							<select name="CURRENCY_STANDS[<?= StandPrices::TYPE_STANDARD ?>][<?= LANG_RU_UP ?>]" class="js-prices-stands-currency-standard-<?= LANG_RU ?>">
								<option value="">- не выбрано -</option>
								<? foreach ($currencies as $id => $currency) { ?>
									<option <? if ($id == $event->getCurrencyStandsStandard(LANG_RU_UP)) { ?> selected <? } ?> value="<?= $id ?>">
                                        <?= $currency ?>
                                    </option>
								<? } ?>
							</select>
						</td>
						<td>
							<select name="CURRENCY_STANDS[<?= StandPrices::TYPE_STANDARD ?>][<?= LANG_EN_UP ?>]" class="js-prices-stands-currency-standard-<?= LANG_EN ?>">
								<option value="">- не выбрано -</option>
								<? foreach ($currencies as $id => $currency) { ?>
									<option <? if ($id == $event->getCurrencyStandsStandard(LANG_EN_UP)) { ?> selected <? } ?> value="<?= $id ?>">
                                        <?= $currency ?>
                                    </option>
								<? } ?>
							</select>
						</td>
					</tr>
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
                        var currency = $wrap.find('.js-prices-stands-currency-individual-' + from).val();
                        
                        $wrap.find('.js-prices-stands-individual-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-stands-individual-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                        $wrap.find('.js-prices-stands-currency-individual-' + site + ' option[value="' + currency + '"]').prop('selected', 'selected');
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
                            Используемая валюта
                        </td>
						<td>
							<select name="CURRENCY_STANDS[<?= StandPrices::TYPE_INDIVIDUAL ?>][<?= LANG_RU_UP ?>]" class="js-prices-stands-currency-individual-<?= LANG_RU ?>">
								<option value="">- не выбрано -</option>
								<? foreach ($currencies as $id => $currency) { ?>
									<option <? if ($id == $event->getCurrencyStandsIndividual(LANG_RU_UP)) { ?> selected <? } ?> value="<?= $id ?>">
                                        <?= $currency ?>
                                    </option>
								<? } ?>
							</select>
						</td>
						<td>
							<select name="CURRENCY_STANDS[<?= StandPrices::TYPE_INDIVIDUAL ?>][<?= LANG_EN_UP ?>]" class="js-prices-stands-currency-individual-<?= LANG_EN ?>">
								<option value="">- не выбрано -</option>
								<? foreach ($currencies as $id => $currency) { ?>
									<option <? if ($id == $event->getCurrencyStandsIndividual(LANG_EN_UP)) { ?> selected <? } ?> value="<?= $id ?>">
                                        <?= $currency ?>
                                    </option>
								<? } ?>
							</select>
						</td>
					</tr>
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
                        var currency = $wrap.find('.js-prices-products-currency-standard-' + from).val();
                        
                        $wrap.find('.js-prices-products-standard-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-products-standard-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                        $wrap.find('.js-prices-products-currency-standard-' + site + ' option[value="' + currency + '"]').prop('selected', 'selected');
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
                    <? foreach ($selected_products as $selected_product) { ?>
                        <tr class="js-prices-values">
                            <td>
                                <input type="hidden" name="PRODUCTS[<?= $selected_product ?>]" value="<?= $selected_product ?>" />
                                <?= $products[$selected_product]['NAME'] ?>
                            </td>
                            <td>
                                <input 
                                    class="js-prices-products-standard-<?= LANG_RU ?>"
                                    name="PRICES_PRODUCTS[<?= StandPrices::TYPE_STANDARD ?>][<?= LANG_RU_UP ?>][<?= $selected_product ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_products[StandPrices::TYPE_STANDARD][LANG_RU_UP][$selected_product][StandPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_product ?>"
                                />
                            </td>
                            <td>
                                <input
                                    class="js-prices-products-standard-<?= LANG_EN ?>"
                                    name="PRICES_PRODUCTS[<?= StandPrices::TYPE_STANDARD ?>][<?= LANG_EN_UP ?>][<?= $selected_product ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_products[StandPrices::TYPE_STANDARD][LANG_EN_UP][$selected_product][StandPrices::FIELD_PRICE] ?>"
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
                            Используемая валюта
                        </td>
						<td>
							<select name="CURRENCY_PRODUCTS[<?= StandPrices::TYPE_STANDARD ?>][<?= LANG_RU_UP ?>]" class="js-prices-products-currency-standard-<?= LANG_RU ?>">
								<option value="">- не выбрано -</option>
								<? foreach ($currencies as $id => $currency) { ?>
									<option <? if ($id == $event->getCurrencyProductsStandard(LANG_RU_UP)) { ?> selected <? } ?> value="<?= $id ?>">
                                        <?= $currency ?>
                                    </option>
								<? } ?>
							</select>
						</td>
						<td>
							<select name="CURRENCY_PRODUCTS[<?= StandPrices::TYPE_STANDARD ?>][<?= LANG_EN_UP ?>]" class="js-prices-products-currency-standard-<?= LANG_EN ?>">
								<option value="">- не выбрано -</option>
								<? foreach ($currencies as $id => $currency) { ?>
									<option <? if ($id == $event->getCurrencyProductsStandard(LANG_EN_UP)) { ?> selected <? } ?> value="<?= $id ?>">
                                        <?= $currency ?>
                                    </option>
								<? } ?>
							</select>
						</td>
					</tr>
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
                        var currency = $wrap.find('.js-prices-products-currency-individual-' + from).val();
                        
                        $wrap.find('.js-prices-products-individual-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-products-individual-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                        $wrap.find('.js-prices-products-currency-individual-' + site + ' option[value="' + currency + '"]').prop('selected', 'selected');
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
                    <? foreach ($selected_products as $selected_product) { ?>
                        <tr class="js-prices-values">
                            <td>
                                <input type="hidden" name="PRODUCTS[<?= $selected_product ?>]" value="<?= $selected_product ?>" />
                                <?= $products[$selected_product]['NAME'] ?>
                            </td>
                            <td>
                                <input 
                                    class="js-prices-products-individual-<?= LANG_RU ?>"
                                    name="PRICES_PRODUCTS[<?= StandPrices::TYPE_INDIVIDUAL ?>][<?= LANG_RU_UP ?>][<?= $selected_product ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_products[StandPrices::TYPE_INDIVIDUAL][LANG_RU_UP][$selected_product][StandPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_product ?>"
                                />
                            </td>
                            <td>
                                <input
                                    class="js-prices-products-individual-<?= LANG_EN ?>"
                                    name="PRICES_PRODUCTS[<?= StandPrices::TYPE_INDIVIDUAL ?>][<?= $selected_product ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_products[StandPrices::TYPE_INDIVIDUAL][LANG_EN_UP][$selected_product][StandPrices::FIELD_PRICE] ?>"
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
                            Используемая валюта
                        </td>
						<td>
							<select name="CURRENCY_PRODUCTS[<?= StandPrices::TYPE_INDIVIDUAL ?>][<?= LANG_RU_UP ?>]" class="js-prices-products-currency-individual-<?= LANG_RU ?>">
								<option value="">- не выбрано -</option>
								<? foreach ($currencies as $id => $currency) { ?>
									<option <? if ($id == $event->getCurrencyProductsIndividual(LANG_RU_UP)) { ?> selected <? } ?> value="<?= $id ?>">
                                        <?= $currency ?>
                                    </option>
								<? } ?>
							</select>
						</td>
						<td>
							<select name="CURRENCY_PRODUCTS[<?= StandPrices::TYPE_INDIVIDUAL ?>][<?= LANG_EN_UP ?>]" class="js-prices-products-currency-individual-<?= LANG_EN ?>">
								<option value="">- не выбрано -</option>
								<? foreach ($currencies as $id => $currency) { ?>
									<option <? if ($id == $event->getCurrencyProductsIndividual(LANG_EN_UP)) { ?> selected <? } ?> value="<?= $id ?>">
                                        <?= $currency ?>
                                    </option>
								<? } ?>
							</select>
						</td>
					</tr>
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


    
<? 
// стандартный файл-обработчик Битрикс.
include (dirname(__FILE__) . '/iblock_element_edit_base.after.php');
