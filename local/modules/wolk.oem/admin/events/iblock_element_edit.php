<?php

//////////////////////////
//START of the custom form
//////////////////////////

use Bitrix\Main\Localization\Loc;

use Wolk\OEM\Basket;
use Wolk\OEM\Stand;
use Wolk\OEM\Products\Base as Product;
use Wolk\OEM\Products\Param as SectionParam;
use Wolk\OEM\Prices\Stand as StandPrices;
use Wolk\OEM\Prices\Product as ProductPrices;

IncludeModuleLangFile(__FILE__);

\Bitrix\Main\Loader::includeModule('wolk.core');
\Bitrix\Main\Loader::includeModule('wolk.oem');

// Язык.
$lang = strtoupper(LANGUAGE_ID);

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


// Список разделов.
$sections = [];
foreach ($selected_products as $selected_product_id) { 
	$product = $products[$selected_product_id];
	if (is_object($product)) {
		if (!array_key_exists($product->getSectionID(), $sections)) {
			$sections[$product->getSectionID()] = $product->getSection();
		}
	}
}


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



// Параметры на продукцию.
$result = SectionParam::getList(
    array(
        'filter' => [
			SectionParam::FIELD_EVENT => $event->getID()
		]
    ),
    false
);
$params_sections = array();
while ($item = $result->fetch()) {
	$item['PROPS'] = json_decode($item['UF_PROPS'], true);
	$item['NAMES'] = json_decode($item['UF_NAMES'], true);
	
	$params_sections
		[$item[SectionParam::FIELD_LANG]]
		[$item[SectionParam::FIELD_SECTION]] 
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

<? $tabControl->BeginCustomField('SECTIONS_PROPERTIES_RU', Loc::getMessage('TAB_SECTIONS_PROPERTIES_RU')); ?>
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
                    <? foreach ($sections as $section) { ?>
						<tr>
							<td>
								<b><?= $section->getTitle() ?></b>
							</td>
							<td width="35%">
								<? $props = $section->getProperties() ?>
								<? foreach ($props as $prop) { ?>
									<div style="border: 1px dotted #777777; border-radius: 5px; margin-bottom: 2px; padding: 5px; overflow: hidden;">
										<label>
											<input 
												type="checkbox" 
												name="PARAMS_SECTIONS[RU][<?= $section->getID() ?>][PROPS][REQUIRED][]" 
												value="<?= $prop ?>" 
												<?= (in_array($prop, $params_sections['RU'][$section->getID()]['PROPS']['REQUIRED'])) ? ('checked') : ('') ?>
											/>
											<span><?= Loc::getMessage('PROP_' . $prop) ?></span>
										</label>
										
										<? // Если у продукции есть оплата за символы на фризовой панели. // ?>
										<? if ($section->isSpecialType(Product::SPECIAL_TYPE_FASCIA) && $prop == Basket::PARAM_TEXT) { ?>
											<input 
												type="number" 
												step="1"
												title="<?= Loc::getMessage('FASCIA_FREE_QUANTITY') ?>"
												name="PARAMS_SECTIONS[RU][<?= $section->getID() ?>][PROPS][FASCIA]" 
												value="<?= $params_sections['RU'][$section->getID()]['PROPS']['FASCIA'] ?>"
												style="float: right;"
											/>
										<? } ?>
									</div>
								<? } ?>
							</td>
							<td>
								<? foreach ($props as $prop) { ?>
									<input 
										type="text" 
										name="PARAMS_SECTIONS[RU][<?= $section->getID() ?>][NAMES][<?= $prop ?>]" 
										value="<?= $params_sections['RU'][$section->getID()]['NAMES'][$prop] ?>" 
										style="margin: 2px 0 2px 0;"
										<?= ($prop == Basket::PARAM_FORM_HANGING_STRUCTURE) ? ('disabled') : ('') ?>
									/>
								<? } ?>
							</td>
							<td>
								<textarea name="PARAMS_SECTIONS[RU][<?= $section->getID() ?>][NOTE]" cols="30" rows="4" style="resize: none;"><?= $params_sections['RU'][$section->getID()]['UF_NOTE'] ?></textarea>
							</td>
						</tr>
					<? } ?>
				</tbody>
			</table>
		</td>
	</tr>
<? $tabControl->EndCustomField('SECTIONS_PROPERTIES_RU', ''); ?>



<? $tabControl->BeginCustomField('SECTIONS_PROPERTIES_EN', Loc::getMessage('TAB_SECTIONS_PROPERTIES_EN')); ?>
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
                    <? foreach ($sections as $section) { ?>
						<tr>
							<td>
								<b><?= $section->getTitle() ?></b>
							</td>
							<td width="35%">
								<? $props = $section->getProperties() ?>
								<? foreach ($props as $prop) { ?>
									<div style="border: 1px dotted #777777; border-radius: 5px; margin-bottom: 2px; padding: 5px; overflow: hidden;">
										<label>
											<input 
												type="checkbox" 
												name="PARAMS_SECTIONS[EN][<?= $section->getID() ?>][PROPS][REQUIRED][]" 
												value="<?= $prop ?>" 
												<?= (in_array($prop, $params_sections['EN'][$section->getID()]['PROPS']['REQUIRED'])) ? ('checked') : ('') ?>
											/>
											<span><?= Loc::getMessage('PROP_' . $prop) ?></span>
										</label>
										<? // Если у продукции есть оплата за символы на фризовой панели. // ?>
										<? if ($section->isSpecialType(Product::SPECIAL_TYPE_FASCIA) && $prop == Basket::PARAM_TEXT) { ?>
											<input 
												type="number" 
												step="1"
												title="<?= Loc::getMessage('FASCIA_FREE_QUANTITY') ?>"
												name="PARAMS_SECTIONS[EN][<?= $section->getID() ?>][PROPS][FASCIA]" 
												value=""
												style="float: right;"
											/>
										<? } ?>
									</div>
								<? } ?>
							</td>
							<td>
								<? foreach ($props as $prop) { ?>
									<input 
										type="text" 
										name="PARAMS_SECTIONS[EN][<?= $section->getID() ?>][NAMES][<?= $prop ?>]" 
										value="<?= $params_sections['EN'][$section->getID()]['NAMES'][$prop] ?>" 
										style="margin: 2px 0 2px 0;"
										<?= ($prop == Basket::PARAM_FORM_HANGING_STRUCTURE) ? ('disabled') : ('') ?>
									/>
								<? } ?>
							</td>
							<td>
								<textarea name="PARAMS_SECTIONS[EN][<?= $section->getID() ?>][NOTE]" cols="30" rows="4" style="resize: none;"><?= $params_sections['EN'][$section->getID()]['UF_NOTE'] ?></textarea>
							</td>
						</tr>
					<? } ?>
				</tbody>
			</table>
		</td>
	</tr>
<? $tabControl->EndCustomField('SECTIONS_PROPERTIES_EN', ''); ?>



<? $tabControl->BeginCustomField('STANDS_PRICES_STANDARD', 'Цены на стенды (стандартные)'); ?>
    <tr>
        <td>
            <script>
                $(document).ready(function() {
					// Использование цен другого языка (сайта).
                    $('.js-prices-stands-use-standard-site').on('change', function() {
                        var $that = $(this);
                        var $wrap = $that.closest('.js-prices-wrapper');
                        
                        var site = $that.data('site');
                        var from = $that.find('option:selected').val();
                        
                        if (from == undefined) {
                            return;
                        }
                        
                        $wrap.find('.js-prices-stands-standard-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-stands-standard-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                    });
					
					// Использование цен другого типа.
					$('.js-prices-stands-use-standard-type').on('click', function() {
                        var $self = $(this);
                        
						var type = $self.data('type');
                        var site = $self.data('site');
                        
						$('#js-table-stands-prices-standard-id .js-prices-stands-standard-' + site).each(function() {
							var $that = $(this);
							var $item = $('#js-table-stands-prices-' + type +'-id').find('.js-prices-stands-' + type + '-' + site + '[name="' + $that.prop('name').replace('STANDARD', type.toUpperCase()) + '"]');
							
                            if ($item.length) {
								$that.val($item.val());
							}
                        });
                    });
                });
            </script>
			
            <table id="js-table-stands-prices-standard-id" class="js-prices-wrapper">
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
                                    class="js-prices-stands-standard-<?= LANG_RU ?> js-prices-price"
                                    name="PRICES_STANDS[<?= StandPrices::TYPE_STANDARD ?>][<?= LANG_RU_UP ?>][<?= $selected_stand ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_stands[StandPrices::TYPE_STANDARD][LANG_RU_UP][$selected_stand][StandPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_stand ?>"
                                />
                            </td>
                            <td>
                                <input
                                    class="js-prices-stands-standard-<?= LANG_EN ?> js-prices-price"
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
							<select class="js-prices-stands-use-standard-site" data-site="<?= LANG_RU ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_EN ?>">Цены EN</option>
							</select>
						</td>
						<td>
							<select class="js-prices-stands-use-standard-site" data-site="<?= LANG_EN ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_RU ?>">Цены RU</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
                            <b>Использлвать цены индивидуальной застройки</b>
                        </td>
						<td>
							<input type="button" value="Копировать" class="js-prices-stands-use-standard-type btn" data-type="individual" data-site="<?= LANG_RU ?>" />
						</td>
						<td>
							<input type="button" value="Копировать" class="js-prices-stands-use-standard-type btn" data-type="individual" data-site="<?= LANG_EN ?>" />
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
					// Копирование цен из другого языка (сайта).
                    $('.js-prices-stands-use-individual-site').on('change', function() {
                        var $that = $(this);
                        var $wrap = $that.closest('.js-prices-wrapper');
                        
                        var site = $that.data('site');
                        var from = $that.find('option:selected').val();
                        
                        if (from == undefined) {
                            return;
                        }
                        
                        $wrap.find('.js-prices-stands-individual-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-stands-individual-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                    });
					
					
					// Использование цен другого типа.
					$('.js-prices-stands-use-individual-type').on('click', function() {
                        var $self = $(this);
                        
						var type = $self.data('type');
                        var site = $self.data('site');
                        
						$('#js-table-stands-prices-individual-id .js-prices-stands-individual-' + site).each(function() {
							var $that = $(this);
							var $item = $('#js-table-stands-prices-' + type +'-id').find('.js-prices-stands-' + type + '-' + site + '[name="' + $that.prop('name').replace('INDIVIDUAL', type.toUpperCase()) + '"]');
							
                            if ($item.length) {
								$that.val($item.val());
							}
                        });
                    });
                });
            </script>
        
            <table id="js-table-stands-prices-individual-id" class="js-prices-wrapper">
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
                                    class="js-prices-stands-individual-<?= LANG_RU ?> js-prices-price"
                                    name="PRICES_STANDS[<?= StandPrices::TYPE_INDIVIDUAL ?>][<?= LANG_RU_UP ?>][<?= $selected_stand ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_stands[StandPrices::TYPE_INDIVIDUAL][LANG_RU_UP][$selected_stand][StandPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_stand ?>"
                                />
                            </td>
                            <td>
                                <input
                                    class="js-prices-stands-individual-<?= LANG_EN ?> js-prices-price"
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
							<select class="js-prices-stands-use-individual-site" data-site="<?= LANG_RU ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_EN ?>">Цены EN</option>
							</select>
						</td>
						<td>
							<select class="js-prices-stands-use-individual-site" data-site="<?= LANG_EN ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_RU ?>">Цены RU</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
                            <b>Использлвать цены стандартной застройки</b>
                        </td>
						<td>
							<input type="button" value="Копировать" class="js-prices-stands-use-individual-type btn" data-type="standard" data-site="<?= LANG_RU ?>" />
						</td>
						<td>
							<input type="button" value="Копировать" class="js-prices-stands-use-individual-type btn" data-type="standard" data-site="<?= LANG_EN ?>" />
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
					// Копирование цен из другого языка (сайта).
                    $('.js-prices-products-standard-use-site').on('change', function() {
                        var $that = $(this);
                        var $wrap = $that.closest('.js-prices-wrapper');
                        
                        var site = $that.data('site');
                        var from = $that.find('option:selected').val();
                        
                        if (from == undefined) {
                            return;
                        }
                        $wrap.find('.js-prices-products-standard-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-products-standard-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                    });
					
					
					// Использование цен другого типа.
					$('.js-prices-products-use-standard-type').on('click', function() {
                        var $self = $(this);
                        
						var type = $self.data('type');
                        var site = $self.data('site');
                        
						$('#js-table-products-prices-standard-id .js-prices-products-standard-' + site).each(function() {
							var $that = $(this);
							var $item = $('#js-table-products-prices-' + type +'-id').find('.js-prices-products-' + type + '-' + site + '[name="' + $that.prop('name').replace('STANDARD', type.toUpperCase()) + '"]');
							
                            if ($item.length) {
								$that.val($item.val());
							}
                        });
                    });
                });
            </script>
        
            <table id="js-table-products-prices-standard-id" class="js-prices-wrapper">
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
                                    class="js-prices-products-standard-<?= LANG_RU ?> js-prices-price"
                                    name="PRICES_PRODUCTS[<?= ProductPrices::TYPE_STANDARD ?>][<?= LANG_RU_UP ?>][<?= $selected_product ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_products[ProductPrices::TYPE_STANDARD][LANG_RU_UP][$selected_product][ProductPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_product ?>"
                                />
                            </td>
                            <td>
                                <input
                                    class="js-prices-products-standard-<?= LANG_EN ?> js-prices-price"
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
							<select class="js-prices-products-standard-use-site" data-site="<?= LANG_RU ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_EN ?>">Цены EN</option>
							</select>
						</td>
						<td>
							<select class="js-prices-products-standard-use-site" data-site="<?= LANG_EN ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_RU ?>">Цены RU</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
                            <b>Использлвать цены индивидуальной застройки</b>
                        </td>
						<td>
							<input type="button" value="Копировать" class="js-prices-products-use-standard-type btn" data-type="individual" data-site="<?= LANG_RU ?>" />
						</td>
						<td>
							<input type="button" value="Копировать" class="js-prices-products-use-standard-type btn" data-type="individual" data-site="<?= LANG_EN ?>" />
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
					// Копирование цен из другого языка (сайта).
                    $('.js-prices-products-individual-use-site').on('change', function() {
                        var $that = $(this);
                        var $wrap = $that.closest('.js-prices-wrapper');
                        
                        var site = $that.data('site');
                        var from = $that.find('option:selected').val();
                        
                        if (from == undefined) {
                            return;
                        }
						
                        $wrap.find('.js-prices-products-individual-' + from).each(function() {
                            var value = $(this).val();
                            var stand = $(this).data('stand');
                            
                            $wrap.find('.js-prices-products-individual-' + site + '[data-stand="' + stand + '"]').val(value);
                        });
                    });
					
					
					// Использование цен другого типа.
					$('.js-prices-products-use-individual-type').on('click', function() {
                        var $self = $(this);
                        
						var type = $self.data('type');
                        var site = $self.data('site');
                        
						$('#js-table-products-prices-individual-id .js-prices-products-individual-' + site).each(function() {
							var $that = $(this);
							var $item = $('#js-table-products-prices-' + type +'-id').find('.js-prices-products-' + type + '-' + site + '[name="' + $that.prop('name').replace('INDIVIDUAL', type.toUpperCase()) + '"]');
							
                            if ($item.length) {
								$that.val($item.val());
							}
                        });
                    });
                });
            </script>
        
            <table id="js-table-products-prices-individual-id" class="js-prices-wrapper">
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
                                    class="js-prices-products-individual-<?= LANG_RU ?> js-prices-price"
                                    name="PRICES_PRODUCTS[<?= ProductPrices::TYPE_INDIVIDUAL ?>][<?= LANG_RU_UP ?>][<?= $selected_product ?>]" 
                                    type="text" 
                                    size="15" 
                                    value="<?= $prices_products[ProductPrices::TYPE_INDIVIDUAL][LANG_RU_UP][$selected_product][ProductPrices::FIELD_PRICE] ?>"
                                    data-stand="<?= $selected_product ?>"
                                />
                            </td>
                            <td>
                                <input
                                    class="js-prices-products-individual-<?= LANG_EN ?> js-prices-price"
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
							<select class="js-prices-products-individual-use-site" data-site="<?= LANG_RU ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_EN ?>">Цены EN</option>
							</select>
						</td>
						<td>
							<select class="js-prices-products-individual-use-site" data-site="<?= LANG_EN ?>">
								<option value="">- не использовать -</option>
								<option value="<?= LANG_RU ?>">Цены RU</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
                            <b>Использлвать цены стандартной застройки</b>
                        </td>
						<td>
							<input type="button" value="Копировать" class="js-prices-products-use-individual-type btn" data-type="standard" data-site="<?= LANG_RU ?>" />
						</td>
						<td>
							<input type="button" value="Копировать" class="js-prices-products-use-individual-type btn" data-type="standard" data-site="<?= LANG_EN ?>" />
						</td>
					</tr>
                </tfoot>
            </table>
        </td>
    </tr>
<? $tabControl->EndCustomField('PRODUCTS_PRICES_INDIVIDUAL', ''); ?>

<?
// Построение дерева разделов.
$result = CIBlockSection::getList(
	['DEPTH_LEVEL' => 'ASC', 'SORT' => 'ASC'], 
	['IBLOCK_ID' => IBLOCK_PRODUCTS_ID],
	false,
	['ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'UF_LANG_TITLE_RU', 'UF_LANG_TITLE_EN']
);

$links = [];
$sects = [];
$links[0] = &$sects;
while ($section = $result->getNext()) {
	foreach ($products as $product) {
		if ($product->getSectionID() == $section['ID']) {
			$section['ITEMS'][$product->getID()] = $product;
		}
	}
    $links[intval($section['IBLOCK_SECTION_ID'])]['ITEMS'][$section['ID']] = $section;
    $links[$section['ID']] = &$links[intval($section['IBLOCK_SECTION_ID'])]['ITEMS'][$section['ID']];
}
unset($result, $links);

?>

<? $tabControl->BeginCustomField('PRODUCTS_ITEMS_STANDARD', 'Список продукции (стандартная застройка)'); ?>
	<? $propfields = $PROP['PRODUCTS_STANDARD'] ?>
    <tr>
        <td class="adm-detail-valign-top" colspan="2">
			<div id="js-products-standard-block-id">
				<select name="PRODUCTS_STANDARD[]" multiple="multiple" size="20" style="margin-bottom: 10px;">
					<? foreach ($sects['ITEMS'] as $group) { ?>
						<optgroup label="<?= $group['UF_LANG_TITLE_' . $lang] ?>">
							<? foreach ($group['ITEMS'] as $subgroup) { ?>
								<optgroup label=" . <?= $subgroup['UF_LANG_TITLE_' . $lang] ?>">
									<? if ($group['CODE'] == Wolk\OEM\Products\Section::TYPE_EQUIPMENTS) { ?>
										<? foreach ($subgroup['ITEMS'] as $section) { ?>
											<? foreach ($section['ITEMS'] as $product) { ?>
												<option value="<?= $product->getID() ?>" <?= (in_array($product->getID(), $propfields['VALUE'])) ? ('selected') : ('') ?>>
													<?= $product->getTitle($lang) ?>
												</option>
											<? } ?>
										<? } ?>
									<? } else { ?>
										<? foreach ($subgroup['ITEMS'] as $section) { ?>
											<optgroup label=" .  . <?= $section['UF_LANG_TITLE_' . $lang] ?>">
												<? foreach ($section['ITEMS'] as $product) { ?>
													<option value="<?= $product->getID() ?>" <?= (in_array($product->getID(), $propfields['VALUE'])) ? ('selected') : ('') ?>>
														<?= $product->getTitle($lang) ?>
													</option>
												<? } ?>
											</optgroup>
										<? } ?>
									<? } ?>
								</optgroup>
							<? } ?>
						</optgroup>
					<? } ?>
				</select>
				<br/>
				<? // _ShowPropertyField('PROP['.$propfields['ID'].']', $propfields, $propfields['VALUE'], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy) ?>
				<input type="button" id="js-use-individual-products-id" class="btn" value="Использовать продукцию индивидуальной застройки" />
			</div>
		</td>
    </tr>
<? $tabControl->EndCustomField('PRODUCTS_ITEMS_STANDARD', ''); ?>


<? $tabControl->BeginCustomField('PRODUCTS_ITEMS_INDIVIDUAL', 'Список продукции (индивидуальная застройка)'); ?>
	<? $propfields = $PROP['PRODUCTS_INDIVIDUAL'] ?>
    <tr>
        <td class="adm-detail-valign-top" colspan="2">
			<div id="js-products-individual-block-id">
				<select name="PRODUCTS_INDIVIDUAL[]" multiple="multiple" size="20" style="margin-bottom: 10px;">
					<? foreach ($sects['ITEMS'] as $group) { ?>
						<optgroup label="<?= $group['UF_LANG_TITLE_' . $lang] ?>">
							<? foreach ($group['ITEMS'] as $subgroup) { ?>
								<optgroup label=" . <?= $subgroup['UF_LANG_TITLE_' . $lang] ?>">
									<? if ($group['CODE'] == Wolk\OEM\Products\Section::TYPE_EQUIPMENTS) { ?>
										<? foreach ($subgroup['ITEMS'] as $section) { ?>
											<? foreach ($section['ITEMS'] as $product) { ?>
												<option value="<?= $product->getID() ?>" <?= (in_array($product->getID(), $propfields['VALUE'])) ? ('selected') : ('') ?>>
													<?= $product->getTitle($lang) ?>
												</option>
											<? } ?>
										<? } ?>
									<? } else { ?>
										<? foreach ($subgroup['ITEMS'] as $section) { ?>
											<optgroup label=" .  . <?= $section['UF_LANG_TITLE_' . $lang] ?>">
												<? foreach ($section['ITEMS'] as $product) { ?>
													<option value="<?= $product->getID() ?>" <?= (in_array($product->getID(), $propfields['VALUE'])) ? ('selected') : ('') ?>>
														<?= $product->getTitle($lang) ?>
													</option>
												<? } ?>
											</optgroup>
										<? } ?>
									<? } ?>
								</optgroup>
							<? } ?>
						</optgroup>
					<? } ?>
				</select>
				<br/>
				<? // _ShowPropertyField('PROP['.$propfields['ID'].']', $propfields, $propfields['VALUE'], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy) ?>
				<input type="button" id="js-use-standard-products-id" class="btn" value="Использовать продукцию стандартной застройки" />
			</div>
		</td>
    </tr>
<? $tabControl->EndCustomField('PRODUCTS_ITEMS_INDIVIDUAL', ''); ?>


<script>
	function CheckLangFields(element) {
		if (element.checked) {
			$(element).closest('.edit-table').find("input, textarea, select").not(element).attr('disabled', false);
		} else {
			$(element).closest('.edit-table').find("input, textarea, select").not(element).attr('disabled', true);
		}
	}
	
	$(document).ready(function() {
		$(document).on('click', '#js-use-individual-products-id', function() {
			var $select = $('#js-products-standard-block-id select');
			
			$select.find('option').prop('selected', false);
			$('#js-products-individual-block-id select option:selected').each(function() {
				$select.find('option[value="' + $(this).val() + '"]').prop('selected', 'selected');
			});
		});
		
		$(document).on('click', '#js-use-standard-products-id', function() {
			var $select = $('#js-products-individual-block-id select');
			
			$select.find('option').prop('selected', false);
			$('#js-products-standard-block-id select option:selected').each(function() {
				$select.find('option[value="' + $(this).val() + '"]').prop('selected', 'selected');
			});
		});
	});
</script>


<? /*
<? if (empty($ID)) { ?>
	<script>
		var binds = ['standard' => true, 'individual' => true];
		
		$(document).on('change', '#js-products-standard-block-id input', function(e) {
			binds['standard'] = false;
		});
		
		$(document).on('change', '#js-products-individual-block-id input', function(e) {
			binds['individual'] = false;
		});
	</script>
<? } ?>
*/ ?>



    
<? 
// стандартный файл-обработчик Битрикс.
include (dirname(__FILE__) . '/iblock_element_edit_base.after.php');
