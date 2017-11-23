
/**
 * Функция добавления в корзину.
 */
window['oem-func-hours-quantity-cart'] = function($block) {
	
	var quantity = 0;
	
	var $timemin  = $block.find('.js-hours-quantity-time-min option:selected');
    var $timemax  = $block.find('.js-hours-quantity-time-max option:selected');
	
	var $quantity = $block.find('.js-hours-quantity-quantity');
	
	// Количество.
	var quantity = parseInt($quantity.val());
	
	
	// Время по часам.
    var hours = Date.getHoursBetween($timemin.val() $timemax.val());
	
		
	// Протсавление дополнительных параметров.
	$block.find('.js-product-hours-quantity-times').val($timemin.val() + ' - ' + $timemax.val());
	$block.find('.js-product-hours-quantity-quantity').val($quantity.val());
	
	// Общее количество.
	quantity = hours * quantity;
	
    if (quantity < 0) {
        quantity = 0;
    }	
	
    // Сохранение в корзине.
    PutBasket(quantity, $block);
}


/**
 * Функция добавления товарной позиции.
 */
window['oem-func-hours-quantity-more'] = function($block) {
    var $wrapper = $block.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $clone   = $wrapper.find('.js-product-block').first().clone();
	
	$clone.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();
	$clone.find('.jq-file__name, .jq-file__browse').remove();
	$clone.find('.js-param-x-image').removeClass('changed').hide();
	
	$clone.attr('data-bid', '');
	
    // Сброс параметров.
    window['oem-func-hours-quantity-clear']($clone);
	
    // Добавление блока.
    $section.append($clone);
	
    $clone.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-hours-quantity-clear'] = function($block, full) {
    
	full = (typeof full !== 'undefined') ?  (full) : (true);
	
    $block.find('.styler').styler();
    
	if (full) {
		$block.find('.js-product-select .js-option-noselect').trigger('click');
	}
    
	$block.find('input.js-param-value').val('');
	$block.find('textarea.js-param-value').html('');
	
	$block.find('.js-hours-quantity-time-min option:selected').attr('selected', false);
    $block.find('.js-hours-quantity-time-max option:selected').attr('selected', false);
	$block.find('.js-hours-quantity-time-min option:first-child').attr('selected', 'selected');
    $block.find('.js-hours-quantity-time-max option:first-child').attr('selected', 'selected');
	$block.find('.js-hours-quantity-time-min').val($block.find('.js-hours-quantity-time-min option:first-child').val()).trigger('refresh');
	$block.find('.js-hours-quantity-time-max').val($block.find('.js-hours-quantity-time-min option:first-child').val()).trigger('refresh');
	
	$block.find('.js-hours-quantity-quantity').val('0').styler();
	
    $block.find('.js-product-price').html('');
    $block.find('.js-product-descr').html('');
    $block.find('.js-product-select-price').hide();
    $block.find('.js-product-select-descr').hide();
    	
    // Сброс всех свойств товара.
	if (full) {
		ResetParams($block);
	}
}


$(document).ready(function() {
    
    // Выбор времени.
    $(document).on('change', '.js-block-hours-quantity select.js-hours-quantity-times', function(e) {
        var $block = $(this).closest('.js-product-block');
        
        window['oem-func-hours-quantity-cart']($block);
    });
	
	// Увеличение количества.
    $(document).on('click', '.js-block-hours-quantity .js-quantity-dec', function(event) {
        var $block = $(this).closest('.js-product-block');
		var $input = $block.find('input.js-quantity');
		var value  = parseInt($input.val());
        
		if (value > 0) {
			 $input.val(value - 1);
		}
		
        window['oem-func-hours-quantity-cart']($block);
	});
	
	// Уменьшение количества.
    $(document).on('click', '.js-block-hours-quantity .js-quantity-inc', function(event) {
		var $block = $(this).closest('.js-product-block');
		var $input = $block.find('input.js-quantity');
		var value  = parseInt($input.val());
        
		$input.val(value + 1);
		
        window['oem-func-hours-quantity-cart']($block);
	});
	
	
	// Ввод количества.
	$(document).on('click', '.js-block-hours-quantity .js-quantity', function(event) {
		var $block = $(this).closest('.js-product-block');
		
        window['oem-func-hours-quantity-cart']($block);
	});
});