
/**
 * Функция добавления в корзину.
 */
window['oem-func-days-hours-quantity-cart'] = function($block) {

	var quantity = 0;
	
    var $calendar = $block.find('.js-calendar-content').find('.calendar');
	var $popup    = $block.find('.js-calendar-popup');
	var $mode     = $block.find('.js-calendar-mode');
    var $note     = $block.find('.dates');
	
	var $timemin  = $block.find('.js-days-hours-quantity-time-min option:selected');
    var $timemax  = $block.find('.js-days-hours-quantity-time-max option:selected');
	
	var $quantity = $block.find('.js-days-hours-quantity-quantity');
	
	
	// Количество.
	var quantity = parseInt($quantity.val());
	
	
	// Время по часам.
    var hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-01 ' + $timemax.val()));
    
    if (hours < 0) {
        hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-02 ' + $timemax.val()));
    }
    if (hours != 0) {
        hours = Math.abs(hours);
    }
	hours = parseInt(hours);
	
	
	var dates = 0;
	
	// Выбор диапазона или конкретных дат.
	if ($mode.is(':checked')) {
		var daymin = $block.find('.min-date').val();
		var daymax = $block.find('.max-date').val();
		
		// Комментарий.
		$note.text(Date.getDateFormat(new Date(daymin)) + ' - ' + Date.getDateFormat(new Date(daymax)));
		
		// Общее количество.
		dates = parseInt(Date.getDaysBetween(new Date(daymin), new Date(daymax), true));
	} else {
		var days = $calendar.multiDatesPicker('getDates');
		
		for (let i in days) {
			days[i] = Date.getDateFormat(new Date(days[i]));
		}
		
		// Комментарий.
		$note.text(days.join(', '));
		
		// Общее количество.
		dates = parseInt(days.length);
	}
	
	// Закрытие календаря.
	CalendarClose($popup);
		
	// Протсавление дополнительных параметров.
	$block.find('.js-product-days-hours-quantity-dates').val($note.text());
	$block.find('.js-product-days-hours-quantity-times').val($timemin.val() + ' - ' + $timemax.val());
	$block.find('.js-product-days-hours-quantity-quantity').val($quantity.val());
	
	// Общее количество.
	quantity = dates * hours * quantity;
	
    if (quantity < 0) {
        quantity = 0;
    }	
	
    // Сохранение в корзине.
    PutBasket(quantity, $block);
}


/**
 * Функция добавления товарной позиции.
 */
window['oem-func-days-hours-quantity-more'] = function($block) {
    var $wrapper = $block.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $clone   = $wrapper.find('.js-product-block').first().clone();
	
	$clone.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();
	
    // Сброс параметров.
    window['oem-func-days-hours-quantity-clear']($clone);
	
    // Добавление блока.
    $section.append($clone);
	
    $clone.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-days-hours-quantity-clear'] = function($block, full) {
	var mindate  = $block.find('.calendar').attr('data-date-min'),
        maxdate  = $block.find('.calendar').attr('data-date-max');
	
    full = full || true;
	
    $block.find('.styler').styler();
    
	if (full) {
		$block.find('.js-product-select .js-option-noselect').trigger('click');
    }
    
	$block.find('input.js-param-value').val('');
	$block.find('textarea.js-param-value').html('');
	
	$block.find('.js-days-hours-quantity-time-min option:selected').attr('selected', false);
    $block.find('.js-days-hours-quantity-time-max option:selected').attr('selected', false);
	$block.find('.js-days-hours-quantity-time-min option:first-child').attr('selected', 'selected');
    $block.find('.js-days-hours-quantity-time-max option:first-child').attr('selected', 'selected');
	$block.find('.js-days-hours-quantity-time-min').val($block.find('.js-days-hours-quantity-time-min option:first-child').val()).trigger('refresh');
	$block.find('.js-days-hours-quantity-time-max').val($block.find('.js-days-hours-quantity-time-min option:first-child').val()).trigger('refresh');
	
	$block.find('.js-days-hours-quantity-quantity').val('0').styler();
	
    $block.find('.js-product-price').html('');
    $block.find('.js-product-descr').html('');
    $block.find('.js-product-select-price').hide();
    $block.find('.js-product-select-descr').hide();
    
	$block.find('.calendar').remove();
    $block.find('.calendarPopupContent').append('<div class="calendar" data-date-min="' + mindate + '" data-date-max="' + maxdate+ '" />');
    $block.find('.calendar').multiDatesPicker({
        minDate: new Date(mindate),
        maxDate: new Date(maxdate)
    });
	
	$block.find('.js-calendar-reset').trigger('click');
	
    // Сброс всех свойств товара.
	if (full) {
		ResetParams($block);
	}
}


$(document).ready(function() {
    	
	// Выбор даты.
	$(document).on('click', '.js-block-days-hours-quantity .js-calendar-save', function(e) {
		var $block = $(this).closest('.js-product-block');
		
		window['oem-func-days-hours-quantity-cart']($block);
	});
    
    // Выбор времени.
    $(document).on('change', '.js-block-days-hours-quantity select.js-days-hours-quantity-times', function(e) {
        var $block = $(this).closest('.js-product-block');
        
        window['oem-func-days-hours-quantity-cart']($block);
    });
	
	// Увеличение количества.
    $(document).on('click', '.js-block-days-hours-quantity .js-quantity-dec', function(event) {
        var $block = $(this).closest('.js-product-block');
		var $input = $block.find('input.js-quantity');
		var value  = parseInt($input.val());
        
		if (value > 0) {
			 $input.val(value - 1);
		}
		
        window['oem-func-days-hours-quantity-cart']($block);
	});
	
	// Уменьшение количества.
    $(document).on('click', '.js-block-days-hours-quantity .js-quantity-inc', function(event) {
		var $block = $(this).closest('.js-product-block');
		var $input = $block.find('input.js-quantity');
		var value  = parseInt($input.val());
        
		$input.val(value + 1);
		
        window['oem-func-days-hours-quantity-cart']($block);
	});
	
	
	// Ввод количества.
	$(document).on('click', '.js-block-days-hours-quantity .js-quantity', function(event) {
		var $block = $(this).closest('.js-product-block');
		
        window['oem-func-days-hours-quantity-cart']($block);
	});
});