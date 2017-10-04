
/**
 * Функция добавления в корзину.
 */
window['oem-func-days-hours-cart'] = function($block) {
	var pid = 0;
	var quantity = 0;
	
    var $calendar = $block.find('.js-calendar-content').find('.calendar');
	var $popup    = $block.find('.js-calendar-popup');
	var $mode     = $block.find('.js-calendar-mode');
    var $note     = $block.find('.dates');
	
	var $timemin = $block.find('.js-days-hours-time-min option:selected');
    var $timemax = $block.find('.js-days-hours-time-max option:selected');
	
	
	// Получение ID продукции.
    if ($block.find('.js-product-select').length) {
        pid = $block.find('.js-product-select option:selected').val();
    } else {
        pid = $block.find('.js-product-select').data('pid');
    }
	
	
	// Время по часам.
    var hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-01 ' + $timemax.val()));
    
    if (hours < 0) {
        hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-02 ' + $timemax.val()));
    }
    if (hours != 0) {
        hours = Math.abs(hours);
    }
		
	
	// Выбор диапазона или конкретных дат.
	if ($mode.is(':checked')) {
		var daymin = $block.find('.min-date').val();
		var daymax = $block.find('.max-date').val();
		
		// Комментарий.
		$note.text(Date.getDateFormat(new Date(daymin)) + ' - ' + Date.getDateFormat(new Date(daymax)));
		
		// Общее количество.
		quantity = Date.getDaysBetween(new Date(daymin), new Date(daymax), true);
	} else {
		var days = $calendar.multiDatesPicker('getDates');
		
		for (let i in days) {
			days[i] = Date.getDateFormat(new Date(days[i]));
		}
		
		// Комментарий.
		$note.text(days.join(', '));
		
		// Общее количество.
		quantity = days.length;
	}
	
	// Закрытие календаря.
	CalendarClose($popup);
		
	// Протсавление дополнительных параметров.
	$block.find('.js-product-days-hours-dates').val($note.text());
	$block.find('.js-product-days-hours-times').val($timemin.val() + ' - ' + $timemax.val());
	
	console.log(quantity, hours);
	
	// Общее количество.
	quantity = quantity * hours;
	
    if (quantity < 0) {
        quantity = 0;
    }	
	
    // Сохранение в корзине.
    PutBasket(pid, quantity, $block);
}


/**
 * Функция добавления товарной позиции.
 */
window['oem-func-days-hours-more'] = function($block) {
    var $wrapper = $block.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $clone   = $wrapper.find('.js-product-block').first().clone();
	
	$clone.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();
	
    // Сброс параметров.
    window['oem-func-days-hours-clear']($clone);
	
    // Добавление блока.
    $section.append($clone);
	
    $clone.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-days-hours-clear'] = function($block) {
	var mindate  = $block.find('.calendar').attr('data-date-min'),
        maxdate  = $block.find('.calendar').attr('data-date-max');
	
    $block.attr('data-bid', '');
    //$block.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();
    $block.find('.styler').styler();
    $block.find('.js-product-select .js-option-noselect').trigger('click');
    
	$block.find('input.js-param-value').val('');
	$block.find('textarea.js-param-value').html('');
	
	$block.find('.js-days-hours-time-min option:selected').attr('selected', false);
    $block.find('.js-days-hours-time-max option:selected').attr('selected', false);
	$block.find('.js-days-hours-time-min option:first-child').attr('selected', 'selected');
    $block.find('.js-days-hours-time-max option:first-child').attr('selected', 'selected');
	$block.find('.js-days-hours-time-min').val('').styler();
	$block.find('.js-days-hours-time-max').val('').styler();
	
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
    ResetParams($block);
}


$(document).ready(function() {
    	
	// Выбор даты.
	$(document).on('click', '.js-block-days-hours .js-calendar-save', function(e) {
		var $block = $(this).closest('.js-product-block');
		
		window['oem-func-days-hours-cart']($block);
	});
    
    // Выбор времени.
    $(document).on('change', 'select.js-days-hours-times', function(e) {
        var $block = $(this).closest('.js-product-block');
        
        window['oem-func-days-hours-cart']($block);
    });
    
});