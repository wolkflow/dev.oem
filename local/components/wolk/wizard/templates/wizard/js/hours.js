
/**
 * Функция добавления в корзину.
 */
window['oem-func-hours-cart'] = function($block) {
	
	var quantity = 0;
	
	var $timemin = $block.find('.js-hours-time-min option:selected');
    var $timemax = $block.find('.js-hours-time-max option:selected');
	
	// Время по часам.
    var hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-01 ' + $timemax.val()));
    
    if (hours < 0) {
        hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-02 ' + $timemax.val()));
    }
    if (hours != 0) {
        hours = Math.abs(hours);
    }
	
	// Протсавление дополнительных параметров.
	$block.find('.js-product-hours-times').val($timemin.val() + ' - ' + $timemax.val());
	
	// Общее количество.
	quantity = hours;
	
    if (quantity < 0) {
        quantity = 0;
    }
	
    // Сохранение в корзине.
    PutBasket(quantity, $block);
}


/**
 * Функция добавления товарной позиции.
 */
window['oem-func-hours-more'] = function($block) {
    var $wrapper = $block.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $clone   = $wrapper.find('.js-product-block').first().clone();
	
	$clone.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();
	
    // Сброс параметров.
    window['oem-func-hours-clear']($clone);
	
    // Добавление блока.
    $section.append($clone);
	
    $clone.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-hours-clear'] = function($block, full) {
	
	full = full || true;
	
    $block.find('.styler').styler();
	
	if (full) {
		$block.find('.js-product-select .js-option-noselect').trigger('click');
    }
	
	$block.find('input.js-param-value').val('');
	$block.find('textarea.js-param-value').html('');
	
	$block.find('.js-hours-time-min option:selected').attr('selected', false);
    $block.find('.js-hours-time-max option:selected').attr('selected', false);
	$block.find('.js-hours-time-min option:first-child').attr('selected', 'selected');
    $block.find('.js-hours-time-max option:first-child').attr('selected', 'selected');
	$block.find('.js-hours-time-min').val($block.find('.js-hours-time-min option:first-child').val()).trigger('refresh');
	$block.find('.js-hours-time-max').val($block.find('.js-hours-time-min option:first-child').val()).trigger('refresh');
	
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
    $(document).on('change', '.js-block-hours select.js-hours-times', function(e) {
        var $block = $(this).closest('.js-product-block');
        
        window['oem-func-hours-cart']($block);
    });
    
});
